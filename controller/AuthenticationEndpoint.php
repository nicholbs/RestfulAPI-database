<?php
//require_once 'db/AuthenticationModel.php';
require_once '../db/AuthenticationModel.php';

class AuthenticationEndpoint {

    public function handleEndpoint(string $token, array $dividedUri) : bool{
        $endpoint=$dividedUri[0];
        print($endpoint);
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
            case "storekeeper":
                print("\nVi er i storkeeper sjekker");
                return(new AuthenticationModel())->employeeAuth($endpoint,$token);
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
            default: print ("\nDette gikk galt");
        }

    }


} // end of AuthenticationModel class

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
