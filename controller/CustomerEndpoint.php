<?php

use Codeception\Application;
use Codeception\Command\Console;

require_once 'db/CustomerModel.php';

class CustomerEndpoint
{
    public function handleRequest(array $uri, $specificQuery, $requestType, array $requestBody)
    {

        $lengde = count($uri);
        if ($lengde == 3 && $uri[1] == "plansummary") {
            return $this->retrievePlan();
        }

        switch($uri[2])
        {
            case 'order':
                if($requestType == 'GET') {
                    $this->validateURI($uri);
                    // $arr = array("customer_id", "ski_quantity");
                    // $this->validateBody($requestBody, $arr);
                    return $this->getOrder($uri[1], $uri[3]);
                }
                else if($requestType == 'DELETE') {
                    $this->validateURI($uri);
                    return $this->deleteOrder($uri[1], $uri[3]);
                }
                else if($requestType == 'POST') {
                    return $this->createOrder($requestBody);
                }
                break;
                case 'orderSince':
                    if(!$requestType == 'GET') {
                        throw new BusinessException(httpErrorConst::badRequest, "users can only retrieve orders with filter");
                    }
                    $antQueryKeyelements= count($specificQuery);
                    if (array_key_exists('since',$specificQuery) && $antQueryKeyelements ==1 ){
                        return $this->getOrderSince($uri[1], $specificQuery['since']);
                    } else {
                        throw new BusinessException(httpErrorConst::badRequest, "request is missing 'since' filter");
                    }
                break;
                default: 
                throw new BusinessException(404, "The URL given does not match the business logic, check endpoint documentation");
        }
   
}

    private function validateURI(array $uri) {
        if (!is_numeric($uri[1])) {
            throw new BusinessException(400, "Endpoint expected customer id to be a number");
        }; 
        if (!is_numeric($uri[3])) {
            throw new BusinessException(400, "Endpoint expected customer id to be a number");
        };
    }
    private function validateBody($requestBody, array $arr) {
       
        foreach ($arr as &$value) {
            
            if (!array_key_exists($value, $requestBody)) {
                $reason = "Request was not processed due to missing the value: ";
                $reason .= $value;
                throw new BusinessException(400, $reason);
            } 
        }
    }

    // retrieve plans up to four weeks ago
    private function retrievePlan(): array
    {
        return (new CustomerModel())->retrieveProdPlan();
        
    }

    // retrieve an order with since filter
    private function getOrderSince($customerId, $since): array
    {
        return (new CustomerModel())->retrieveCustomerOrderSince($customerId, $since);
        
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

