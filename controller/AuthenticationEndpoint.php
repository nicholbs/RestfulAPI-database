<?php
require_once 'db/AuthenticationModel.php';

class AuthenticationEndpoint {

    public function handleEndpoint($token, $dividedUri) : bool{
        switch ($dividedUri[0]){
            case "public":
                return true;
                break;
            case "customer" :
                //do somthing
                break;
            case "shipment" :
                //do somthing2
                break;
            case "storkeeper":
                //do somthing3
                break;
            case "production-plans":
                //do somthing 4
                break;
            case "customer-rep":
            //do somthing 5
                break;
            case "customer-rep":
            //Do somthing 6
                break;
            default: return false;
        }

    }


} // end of AuthenticationModel class