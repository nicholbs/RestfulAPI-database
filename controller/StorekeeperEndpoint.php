<?php
//require_once 'RESTConstants.php';
require_once 'db/StorekeeperModel.php';
require_once 'constants.php';

class StorekeeperEndpoint
{
    public function handleRequest($uri,$specificQuery,$requestType,$requestBody)
    {
        switch($uri[1])
        {
            case 'orders':
                if($requestType == 'GET')
                    return $this->retrieveOrders();
                break;
            case 'ski':
                if($requestType == 'POST')
                    return $this->createSki($requestBody);
                break;
            case 'transitionrecord':
                if($requestType == 'PUT'){
                    return (new StorekeeperModel()) ->transitionRecord($requestBody);
                }
                break;
        }
    }

    private function retrieveOrders(): array
    {
        return (new StorekeeperModel())->retrieveOrders();
    }

    private function createSki($requestBody)
    {
        return (new StorekeeperModel())->createSki($requestBody);
    }
}