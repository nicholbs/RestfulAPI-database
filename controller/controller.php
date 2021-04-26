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
require_once 'constants.php';
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


    public  function request(array $dividedUri,array $specificQuery,string $requestType, array $requestBody, string $token){
        $witchEndpoint = $dividedUri[0]; //Extracting the endpoint

        switch ($dividedUri[0]){
            case "orders": echo "orderS endpoint"; break;
            case "customer" : 
                return (new CustomerEndpoint())->handleRequest($dividedUri,$specificQuery,$requestType, $requestBody);
                break;
            case "shipment" : 
                return (new TransporterEndpoint())->handleRequest($dividedUri,$specificQuery,$requestType);
                break;
            case "storekeeper": 
                return (new StorekeeperEndpoint())->handleRequest($dividedUri,$specificQuery,$requestType,$requestBody);
                break;
            case "production-.plans": echo "production-plans";break;
            case uriConst::public:
               return (new publicEndpoint())->handleRequest($dividedUri,$specificQuery,$requestType,$requestBody);

                break;
            case "customer-rep":
                return (new customerRepEndpoint()) ->handleRequest($dividedUri,$specificQuery,$requestType,$requestBody, $token);
                break;
            default: 
            throw new APIException(404, "The URL given does not match any endpoints");

        }
    }

    /**
     * This function chek wethever the user is authenticated with a token and is allowed to access the specifed endpoint
     * @param $token - The token provided from frontend
     * @param $dividedUri - The endpoint URI
     * @see authenticationEndpoint() -> handleEndpoint()
     */
    public function authentication(array $dividedUri, string $token) :bool{

        return (new AuthenticationEndpoint()) ->handleEndpoint($token,$dividedUri);

    }

}