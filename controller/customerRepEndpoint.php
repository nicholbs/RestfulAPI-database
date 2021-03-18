<?php
//require_once 'RESTConstants.php';
require_once 'db/customerRepModel.php';

class customerRepEndpoint
{
    public function handleRequest($uri,$specificQuery,$requestType)
    {
        switch($uri[1])
        {
            case 'orders':
                if($requestType == 'GET')
                return $this ->retrieveOrders();
                break;
            case 'ski':
                if($requestType == 'POST')
                    echo  "post customer-rep";
                break;
        }
    }
    private function retrieveOrders(): array
    {
        return (new customerRepModel())->retrieveOrders();
    }

}