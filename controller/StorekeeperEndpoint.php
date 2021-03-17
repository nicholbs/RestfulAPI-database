<?php
//require_once 'RESTConstants.php';
require_once 'db/OrderModel.php';

class DealersEndpoint
{
    public function handleRequest($uri,$requestType)
    {
        switch($uri[1])
        {
            case 'orders':
                if($requestType == 'GET')
                    return $this->retrieveOrders();
                break;
            case 'ski':
                if($requestType == 'POST')
                    $this->createSki();
                break;
        }
    }

    private function retrieveOrders(): array
    {
        return (new OrderModel())->getCollection();
    }

    private function createSki()
    {

    }
}