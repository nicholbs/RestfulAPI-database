<?php
require_once 'DB.php';

class AuthenticationModel extends DB {

    /**
     * This class schek if a employee have the rights to access a specific endpoint
     * @param $token - the token provided from frontent / user
     * @param $department - what department/ first part of the uri the resource shuld access
     * @return bool - if the employee has a valid token AND is in the right department
     */
    public function employeeAuth(string $department, string $token) :bool{

        $query= "SELECT COUNT(employee_id) AS NumberOfEmployee FROM employees WHERE department LIKE :dep AND token LIKE :token";
        $statement = $this ->db -> prepare($query); //prepare the statment

        $statement ->bindValue(':dep',$department);
        $statement ->bindValue(':token',$token);

        $statement ->execute();
        $result=$statement->fetchColumn();
      //  print_r($result);

        //Send response based if the user has the token and is in the right department for accessing the endpoint.
        if($result ==1){
            return true;
        }
        else{
            return false;
        }

    }

    /**
     * This funnction chek if a customer has a valid token for accessing the API request
     * @param $token - the token provided from frontent / user
     * @return bool - return true/false based on the customer has a valid token
     */
    public function customerAuth($token) :bool{
        $query="SELECT COUNT(customer_id) as customer FROM customers WHERE token LIKE :token";

        $statement = $this ->db -> prepare($query); //prepare the statment
        $statement ->bindValue(':token',$token);
        $statement ->execute();
        $result=$statement->fetchColumn();

        //Send response based if the customer har a vaild token
        if($result ==1){
            return true;
        }
        else{
            return false;
        }

    }

}//End of AuthenticationModel class

//Some testdata

/**
$a ="storekeeper";
$b ="e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855";
$tabell= array();
$tabell[0]="storekeeper";


$test = new AuthenticationModel();
//$test ->employeeAuth($a,$b);

if($test->employeeAuth($a,$b)){
    print("\n employee ok");
}

**/
/**

$c="2927ebdf56c20cbb90fbd85cac5be30d60e3dfb9f9c9eda869d0fdce36043a85";

$customer = new AuthenticationModel();
$customer ->customerAuth($c);

if($customer ->customerAuth($c)){
    print("\n authentisert");
}
else{
    print("\n Ikke authentisert");

}
 * **/