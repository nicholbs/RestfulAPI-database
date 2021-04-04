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

    /**
     * This function chek if the usertype is allowed to enter the specific API. See the comment under //Add more departments on who
     * for adding ore removing departmens thath shuld ore not be granted to access the specific URI.
     *
     * @param string $usertype - Type of user eg customer ore storekeeper
     * @param array $dividedUri - The URI to the API request
     * @return bool - Retuns true if the specific user/usertype is allowed to access the specific API
     */
    public function aclList(string $usertype, array $dividedUri) :bool{
        //orders endpoint
        $orders=array(); //represent the ACL of a orders endpoint
        $customer=array(); //represent the ACL of the customer endpoint
        $shipment=array(); //Represents the ACL of the shipment endpoint
        $storekeeper =array(); // represent the ACL of the storekeeper endpoint
        $productionPlans=array(); // represents the acl for production-plans endpoint
        $public=array(); //tror vi ikke trenger denne
        $customerRep=array(); //represens the ACL of the customer-rep endpoint


        //Add more departments based on who shuld have access to the given api
        $orders[0]="customer";
        $customer[]="customer";
        $shipment[]="storekeeper";
        $storekeeper[]="storekeeper";
        $productionPlans[]="production-plans";


        //Cheking the request baset on the api uri and the ACL list and returns true if match

        if(array_search($usertype,$orders) !== false && $dividedUri[0] == "orders"){
            echo("key exist");
            return true;
        }
        elseif (array_search($usertype,$customer) !== false && $dividedUri[0] == "customer"){
            return true;
        }
        elseif (array_search($usertype,$shipment) !== false && $dividedUri[0] == "shipment"){
            return true;
        }
        elseif (array_search($usertype,$storekeeper) !== false && $dividedUri[0] == "storekeeper"){
            echo("storekeeper key exist");
            return true;
        }
        elseif (array_search($usertype,$productionPlans) !== false && $dividedUri[0] == "production-plans"){
            return true;
        }
        elseif (array_search($usertype,$customerRep) !== false && $dividedUri[0] == "customer-rep"){
            return true;
        }
        else {
            return false; //If nothing matches the ACL we return false
        }

    }

    /**
     * This function handles the authentication based on the endpoint uri
     * @param string $token - The token from the user/frontend
     * @param array $dividedUri - The URI divided in an array
     * @return bool - return ture ore false if the user is allowed to access the certan api.
     *
     * @see AuthenticationModel()->findUsertype()
     * @see this-> aclList()
     */
    public function handleEndpont2(string $token, array $dividedUri) :bool {

        //Find what department ore if the user is a customer based on the token:
        $usertype = (new AuthenticationModel())->findUsertype($token);
        print("\n usertype: " . $usertype);

        //Send respons if the user is allowed to procede with the request
        if($dividedUri[0] =="public"){
            return true;
        }
        elseif ($this->aclList($usertype,$dividedUri)){
            return true;
        }
        else{
            return false;
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

/**
$a ="storekeeper";
$b ="e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855";
$c= "2927ebdf56c20cbb90fbd85cac5be30d60e3dfb9f9c9eda869d0fdce36043a85";
$d="";

$tabell = array();
//$tabell[0]="storekeeper";
$tabell[0]="public";


$test = new  AuthenticationEndpoint();
//$test ->aclList($tabell,$c);
//$test ->handleEndpont2($c,$tabell);
//$test ->aclList($a,$tabell);

//$test ->handleEndpont2($c,$tabell);


if($test ->handleEndpont2($d,$tabell)){
    print("\nAlt ok du får fortsette! \n");
}
else{
    print("\nNei du får ikke fortsette \n");
}
**/