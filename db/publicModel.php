<?php
//require_once 'DB.php';
require_once 'DB.php';

class publicModel extends DB {

    public function __construct()
    {
        parent::__construct();
    }
    //Slutt på constructor

    public function getAllSkiTypes(){
        //echo("\n Hit kom jeg \n");
        $kommando = $this ->db ->query("SELECT * FROM ski_types");
        $resultatfradb =$kommando ->fetchAll();


        //print_r($resultatfradb); //skriver ut hele arrayen.

        return $resultatfradb;

    }//end of getAllSkies

}//End of class public model

//$test =  new publicModel();
//$test ->getAllSkiTypes();
