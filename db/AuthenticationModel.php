<?php
require_once 'DB.php';

class AuthenticationModel extends DB {

    /**
     * This class schek if a employee have the rights to access a specific endpoint
     * @param $token
     * @param $dividedUri
     */
    public function employeeAuth($department, $token){

        $query= "SELECT COUNT(employee_id) AS NumberOfEmployee FROM employees WHERE department LIKE :dep AND token LIKE :token";
        $statement = $this ->db -> prepare($query); //prepare the statment

        $statement ->bindValue(':dep',$department);
        $statement ->bindValue(':token',$token);

        $statement ->execute();
        $result=$statement->fetchColumn();
        print_r($result);

    }

}//End of AuthenticationModel class
$a ="storekeeper";
$b ="e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855";

$test = new AuthenticationModel();
$test ->employeeAuth($a,$b);