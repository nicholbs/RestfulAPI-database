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
                    $this->getOrder();
                break;
            case 'order':
                if($requestType == 'DELETE')
                    $this->deleteOrder();
                break;
            case 'order':
                if($requestType == 'POST')
                    $this->createOrder();
                break;
            case 'orders':
                if($requestType == 'GET')
                    $this->createSki();
                break;
            case 'orders':
                if($requestType == 'POST')
                    $this->createSki();
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
    private function getOrder()
    {
        return (new CustomerModel())->retrieveCustomerOrder();
    }
    // delete an order
    private function deleteOrder()
    {
        return (new CustomerModel())->deleteCustomerOrder();
    }
    // create an order
    private function createOrder()
    {
        return (new CustomerModel())->postCustomerOrder();
    }



}