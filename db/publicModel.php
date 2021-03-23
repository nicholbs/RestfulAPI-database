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
        //$result = $statement ->fetchAll();
        $result = $statement ->fetchAll(PDO::FETCH_ASSOC);
        return($result);

    }//End of

    /**
     * This function  finds all skimodels based on grip filter
     * @param $specificQuery
     * @return array
     */
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
       // $result = $statement ->fetchAll();
        $result = $statement ->fetchAll(PDO::FETCH_ASSOC);


        return($result);

    }

    /**
     * This function finds skis based on model and grip filter
     * @param $specificQuery
     * @return array
     */
    public function getAllFilter($specificQuery){
        $modelsFilter = array(); //Used to save ach model thath shuld be filtred
        $gripFilter = array();
        $modelsFilter = explode(',',$specificQuery['model']); //divide the specific filter into an array
        $gripFilter = explode(',',$specificQuery['grip']); //divide the specific filter into an array

        //First create the query string before prepare statement
        //The first part of query
        $query = "SELECT * FROM `ski_types` WHERE model IN (:arg0";
        //contactinate the arguments for the prepare statmnt
        for($i=1; $i < count($modelsFilter); $i++){
            $query .= ",:";
            $query .="arg$i";
        }
        $query .= ')';

        //finishes the query string, adding grip
        $query .= "OR grip in (:argx0";
        for($i=1; $i < count($gripFilter); $i++){
            $query .= ",:";
            $query .="argx$i";
        }
        $query .= ')';

        $statement = $this ->db -> prepare($query); //prepare the statment

        //Binding values before execute fo model filter
        for($i=0; $i <count($modelsFilter); $i++){
            $temp = ":arg$i";
            $statement ->bindValue($temp,$modelsFilter[$i]);
        }

        //Binding values before execute for grip system
        for($i=0; $i <count($gripFilter); $i++){
            $temp = ":argx$i";
            $statement ->bindValue($temp,$gripFilter[$i]);
        }

        $statement -> execute();
        $result = array();
        $result = $statement ->fetchAll(PDO::FETCH_ASSOC);
        return($result);


    }
    //ikke i bruk pr na
    public function getModelFilter($specificQuery,$filtertype) : array{
        $modelsFilter = array(); //Used to save ach model thath shuld be filtred
        $modelsFilter = explode(',',$specificQuery[$filtertype]); //divide the specific filter into an array


        //First create the query string before prepare statement
        //The first part of uery
        $query = "SELECT * FROM `ski_types` WHERE $filtertype IN (:arg0";
        //contactinate the arguments for the prepare statmnt
        for($i=1; $i < count($modelsFilter); $i++){
            $query .= ",:";
            $query .="arg$i";
        }
        $query .= ')';


        $statement = $this ->db -> prepare($query); //prepare the statment

      //$statement ->bindValue(":arga",$filtertype);
        //Binding values before execute
        for($i=0; $i <count($modelsFilter); $i++){
            $temp = ":arg$i";
            $statement ->bindValue($temp,$modelsFilter[$i]);
        }
        $statement -> execute();
        $result = $statement ->fetchAll();
        print_r($result);
        echo ("\ndette er ilter:" . $filtertype);

        return($result);

    }

}//End of class public model

/**
$tabell = array();
$tabell['model']="tester";
$tabell['grip']="dd,aa";
//print_r($tabell);

$test = new publicModel();
print_r($test ->getAllFilter($tabell));
//print_r($test ->getSkiTypesModelFilterolodslett($tabell));
//print_r($test ->getAllFilter($tabell));

$tabell2 = array();
$tabell2['model'] ="tester,dd";
//print_r($test ->getSkiTypesModelFilter($tabell2));

**/






