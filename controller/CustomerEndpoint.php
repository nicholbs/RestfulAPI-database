<?php
//require_once 'RESTConstants.php';
// require_once 'db/StorekeeperModel.php';

use Codeception\Command\Console;

require_once 'db/CustomerModel.php';

class CustomerEndpoint
{
    public function handleRequest(array $uri,$specificQuery,$requestType)
    {

    if(in_array("plansummary", $uri) && $requestType=="GET")
        return $this->retrievePlan();

        // echo json_encode($specificQuery);
        // echo json_encode($uri);
        // echo("Dette er CustomerEndpoint med uri: " + strval($uri));
        // echo("Dette er CustomerEndpoint med query: " + strval($specificQuery));
        // echo("Dette er CustomerEndpoint med req: " + strval($requestType));
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

