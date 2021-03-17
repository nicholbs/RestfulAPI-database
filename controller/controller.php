<?php
require_once 'db/publicModel.php';
require_once 'db/OrderModel.php';
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


    public  function request($dividedUri,$specificQuery,$requestType){
        $witchEndpoint = $dividedUri[0]; //Extracting the endpoint

        switch ($witchEndpoint){
            case "order":
                return (new OrderModel())->getCollection();
                break;
            case "orders": echo "orderS endpoint"; break;
            case "customer" : echo "customer endpoint"; break;
            case "storekeeper": echo "storekeeper endpint"; break;
            case "production-.plans": echo "production-plans";break;
            case "public":
                $publicEnd = new publicModel();
                $res = $publicEnd ->getAllSkiTypes();
                return $res;
                break;
            default: return "error no url";

        }

    }

}