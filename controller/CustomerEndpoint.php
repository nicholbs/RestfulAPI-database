<?php
//require_once 'RESTConstants.php';
// require_once 'db/StorekeeperModel.php';
require_once 'db/CustomerModel.php';

class CustomerEndpoint
{
    public function handleRequest($uri,$specificQuery,$requestType)
    {

    if($uri[1] == "plansummary")
        return $this->retrievePlan();

        // print("Dette er CustomerEndpoint med uri: " + $uri);
        switch($uri[2])
        {
            // case 'plansummary':
            //     if($requestType == 'GET')
            //         echo("\n CustomerEndpoint plansummary");
            //         return $this->retrievePlan();
            //     break;
            case 'order':
                if($requestType == 'GET')
                    return $this->getOrder($uri[1], $uri[3]);
                break;
            case 'order':
                if($requestType == 'DELETE')
                    return $this->deleteOrder($uri[1], $uri[3]);
                break;
            case 'order':
                if($requestType == 'POST')
                return $this->createOrder($uri[1], $uri[3]);
                break;
            case 'orders':
                if($requestType == 'GET')
                return $this->createSki();
                break;
            case 'orders':
                if($requestType == 'POST')
                return $this->createSki();
                break;
            case 'splitorder':
                if($requestType == 'POST')
                    $this->createSki();
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
    private function createOrder($customerId, $orderNr)
    {
        return (new CustomerModel())->postCustomerOrder($customerId, $orderNr);
    }



}