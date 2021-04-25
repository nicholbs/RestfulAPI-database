<?php
//require_once 'RESTConstants.php';
// require_once 'db/OrderModel.php';
// require_once 'constants.php';

use Symfony\Component\CssSelector\Parser\Token;

class CustomerModel extends DB
{
     /**
    * Retrieve all production plans from database in a four week intervall
    *
    * @author Nicholas Bodvin Sellevåg
    */ 
    public function retrieveProdPlan(): array
    {
        // Body - endpoint
        // eksisterer tingen i databasen
        // sjekk at resultat fra query inneholder det det skal.
        // echo "retrieveProdPlan";

        // Prepare and send request to database which retrieves production plans
        $stmt = $this ->db ->query('SELECT * FROM `production_plans` WHERE day >= DATE_ADD(
            DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) DAY),
            INTERVAL - 4 WEEK)');
        $res =$stmt ->fetchAll();
        
         // If request is empty no record was found
         if (empty($res)) {
            return $res;
        } 

        // If request is not empty check that response contains all attributes expected from database
        else {
            $arr = array("ski_type", "day", "quantity");
            $this->validateRespone($arr, $res);
            return $res;
        }
        return $res;
    }
    
    
    /**
    * Retrieve an order and validates content of response
    *
    * @param int $customer_nr id of customer
    * @param int $order_nr id of order
    * @author Nicholas Bodvin Sellevåg
    */ 
    public function retrieveCustomerOrder($customer_nr, $order_nr)
    {
        // Prepare and send request to database which retrieves appropriate order
        $stmt = $this ->db ->prepare('SELECT order_nr, state, date_placed, price, order_aggregate FROM `orders` WHERE `customer_id` = :customerId AND `order_nr` = :orderNr');
        $stmt->bindValue(':customerId', $customer_nr);
        $stmt->bindValue(':orderNr', $order_nr);
        $stmt->execute();   
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // If request is empty no record was found
        if (empty($res)) {
            return $res;
        } 
        // If request is not empty check that response contains all attributes expected from database
        else {
            $arr = array("order_nr", "state", "date_placed", "price");
            $this->validateRespone($arr, $res);
            return $res;
        }
        
    }
    
        
    /**
    * Delete an order and validates content of response
    *
    * Firstly a check of wether record exist
    * Then deletes record
    * Lastly checks if record was successfully deleted
    *
    * @param int $customer_nr id of customer
    * @param int $order_nr id of order
    * @see CustomerModel::retrieveCustomerOrder()
    * @author Nicholas Bodvin Sellevåg
    */ 
    public function deleteCustomerOrder($customer_nr, $order_nr)
    {
        $customerOrder = $this->retrieveCustomerOrder($customer_nr, $order_nr);
        $res = array();
        $res['order_nr'] = $order_nr;
        $res['customer'] = $customer_nr;


        if (empty($customerOrder)) {
            throw new BusinessException(httpErrorConst::notFound, "Record was not found");
        } else {
            $stmt = $this->db->prepare('DELETE FROM orders WHERE order_nr = ?');
            $stmt->execute([$order_nr]);

            $customerOrder = $this->retrieveCustomerOrder($customer_nr, $order_nr);
            if (empty($customerOrder)) {
                $res['status'] = "Deleted";
                return $res;
            }
            else {
                throw new APIException(httpErrorConst::serverError, "Was not able to delete the record");
            }
        }
    }

    // create an order
    public function postCustomerOrder($requestBody)
    {
        // TODO - Rollback minefelt for når ting er fucky + exceptions

        // Get customer's buying price
        $stmt = $this->db->prepare('SELECT `buying_price` FROM `customers` WHERE `customer_id` = :customerId');
        $stmt->bindValue(':customerId', $requestBody['customer']);
        $stmt->execute();
        $buying_price = $stmt->fetch(PDO::FETCH_ASSOC)['buying_price'];

        $skis = $requestBody['skis']; //For ease of reading

        // Creates list of ski types in format (2, 3, ...) for query
        $ski_types = "(" . strval($skis[0]['type']); //Note to self: strval() = toString()
        for($i = 1; $i < count($skis); $i++)
            $ski_types .= ", " . strval($skis[$i]['type']);
        $ski_types .= ")";

        // Get msrp of relevant ski types
        $stmt = $this->db->prepare('SELECT `msrp` FROM `ski_types` WHERE `type_id` IN ' . $ski_types);
        $stmt->execute();
        $msrp = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculate total price of order
        $price = 0;
        for($i = 0; $i < count($skis); $i++)
            $price += $msrp[$i]['msrp'] * $skis[$i]['quantity'];
        $price *= $buying_price;

        //-- You've entered the TRANSACTION ZONE --
        $this->db->beginTransaction();

        // Insert order
        $query = 'INSERT INTO orders (price, customer_id) VALUES (:price, :customer_id)';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':price', $price);
        $stmt->bindValue(':customer_id', $requestBody['customer']);
        $stmt->execute();
        
        $order_nr = $this->db->lastInsertId();

        // Insert sub orders
        $query = 'INSERT INTO sub_orders (order_nr, type_id, ski_quantity) VALUES';
        for($i = 0; $i < count($skis); $i++){
            $query .= ' (' . $order_nr . ', ' . $skis[$i]['type'] . ', ' . $skis[$i]['quantity'] . ')'; //(order_nr, type_id, ski_quantity)
            if(!$i + 1 == count($skis)) //Adds comma after every value set but the last
            $query .= ',';
        }
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Gets order view for inserted order
        $query = 'SELECT type_id, ski_quantity, msrp, subtotal FROM order_view WHERE order_nr = :order_nr';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':order_nr', $order_nr);
        $stmt->execute();
        
        //-- We hope to see you again! --
        $this->db->commit();

        // Create response
        $res = array();
        $res['order_nr'] = $order_nr;
        $res['customer'] = $requestBody['customer'];
        $res['buying_price'] = $buying_price;
        $res['total'] = $price;
        $res['sub_orders'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        print_r($res);
        return $res;
    }


        
    /**
    * Validates that response from database contains the appropriate keys
    *
    * @param array $arr - array containing keys to check for
    * @param $response - array which keys is checked
    * @author Nicholas Bodvin Sellevåg
    */ 
    private function validateRespone(array $arr, array $response) {

        foreach ($response as &$value) {
            $index = 0;
            foreach ($arr as &$value) {
                if (!array_key_exists($value, $response[$index])) {
                    $reason = "Request was not processed due to missing the value: ";
                    $reason .= $value;
                    throw new BusinessException(httpErrorConst::badRequest, $reason);
                } 
            }
            $index++;
        }
    }

    // Gir det noe mening å ha orders??
    // get specific orders
    public function getCustomerOrders()
    {
        echo "\ngetCustomerOrders\n";
        
    }
    // create orders
    public function deleteCustomerOrders()
    {
        echo "\ndeleteCustomerOrders\n";
        
    }
    // create orders
    public function createCustomerOrders()
    {
        echo "\ncreateCustomerOrders\n";
        
    }    
    
    
    // split an order
    // trenger vi denn engang??
    public function splitOrder()
    {
        echo "\nsplitCustomerOrders\n";

    }
}