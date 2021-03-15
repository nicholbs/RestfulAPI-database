<?php
class controller{
    public function __construct(){
      //implementer logikk her

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
        $test ="Vi er i kontroller";
        //legg på logikk her
        return $test;
    }
}