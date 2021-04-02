<?php
require_once 'db/AuthenticationModel.php';
//require_once '../db/AuthenticationModel.php';

class AuthenticationEndpoint {

    /**
     * This function handles the authentication based on the endpoint uri
     * @param string $token - string with the token, if the token not set The string contain notAuthenticated
     * @param array $dividedUri - Arry with the URI
     * @return bool - return true ore false based om
     * @see AuthenticationModel() -> emplyeeAtuh() chek if the employee is allowed to ender the endpoint based on the token AND the department
     * @see AuthenticationModel() -> costomerAuth()  chek if the customer is allowed to ender the endpoint based on a valid token
     */
    public function handleEndpoint(string $token, array $dividedUri) : bool{
        $endpoint=$dividedUri[0]; //Extracting the endpoint

        //If the user don't provide a token we reset the requesturi to public, result will be not allowed if the user is accesing somthing else.
        if($token == "notAuthenticated"){
            $dividedUri[0] ="public";
        }
        print($endpoint);
        switch ($dividedUri[0]){
            case "public":
                return true;
                break;
            case "customer" :
                return(new AuthenticationModel())->employeeAuth($endpoint,$token);
                break;
            case "shipment" :
                return(new AuthenticationModel())->employeeAuth($endpoint,$token);
                break;
            case "storekeeper":
                //print("\nVi er i storkeeper sjekker");
                return(new AuthenticationModel())->employeeAuth($endpoint,$token);
                break;
            case "production-plans":
                return(new AuthenticationModel())->employeeAuth($endpoint,$token);
                break;
            case "customer-rep":
                return(new AuthenticationModel())->employeeAuth($endpoint,$token);
                break;
            case "customer-rep":
                return(new AuthenticationModel())->employeeAuth($endpoint,$token);
                break;
            default: return false;
        }

    }


} // end of AuthenticationModel class
/**
$a ="storekeeper";
$b ="e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855";

$tabell = array();
$tabell[0]="storekeeper";

$test=new AuthenticationEndpoint();

if($test -> handleEndpoint($b,$tabell)){
    print("\n Storkeeper ok");
}
else{
    print("\nStorkeeper ikke ok");
}
**/