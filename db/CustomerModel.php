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
        $query = 'SELECT order_view.order_nr, name, order_view.state, buying_price, ROUND(order_view.price, 2) AS total, model, ski_quantity, msrp, subtotal, date_placed 
                  FROM order_view INNER JOIN orders ON orders.order_nr = order_view.order_nr WHERE order_view.order_nr = :order_nr';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":order_nr", $order_nr);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($orders))
            throw new BusinessException(httpErrorConst::notFound, "Record not found");

        $row = array();

        // Prints order
        $row['order_nr']        = $orders[0]['order_nr'];
        $row['name']            = $orders[0]['name'];
        $row['date_placed']     = $orders[0]['date_placed'];
        $row['buying_price']    = $orders[0]['buying_price'];
        $row['state']           = $orders[0]['state'];
        $row['total']           = $orders[0]['total'];

        // Prints suborders
        $row['sub_orders'] = array();
        for($i = 0; $i < count($orders); $i++){
            $this->fillSubOrder($row, $i, $orders[$i]);
        }

        return $row;
    }
    
    /**
    * Retrieve an order and validates content of response
    *
    * @param int $customer_nr id of customer
    * @param int $order_nr id of order
    * @author Nicholas Bodvin Sellevåg
    */ 
    public function retrieveCustomerOrderSince($customer_nr, $since)
    {
        // Prepare and send request to database which retrieves appropriate order
        // SELECT `order_nr`, `price`, `state`, `customer_id`, `date_placed`, `order_aggregate` FROM `orders` WHERE date_placed > 2018-01-01
        $stmt = $this ->db ->prepare('SELECT order_nr, state, date_placed, price, order_aggregate FROM `orders` WHERE `customer_id` = :customerId AND date_placed > :since');
        $stmt->bindValue(':customerId', $customer_nr);
        $stmt->bindValue(':since', $since);
        $stmt->execute();   
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // If request is empty no record was found
        if (empty($res)) {
            throw new BusinessException(httpErrorConst::notFound, "Record was not found");
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
        if (!$this->orderExists($order_nr)) {
            throw new BusinessException(httpErrorConst::notFound, "Record was not found");
        } else {
            $stmt = $this->db->prepare('DELETE FROM orders WHERE order_nr = ?');
            $stmt->execute([$order_nr]);

            if (!$this->orderExists($order_nr)) {
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
        // TODO : Error codes
        
        // Checks validity of request body
        if(!array_key_exists('customer', $requestBody) || !array_key_exists('skis', $requestBody))
            throw new APIException(403, "Incomplete request body");

        $skis = $requestBody['skis']; //For ease of reading
        if(!array_key_exists(0, $skis))
            throw new APIException(403, "No skis in request body");

        // Get customer's buying price
        $stmt = $this->db->prepare('SELECT `buying_price` FROM `customers` WHERE `customer_id` = :customerId');
        $stmt->bindValue(':customerId', $requestBody['customer']);
        $stmt->execute();
        $buying_price = $stmt->fetch(PDO::FETCH_ASSOC)['buying_price'];

        if($buying_price == null)
            throw new BusinessException(403, "Customer does not exist");

        // Creates list of ski types in format (2, 3, ...) for query
        $ski_types = "(" . strval($skis[0]['type']); //Note to self: strval() = toString()
        for($i = 1; $i < count($skis); $i++)
            $ski_types .= ", " . strval($skis[$i]['type']);
        $ski_types .= ")";

        // Get msrp of relevant ski types
        $stmt = $this->db->prepare('SELECT `msrp` FROM `ski_types` WHERE `type_id` IN ' . $ski_types);
        $stmt->execute();
        $msrp = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(count($msrp) < count($skis))
            throw new APIException(403, "One or more ski types does not exist");

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
        $query = 'SELECT type_id, ski_quantity, msrp, subtotal FROM suborder_view WHERE order_nr = :order_nr';
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

    //Helper functions
    private function fillSubOrder(&$target_array, $target_index, $source_array)
    {
        $target_array['sub_orders'][$target_index]['model']     = $source_array['model'];
        $target_array['sub_orders'][$target_index]['msrp']      = $source_array['msrp'];
        $target_array['sub_orders'][$target_index]['quantity']  = $source_array['ski_quantity'];
        $target_array['sub_orders'][$target_index]['subtotal']  = $source_array['subtotal'];
    }

    private function orderExists($order_nr){
        $query = 'SELECT COUNT(1) FROM orders WHERE order_nr = :order_nr';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":order_nr", $order_nr);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_NUM)[0];
    }

    //TODO
    public function splitOrder()
    {
        echo "\nsplitCustomerOrders\n";
    }
}