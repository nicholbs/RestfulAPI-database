<?php
//require_once 'RESTConstants.php';
require_once 'db/publicModel.php';

class publicEndpoint
{
    public function handleRequest($uri,$specificQuery,$requestType,$requestBodyJson)
    {
        switch($uri[1])
        {
            case 'skis':
                if($requestType == 'GET')
                    return $this ->retrieveOrders();
                    echo "skis i public endpint";
                break;
        }
    }
    private function retrieveOrders(): array
    {
        return (new publicModel())->getAllSkiTypes();
    }

}