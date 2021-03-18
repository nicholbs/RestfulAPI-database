<?php
//require_once 'RESTConstants.php';
// require_once 'db/OrderModel.php';

class CustomerModel extends DB
{
    // get 4 week product plan
    public function retrieveProdPlan(): array
    {
        echo "retrieveProdPlan";
        $stmt = $this ->db ->query("SELECT * FROM orders");
        $res =$stmt ->fetchAll();
        
        return $res;
    }
    
    
    // retrieve an order
    public function retrieveCustomerOrder($customer_nr, $order_nr): array
    {
        echo "\nretrieveCustomerOrder\n";
        echo $customer_nr;
        echo "\n";
        echo $order_nr;
        echo "\n";
        // 
        // $stmt = $this ->db ->prepare('SELECT * FROM `orders` WHERE `customer_id` = :customerId');
        $stmt = $this ->db ->prepare('SELECT * FROM `orders` WHERE `customer_id` = :customerId AND `order_nr` = :orderNr');
        $stmt->bindValue(':customerId', $customer_nr);
        $stmt->bindValue(':orderNr', $order_nr);
        $stmt->execute();
        
        $res =$stmt->fetchAll();
        
        return $res;
    }
    // delete an order
    public function deleteCustomerOrder($customer_nr, $order_nr)
    {
        // echo "\ndeleteCustomerOrder\n";
        // $sql = "DELETE FROM `orders` WHERE customer_id = {$customer_nr}";
        // if ($this->db->query($sql) === TRUE) {
        //     echo "Record deleted successfully";
        //     return "Success";
        // } else {
        //     echo "Error deleting record";
        //     return "Error";
        //   }
    }
    // create an order
    public function postCustomerOrder($customer_nr, $order_nr)
    {
        echo "\npostCustomerOrder\n";

    }

    // get specific orders
    public function getCustomerOrders()
    {
        
    }
    // create orders
    public function createCustomerOrders()
    {
        
    }
    
    
    // split an order
    public function splitOrder()
    {

    }
}