<?php
//require_once 'RESTConstants.php';
// require_once 'db/OrderModel.php';

class TransporterModel extends DB
{
    // retrieve an order
    public function updateShipment($order_nr, $token, $requestBody)
    {
        // check if order exists
        $res = $this->retrieveCustomerOrder($order_nr);
        
        if ($res[0]["state"] == "skis-available") {
            $stmt = $this->db->prepare('UPDATE `orders` SET `state`="shipped" WHERE `order_nr` = :orderNr');
            $stmt->bindValue(':orderNr', $order_nr);  
            $stmt->execute();

            $isShipped = $this->retrieveCustomerOrder($order_nr);
            print_r($isShipped);
            if ($isShipped[0]["state"]== "shipped") {
                $res = $this->updateOrderHistory($order_nr);
                $customer_id = $isShipped[0]["customer_id"];
                $res = $this->insertShipment($order_nr, $requestBody, $customer_id);
                
            } else {
                throw new BusinessException(httpErrorConst::serverError, "Order was not properly updated");
            }
        } else {
            $reason = "The order given does not have status as 'skis-available', instead it is:";
            $reason .= $res[0]["state"];
            throw new BusinessException(httpErrorConst::badRequest, $reason);
        }
        // $stmt->bindValue(':orderNr', $order_nr);  
        // $stmt->execute();   
        // $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // // $res =$stmt->fetchAll();
        // return "Success";
    }
 
    /**
    * Retrieve an order and validates content of response
    *
    * @param int $customer_nr id of customer
    * @param int $order_nr id of order
    * @author Nicholas Bodvin Sellevåg
    */ 
    public function retrieveCustomerOrder($order_nr)
    {
        // Prepare and send request to database which retrieves appropriate order
        $stmt = $this ->db ->prepare('SELECT order_nr, state, date_placed, price, order_aggregate, customer_id FROM `orders` WHERE `order_nr` = :orderNr');
        $stmt->bindValue(':orderNr', $order_nr);  
        $stmt->execute();   
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // If request is empty no record was found
        if (empty($res)) {
            throw new BusinessException(httpErrorConst::badRequest, "Order requested did not exist");
        } 
        // If request is not empty check that response contains all attributes expected from database
        else {
            $arr = array("order_nr", "state", "date_placed", "price", "customer_id");
                $this->validateRespone($arr, $res);
                return $res;
        }
        
    }
    /**
    * Retrieve an order and validates content of response
    *
    * @param int $customer_nr id of customer
    * @param int $order_nr id of order
    * @author Nicholas Bodvin Sellevåg
    */ 
    public function updateOrderHistory($order_nr)
    {
        // Prepare and send request to database which retrieves appropriate order
        $stmt = $this ->db ->prepare('SELECT customer_rep FROM `order_history` WHERE order_nr = :orderNr');
        $stmt->bindValue(':orderNr', $order_nr);  
        $stmt->execute();   
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // If request is empty no record was found
        if (empty($res)) {
            throw new BusinessException(httpErrorConst::badRequest, "Order history requested did not exist");
        } 
        // If request is not empty check that response contains all attributes expected from database
        else {
            $arr = array("customer_rep");
            $this->validateRespone($arr, $res);
            $customerRep_nr = $res[0]['customer_rep'];
            $stmt = $this ->db ->prepare('INSERT INTO `order_history`(`order_nr`, `state`, `customer_rep`) VALUES (:orderNr,"shipped",:customerRep)');
            $stmt->bindValue(':orderNr', $order_nr);  
            $stmt->bindValue(':customerRep', $customerRep_nr);  
            $stmt->execute();   
            
            
            $stmt = $this ->db ->prepare('SELECT `order_nr`, `state`, `customer_rep`, `changed_date` FROM `order_history` WHERE order_nr = :orderNr AND state = "shipped"');
            $stmt->bindValue(':orderNr', $order_nr);  
            $stmt->execute();   
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $arr = array("order_nr", "customer_rep", "state", "changed_date");
                $this->validateRespone($arr, $res);
            return $res;
        }
        
    }
    /**
    * Retrieve an order and validates content of response
    *
    * @param int $customer_nr id of customer
    * @param int $order_nr id of order
    * @author Nicholas Bodvin Sellevåg
    */ 
    public function insertShipment($order_nr, $requestBody, $customer_nr)
    {
        echo "insertShipment";
        $address=$requestBody["shipping_address"];
        $pickup=$requestBody["scheduled_pickup"];
        $transporter=$requestBody["transporter"];
        $driverId=$requestBody["driver_id"];
        print_r($requestBody);
        
        echo "\n\n";
        echo $address;
        echo $pickup;
        echo $transporter;
        echo $driverId;
        echo $customer_nr;
        echo $order_nr;

        // // // // // // ser ut som problemet kommer av datetime variabelen
        // Prepare and send request to database which retrieves appropriate order
        $stmt = $this ->db ->prepare('INSERT INTO `shipments`(`customer_id`, `shipping_address`, `scheduled_pickup`, `state`, `order_nr`, `transporter`, `driver_id`) VALUES (:customerId, :shipping_address, :pickup, "shipped", :orderNr, :transporter, :driverId)');
        $stmt->bindValue(':customerId', $customer_nr);
        $stmt->bindValue(':shipping_address', $address);
        $stmt->bindValue(':pickup', $pickup);
        $stmt->bindValue(':orderNr', $order_nr);
        $stmt->bindValue(':transporter', $transporter);
        $stmt->bindValue(':driverId', $driverId);
        $stmt->execute();   
        echo "etter insert";


        $stmt = $this ->db ->prepare('SELECT `shipment_nr`, `customer_id`, `shipping_address`, `scheduled_pickup`, `state`, `order_nr`, `transporter`, `driver_id` FROM `shipments` WHERE order_nr = :orderNr');
        $stmt->bindValue(':orderNr', $order_nr);  
        $stmt->execute();   
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $arr = array("shipment_nr", "customer_id", "shipping_address", "scheduled_pickup", "state", "order_nr", "transporter", "driver_id");
        $this->validateRespone($arr, $res);
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
    
}