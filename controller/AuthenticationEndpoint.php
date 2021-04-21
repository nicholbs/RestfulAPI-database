<?php
require_once 'db/AuthenticationModel.php';
//require_once '../db/AuthenticationModel.php';

class AuthenticationEndpoint {
    /**
     * This function handles the authentication based on the endpoint uri
     * @param string $token - The token from the user/frontend
     * @param array $dividedUri - The URI divided in an array
     * @return bool - return ture ore false if the user is allowed to access the certan api.
     *
     * @see AuthenticationModel()->findUsertype()
     * @see this-> aclList()
     */
    public function handleEndpoint(string $token, array $dividedUri) :bool {

        //Find what department ore if the user is a customer based on the token:
        $usertype = (new AuthenticationModel())->findUsertype($token);
        //print("\n usertype: " . $usertype);

        //Send respons if the user is allowed to procede with the request
        if($dividedUri[0] =="public"){ //Everyone shuld be allowed to access the public API.
            return true;
        }
        elseif ($this->aclList($usertype,$dividedUri)){
            return true;
        }
        else{
            return false;
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


        //Add more departments based on who shuld have access to the given api endpoint
        $orders[0]="customer";
        $customer[]="customer";
        $shipment[]="storekeeper";
        $storekeeper[]="storekeeper";
        $productionPlans[]="production-plans";
        $customerRep[]="customer-rep";


        //Cheking the request baset on the api uri and the ACL list and returns true if the api and user match allow

        if(array_search($usertype,$orders) !== false && $dividedUri[0] == "orders"){
           // echo("key exist");
            return true;
        }
        elseif (array_search($usertype,$customer) !== false && $dividedUri[0] == "customer"){
            return true;
        }
        elseif (array_search($usertype,$shipment) !== false && $dividedUri[0] == "shipment"){
            return true;
        }
        elseif (array_search($usertype,$storekeeper) !== false && $dividedUri[0] == "storekeeper"){
           // echo("storekeeper key exist");
            return true;
        }
        elseif (array_search($usertype,$productionPlans) !== false && $dividedUri[0] == "production-plans"){
            return true;
        }
        elseif (array_search($usertype,$customerRep) !== false && $dividedUri[0] == "customer-rep"){
            return true;
        }
        else {
            throw new APIException(404, "The URL given does not match any endpoints");
            // return false; //If nothing matches the ACL we return false
        }

    }



} // end of AuthenticationModel class
