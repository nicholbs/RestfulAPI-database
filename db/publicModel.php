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

    /**
     * This functions find all skis based on models
     * @param $specificQuery - araay with model filter
     */
    public function getSkiTypesModelFilter($specificQuery) : array{
        $modelsFilter = array(); //Used to save ach model thath shuld be filtred
        $modelsFilter = explode(',',$specificQuery['model']); //divide the specific filter into an array


        //First create the query string before prepare statement
        //The first part of uery
        $query = "SELECT * FROM `ski_types` WHERE model IN (:arg0";
        //contactinate the arguments for the prepare statmnt
        for($i=1; $i < count($modelsFilter); $i++){
                $query .= ",:";
                $query .="arg$i";
        }
        $query .= ')';

        $statement = $this ->db -> prepare($query); //prepare the statment

        //Binding values before execute
        for($i=0; $i <count($modelsFilter); $i++){
            $temp = ":arg$i";
            $statement ->bindValue($temp,$modelsFilter[$i]);
        }

        $statement -> execute();
        $result = $statement ->fetchAll();


        return($result);

    }//End of

    public function getGripModelFilter($specificQuery) : array{
        $modelsFilter = array(); //Used to save ach model thath shuld be filtred
        $modelsFilter = explode(',',$specificQuery['grip']); //divide the specific filter into an array


        //First create the query string before prepare statement
        //The first part of uery
        $query = "SELECT * FROM `ski_types` WHERE grip IN (:arg0";
        //contactinate the arguments for the prepare statmnt
        for($i=1; $i < count($modelsFilter); $i++){
            $query .= ",:";
            $query .="arg$i";
        }
        $query .= ')';

        $statement = $this ->db -> prepare($query); //prepare the statment

        //Binding values before execute
        for($i=0; $i <count($modelsFilter); $i++){
            $temp = ":arg$i";
            $statement ->bindValue($temp,$modelsFilter[$i]);
        }

        $statement -> execute();
        $result = $statement ->fetchAll();


        return($result);

    }

}//End of class public model


