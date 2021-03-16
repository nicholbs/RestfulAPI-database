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
     * @param $dividedUri - Motar uri delt i dler
     * @param $specificQuery - motar query parametre
     * @param $requestType - om det er get, put osv
     * @return string - returnerer en teststring pr nå
     */
    public  function request($dividedUri,$specificQuery,$requestType){
        $test ="Vi er i kontroller - fant ikke case";
        $vi = "Vi er i kontroller customer";
        $vie = "Vi er i kontroller order";
        $pu = "Vi er i kontrolleren og i public";
        //legg på logikk her

        //Her kan vi vidresende til customer endpoint
        if ($dividedUri[0] == "customer"){

            return $vi;
        }
        elseif ($dividedUri[0] == "order") {
           // OrderModel::getCollection();
            $res = new OrderModel();
            $res -> getCollection();
            return $res;


        }
        //her kan vi vidresende til public endpoint
        elseif ($dividedUri[0]== "public"){
            $publicEnd = new publicModel();
            $res = $publicEnd ->getAllSkiTypes();
            return $res;
        }

        else{
            return $test;

        }

    }
}