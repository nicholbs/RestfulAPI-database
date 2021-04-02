<?php
require_once 'db/publicModel.php';
// require_once 'db/OrderModel.php';
require_once 'db/StorekeeperModel.php';
require_once 'StorekeeperEndpoint.php';
require_once 'CustomerEndpoint.php';
require_once 'customerRepEndpoint.php';
require_once 'TransporterEndpoint.php';
require_once 'PublicEndpoint.php';
require_once 'AuthenticationEndpoint.php';
class controller{
    public function __construct(){

    }
    /**
     * Denne funksjonen motar en request fra api.php finner ut hvilket endpoint det skal til og sender informasjon videre
     * til dette endpointet foreksempel customer. pr nå returnerer den kun vi eri kontroller, dette skal du se via din
     * nettleser
     * @param $dividedUri - Motar en arry som inneholder URI delt
     * @param $specificQuery - motar query (etter ?)
     * @param $requestType - om det er get, put osv
     * @return string - returnerer en teststring pr nå
     */


    public  function request(array $dividedUri,array $specificQuery,string $requestType,array $requestBodyJson){
        $witchEndpoint = $dividedUri[0]; //Extracting the endpoint

        switch ($witchEndpoint){
            case "orders": echo "orderS endpoint"; break;
            case "customer" : 
                // echo "customer endpoint\n";
                return (new CustomerEndpoint())->handleRequest($dividedUri,$specificQuery,$requestType);
                break;
            case "shipment" : 
                // echo "customer endpoint\n";
                return (new TransporterEndpoint())->handleRequest($dividedUri,$specificQuery,$requestType);
                break;
            case "storekeeper": 
               // echo "storekeeper endpoint";
                return (new StorekeeperEndpoint())->handleRequest($dividedUri,$specificQuery,$requestType,$requestBodyJson);
                break;
            case "production-.plans": echo "production-plans";break;
            case "public":
               return (new publicEndpoint())->handleRequest($dividedUri,$specificQuery,$requestType,$requestBodyJson);

                break;
            case "customer-rep":
                //echo "vi er i customer-rep";
                return (new customerRepEndpoint()) ->handleRequest($dividedUri,$specificQuery,$requestType,$requestBodyJson);
                break;
            default: return "error no url";

        }

    }

    /**
     * This function chek wethever the user is authenticated with a token and is allowed to access the specifed endpoint
     * @param $token - The token provided from frontend
     * @param $dividedUri - The endpoint URI
     */
    public function authentication(array $dividedUri, string $token) :bool{
        //print("\n Vi er i authentisering");
        //print("\n" . $token . "\n");
        //return true;
        return (new AuthenticationEndpoint()) ->handleEndpoint($token,$dividedUri);

    }

}