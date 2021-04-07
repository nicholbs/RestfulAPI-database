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
     * This function finds skis based on model and grip filter
     * @param $specificQuery
     * @return array
     */
    public function getAllFilter(array $specificQuery) : array{
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

    /**
     * Ths function finds all the skiis based on a certan filter criteria
     * @param array $specificQuery - the end users filter of choise
     * @param string $filtertype - a local variabel used in PublicEndpoint to tell this function if it is a model ore a grip filter in use.
     * @return array - returns the results.
     */
    public function getAFilter(array $specificQuery,string $filtertype) : array
    {
        $modelsFilter = array(); //Used to save ach model thath shuld be filtred
        //Using the if statment to do achek of the input of filtertype. In pdo you cant use bindings in the where closet so i do a valdaton in the if statment instead. $fiiltertype varaibel wil however not be directly in tuch with the enduser in anyway.
        if ($filtertype == "model" || $filtertype == "grip") {
            $modelsFilter = explode(',', $specificQuery[$filtertype]); //divide the specific filter into an array


            //First create the query string before prepare statement
            //The first part of uery
            $query = "SELECT * FROM `ski_types` WHERE $filtertype IN (:arg0";
            //contactinate the arguments for the prepare statmnt
            for ($i = 1; $i < count($modelsFilter); $i++) {
                $query .= ",:";
                $query .= "arg$i";
            }
            $query .= ')';


            $statement = $this->db->prepare($query); //prepare the statment

            //$statement ->bindValue(":arga",$filtertype);
            //Binding values before execute
            for ($i = 0; $i < count($modelsFilter); $i++) {
                $temp = ":arg$i";
                $statement->bindValue($temp, $modelsFilter[$i]);
            }
            $statement->execute();
            $result = $statement ->fetchAll(PDO::FETCH_ASSOC);
            return($result);


        }
    }

}//End of class public model







