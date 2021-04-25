<?php
//require_once 'RESTConstants.php';
// require_once 'db/StorekeeperModel.php';

use Codeception\Application;
use Codeception\Command\Console;

require_once 'db/CustomerModel.php';

class CustomerEndpoint
{
    public function handleRequest(array $uri, $specificQuery, $requestType, array $requestBody)
    {

        // print_r($uri);  
        $lengde = count($uri);
        if ($lengde == 3 && $uri[1] == "plansummary") {
            return $this->retrievePlan();
        }

        // print_r($requestBody);
        
       
    

        switch($uri[2])
        {
            case 'order':
                if($requestType == 'GET') {
                    $this->validateURI($uri);
                    $this->validateBody($requestBody);
                    return $this->getOrder($uri[1], $uri[3]);
                }
                else if($requestType == 'DELETE') {
                    $this->validateURI($uri);
                    $this->validateBody($requestBody);
                    return $this->deleteOrder($uri[1], $uri[3]);
                }
                else if($requestType == 'POST') {
                    return $this->createOrder($requestBody);
                }
                default: 
                throw new BusinessException(404, "The URL given does not match the business logic, check endpoint documentation");
        }
    

    // // get 4 week product plan
    // private function retrievePlan(): array
    // {
    //     return (new CustomerModel())->retrieveProdPlan();
        
    // }
    // get 4 week product plan
   
}

    private function validateURI(array $uri) {
        if (!is_numeric($uri[1])) {
            throw new BusinessException(400, "Endpoint expected customer id to be a number");
        }; 
        if (!is_numeric($uri[3])) {
            throw new BusinessException(400, "Endpoint expected customer id to be a number");
        };
    }
    private function validateBody($requestBody) {
        $arr = array("customer_id", "ski_quantity");
        foreach ($arr as &$value) {
            
            if (!array_key_exists($value, $requestBody)) {
                $reason = "Request was not processed due to missing the value: ";
                $reason .= $value;
                throw new BusinessException(400, $reason);
            } 
        }
    }

    private function retrievePlan(): array
    {
        return (new CustomerModel())->retrieveProdPlan();
        
    }

    // retrieve an order
    private function getOrder($customerId, $orderNr)
    {
        return (new CustomerModel())->retrieveCustomerOrder($customerId, $orderNr);
    }
    // delete an order
    private function deleteOrder($customerId, $orderNr)
    {
        return (new CustomerModel())->deleteCustomerOrder($customerId, $orderNr);
    }
    // create an order
    private function createOrder(array $requestBody)
    {
        return (new CustomerModel())->postCustomerOrder($requestBody);
    }
}

