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
    public function postCustomerOrder($requestBody)
    {
        // Get customer's buying price
        $stmt = $this->db->prepare('SELECT `buying_price` FROM `customers` WHERE `customer_id` = :customerId');
        $stmt->bindValue(':customerId', $requestBody['customer']);
        $stmt->execute();
        $buying_price = $stmt->fetch()['buying_price'];

        $skis = $requestBody['skis']; //For ease of reading

        // Creates list of ski types in format (2, 3, ...) for query
        $ski_types = "(" . strval($skis[0]['type']); //Note to self: strval() = toString()
        for($i = 1; $i < count($skis); $i++)
            $ski_types .= ", " . strval($skis[$i]['type']);
        $ski_types .= ")";

        // Get msrp of relevant ski types
        $stmt = $this->db->prepare('SELECT `msrp` FROM `ski_types` WHERE `type_id` IN ' . $ski_types);
        $stmt->execute();
        $msrp = $stmt->fetchColumn('msrp');

        // Calculate total price of order
        $price = 0;
        for($i = 0; $i < count($skis); $i++)
            $price += $msrp[$i]['msrp'] * $skis[$i]['quantity'];
        $price *= $buying_price;

        return $msrp;

        //      TODO 
        //       - Start transaction
        //       - Insert order
        //       - Insert sub orders (loop)
        //       - Commit transaction
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