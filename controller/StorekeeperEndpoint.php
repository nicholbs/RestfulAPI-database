<?php
//require_once 'RESTConstants.php';
require_once 'db/StorekeeperModel.php';

class StorekeeperEndpoint
{
    public function handleRequest($uri,$specificQuery,$requestType,$requestBodyJson)
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
            case 'transitionrecord':
                if($requestType == 'PUT'){
                    return (new StorekeeperModel()) ->transitionRecord($requestBodyJson);
                }
                break;
        }
    }

    private function retrieveOrders(): array
    {
        return (new StorekeeperModel())->retrieveOrders();
    }

    private function createSki()
    {

    }
}