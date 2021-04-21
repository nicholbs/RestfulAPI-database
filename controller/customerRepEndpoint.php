<?php
//require_once 'RESTConstants.php';
require_once 'db/customerRepModel.php';

class customerRepEndpoint
{
    public function handleRequest($uri,$specificQuery,$requestType,$requestBodyJson)
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
        }
    }
    private function retrieveOrders(): array
    {
        return (new customerRepModel())->retrieveOrders();
    }
    /**
     * This function determ witch filter is in used
     * @param $
     * @see customerRepModel()) -> getOrdersFilter()
     */
    public  function customerRepFilter(array $specificQuery){
        if (array_key_exists('status',$specificQuery)){
            return(new customerRepModel()) ->getOrdersFilter2($specificQuery);

        }
    }

}