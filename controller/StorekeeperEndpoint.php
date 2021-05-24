<?php
//require_once 'RESTConstants.php';
require_once 'BusinessException.php';
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
            else
                throw new BusinessException(400, "Invalid request method");
            break;
            
        case 'ski':
            if($requestType == 'POST')
                return $this->createSki($requestBody);
            else
                throw new BusinessException(400, "Invalid request method");
            break;

        case 'transitionrecord':
            if($requestType == 'PUT')
                return (new StorekeeperModel())->transitionRecord($requestBody);
            else
                throw new BusinessException(400, "Invalid request method");
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