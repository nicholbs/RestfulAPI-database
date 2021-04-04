<?php
require_once 'DB.php';

class AuthenticationModel extends DB {

    //Pensjonert drøft sletting -odd

    /**
     * This class schek if a employee have the rights to access a specific endpoint
     * @param $token - the token provided from frontent / user
     * @param $department - what department/ first part of the uri the resource shuld access
     * @return bool - if the employee has a valid token AND is in the right department
     */
    /**
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
     * **/

    //pensjonert drøft sletting -odd

    /**
     * This funnction chek if a customer has a valid token for accessing the API request
     * @param $token - the token provided from frontent / user
     * @return bool - return true/false based on the customer has a valid token
     */
    /**
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
     * */

    /**
     * This function find the usertype based on the token, If the user dosen't have a token we return notAuthenticated
     * @param $token - token from the user /frontend
     * @return string - return customer, department ore notAuthenticated if nothing is found from the token.
     */
    public function findUsertype($token) :string{

        //Check if the token belongs to a customer:
        $queryCustomerCount="SELECT COUNT(customer_id) as customer FROM customers WHERE token LIKE :token";
        $statementCustomer = $this ->db -> prepare($queryCustomerCount); //prepare the statment
        $statementCustomer ->bindValue(':token',$token);
        $statementCustomer ->execute();
        $customerCount=$statementCustomer->fetchColumn();

        //If there is exactely one match in the customer table, we habve found a valid hit,
        if($customerCount ==1 ){
           // echo("Customer funnet");
            return "customer"; //This will end the rest of the function so tath the employee code wil not run if there is a hit on customer
        }

        //If the token not belongs to a customer, we chek if the token belongs to a employee

        //Preper the data
        $queryEmployee="SELECT COUNT(employee_id) AS NumberOfEmployee FROM employees WHERE token LIKE :token";
        $statementEmployeeCount = $this ->db -> prepare($queryEmployee); //prepare the statment
        $statementEmployeeCount ->bindValue(':token',$token);
        $statementEmployeeCount ->execute();
        $EmployeeCount=$statementEmployeeCount->fetchColumn();

        //Chek if we get exactly one hit on the emplyee table.
        if($EmployeeCount ==1){
            //echo("employee found");
            //If we get a hit we will get the department based on the token
            $queryEmployeeDepartment= "SELECT department FROM employees WHERE token LIKE :token";
            $statementEmployeeDepartment= $this ->db->prepare($queryEmployeeDepartment);
            $statementEmployeeDepartment ->bindValue(':token',$token);
            $statementEmployeeDepartment ->execute();
            $department = $statementEmployeeDepartment ->fetchAll(PDO::FETCH_COLUMN);
            return $department[0]; //Returns the department as a string for the user with the specific token
        }
        //If we dosent have a hit in customer ore employee we return notAuthenticated
        else{
            return "notAuthenticated";
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

/**

$c="2927ebdf56c20cbb90fbd85cac5be30d60e3dfb9f9c9eda869d0fdce36043a85";
$b ="e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855";
$customer = new  AuthenticationModel();
$f = $customer -> findUsertype($c);

print("\nDette er avdelingen: " . $f);
 * **/