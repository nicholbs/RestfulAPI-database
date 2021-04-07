<?php
//require_once 'RESTConstants.php';
require_once 'db/publicModel.php';


class publicEndpoint
{
    /**
     * This function handles the publicEndpoit requests
     * @param array $uri    - Array divded into / in this case skis
     * @param array $specificQuery  -   An array with the content afther ? mark
     * @param string $requestType   -   get/post/put etc..
     * @param array $requestBodyJson    -   If there is a json body we recive tath in an array.
     * @return array    -   returning the results
     * @see this -> skifilter()
     */
    public function handleRequest(array $uri,array $specificQuery, string $requestType,array $requestBodyJson)
    {
        switch($uri[1])
        {
            case 'skis':
                if($requestType == 'GET')
                    return $this ->skifilter($specificQuery);
                break;
            case 'test':
                echo ("\n Vi er i test på skifilter");
               return $this ->skifilter($specificQuery);
                break;
        }
    }
    private function retrieveOrders(): array
    {
        return (new publicModel())->getAllSkiTypes();
    }

    /**
     * This function determ witch filter is used
     * @param $specificQuery    - Its a array consist of the keys and valuses retrived from the query
     * @see publicModel()->getAllFilters()
     * @see publicModel()-> getAFilter()
     */
    public  function skifilter(array $specificQuery){
        $antQueryKeyelements= count($specificQuery); //get the count on all key arguments passed to the filter

        //If both the model and grip filter is used
        if (array_key_exists('model',$specificQuery) && array_key_exists('grip',$specificQuery)){
            //return(new publicModel()) ->getModelFilter($specificQuery,"grip");
            return(new publicModel())->getAllFilter($specificQuery);
        }
        //If only the model filter is in use
        elseif (array_key_exists('model',$specificQuery) && $antQueryKeyelements ==1 ){

            //return(new publicModel())->getSkiTypesModelFilter($specificQuery);
           return (new publicModel()) ->getAFilter($specificQuery,'model');
        }
        //If only the grip filter is in use
        elseif (array_key_exists('grip',$specificQuery) && $antQueryKeyelements ==1){

            //return(new publicModel())->getGripModelFilter($specificQuery);
            return (new publicModel()) ->getAFilter($specificQuery,'grip');
        }
        //If the filter dosent exist
        else{
            return "filter not found";
        }

    }

}