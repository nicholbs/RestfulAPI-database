<?php
//require_once 'RESTConstants.php';
// require_once 'db/OrderModel.php';

class CustomerModel extends DB
{
    // get 4 week product plan
    public function retrieveProdPlan(): array
    {
        $stmt = $this ->db ->query("SELECT * FROM orders");
        $res =$stmt ->fetchAll();

        return $res;
    }

    
    // retrieve an order
    public function retrieveCustomerOrder()
    {
        
    }
    // delete an order
    public function deleteCustomerOrder()
    {
        
    }
    // create an order
    public function postCustomerOrder()
    {

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