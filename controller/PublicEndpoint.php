<?php
//require_once 'RESTConstants.php';
require_once 'db/publicModel.php';

class publicEndpoint
{
    public function handleRequest($uri,$specificQuery,$requestType,$requestBodyJson)
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
     */
    public  function skifilter($specificQuery){
        $antQueryKeyelements= count($specificQuery); //get the count on all key arguments passed to the filter

        //If both the model and grip filter is used
        if (array_key_exists('model',$specificQuery) && array_key_exists('grip',$specificQuery)){
            echo ("\nmodel og grip filter finnes \n");
        }
        //If only the model filter is in use
        elseif (array_key_exists('model',$specificQuery) && $antQueryKeyelements ==1 ){

            return(new publicModel())->getSkiTypesModelFilter($specificQuery);
        }
        //If only the grip filter is in use
        elseif (array_key_exists('grip',$specificQuery) && $antQueryKeyelements ==1){
            echo("\nvi er i grip filter");
        }
        //If the filter dosent exist
        else{
            echo ("\nFilter not found\n");
        }

    }

}