<?php
//require_once 'RESTConstants.php';
// require_once 'db/OrderModel.php';

class CustomerModel extends DB
{
    // get 4 week product plan
    public function retrieveProdPlan(): array
    {
        // echo "retrieveProdPlan";
        $stmt = $this ->db ->query('SELECT * FROM `production_plans` WHERE day >= DATE_ADD(
            DATE_ADD(CURDATE(), INTERVAL - WEEKDAY(CURDATE()) DAY),
            INTERVAL - 4 WEEK)');
        $res =$stmt ->fetchAll();
        
        return $res;
    }
    
    
    // retrieve an order
    public function retrieveCustomerOrder($customer_nr, $order_nr)
    {
        // // echo "\nretrieveCustomerOrder\n";
        // // echo $customer_nr;
        // // echo "\n";
        // // echo $order_nr;
        // // echo "\n";
        // 
        // $stmt = $this ->db ->prepare('SELECT * FROM `orders` WHERE `customer_id` = :customerId');
        $stmt = $this ->db ->prepare('SELECT * FROM `orders` WHERE `customer_id` = :customerId AND `order_nr` = :orderNr');
        $stmt->bindValue(':customerId', $customer_nr);
        $stmt->bindValue(':orderNr', $order_nr);
        $stmt->execute();
        
        $res = $stmt->fetchAll();
        return $res;
    }
    // delete an order
    public function deleteCustomerOrder($customer_nr, $order_nr)
    {
        // echo "\ndeleteCustomerOrder\n";
        $stmt = $this->db->prepare('DELETE FROM orders WHERE order_nr = ?');
        $stmt->execute([$order_nr]);
        // $res = $stmt->fetch();
        return "Success";
    }
    // create an order
    public function postCustomerOrder($customer_id)
    {
        $stmt = $this->db->prepare('SELECT `buying_price` FROM `customers` WHERE `customer_id` = :customerId');
        $stmt->bindValue(':customerID', $customer_id);
        $stmt->execute();
        $buyingPrice = $stmt->fetch();

        

        //$stmt = $this->db->prepare('INSERT INTO orders(customer_id, ski_type, price) VALUES (?,2,70)');
        // $stmt->execute([$order_nr, $customer_nr]);
        //$stmt->execute([$customer_nr]);

        // INSERT INTO orders(customer_id, ski_type, price) VALUES (4,2,69)
        // $res = $stmt->fetch();
        //return "Success";
        
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