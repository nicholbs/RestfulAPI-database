<?php
//require_once 'RESTConstants.php';
require_once 'db/customerRepModel.php';

class customerRepEndpoint
{
    /**
     * This function handles the publicEndpoit requests
     * @param array $uri    - Array divded into / in this case skis
     * @param array $specificQuery  -   An array with the content afther ? mark
     * @param string $requestType   -   get/post/put etc..
     * @param array $requestBodyJson    -   If there is a json body we recive tath in an array.
     * @return array    -   returning the results
     * @see this -> customerRepFilter()
     */
    public function handleRequest($uri,$specificQuery,$requestType,$requestBodyJson, $token)
    {
        switch($uri[1])
        {
            case 'orders':
                if($requestType == 'GET')
                //return $this ->retrieveOrders();
                    return $this->customerRepFilter($specificQuery);
                break;
            case 'ski':
                if($requestType == 'POST')
                    echo  "post customer-rep";
                break;
            case 'test':
                return (new customerRepModel()) ->changeOrderState($specificQuery, $requestBodyJson, $token);
        }
    }
    private function retrieveOrders(): array
    {
        return (new customerRepModel())->retrieveOrders();
    }
    /**
     * This function determ witch filter is in used
     * @param $specificQuery - Array with qquery sendt frontebd
     * @see customerRepModel()) -> getOrdersFilter()
     * $return an array with a all skis of a folters
     */
    public  function customerRepFilter(array $specificQuery){
        if (array_key_exists('status',$specificQuery)){
            return(new customerRepModel()) ->getOrdersFilter($specificQuery);

        }
    }

}