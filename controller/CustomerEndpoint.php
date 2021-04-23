<?php
//require_once 'RESTConstants.php';
// require_once 'db/StorekeeperModel.php';

use Codeception\Application;
use Codeception\Command\Console;

require_once 'db/CustomerModel.php';

class CustomerEndpoint
{
    public function handleRequest(array $uri,$specificQuery,$requestType, $requestBody)
    {

        // print_r($requestBody);
        // Check that content of request contains the values needed
        if (is_array($requestBody)) {

            $arr = array("customer_id", "ski_quantity");
            foreach ($arr as &$value) {
                
                if (!array_key_exists($value, $requestBody)) {
                    $reason = "Request was not processed due to missing the value: ";
                    $reason .= $value;
                    throw new BusinessException(400, $reason);
                } 
            }
        } else {
            throw new BusinessException(400, "Request was not processed due to an apparent client error, for example malformed syntax in body");
        }

        

    if(in_array("plansummary", $uri) && $requestType=="GET")
        return $this->retrievePlan();

        switch($uri[2])
        {
            case 'order':
                if($requestType == 'GET' && is_numeric($uri[1]) && is_numeric($uri[3])) {
                    return $this->getOrder($uri[1], $uri[3]);
                }
                if($requestType == 'DELETE'&& is_numeric($uri[1]) && is_numeric($uri[3])) {
                    return $this->deleteOrder($uri[1], $uri[3]);
                }
                if($requestType == 'POST'&& is_numeric($uri[1]) && is_numeric($uri[3])) {
                    return $this->createOrder($uri[1], $uri[3]);
                }
                default: 
                    throw new BusinessException(400, "Request was not processed due to an apparent client error, for example malformed request syntax");
               

        }
    }

    // get 4 week product plan
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
    private function createOrder($customerId, $orderNr)
    {
        return (new CustomerModel())->postCustomerOrder($customerId, $orderNr);
    }
}

