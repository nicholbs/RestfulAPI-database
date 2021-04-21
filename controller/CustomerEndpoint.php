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

        /*
        print_r($requestBody);
        $arr = array("customer_id", "ski_quantity");
        foreach ($arr as &$value) {
            
            if (!array_key_exists($value, $requestBody)) {
                $reason = "Request was not processed due to missing the value: ";
                $reason .= $value;
                throw new BusinessException(400, $reason);
            } 
        }*/

        switch($uri[2])
        {
            case 'order':
                if($requestType == 'GET' && is_numeric($uri[1]) && is_numeric($uri[3])) {
                    return $this->getOrder($uri[1], $uri[3]);
                }
                else if($requestType == 'DELETE') {
                    return $this->deleteOrder($uri[1], $uri[3]);
                }
                else if($requestType == 'POST') {
                    return $this->createOrder($requestBody);
                }
                break;
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
    private function createOrder(array $requestBody)
    {
        return (new CustomerModel())->postCustomerOrder($requestBody);
    }
}

