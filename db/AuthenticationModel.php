<?php
require_once 'DB.php';

class AuthenticationModel extends DB {


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
        $customerCount=$statementCustomer->fetchColumn(); //retrive number of matches

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
        $EmployeeCount=$statementEmployeeCount->fetchColumn(); // retrive number of matches in the database.

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

    /**
     * This function find a employee id and returns the employeeid
     * @param $token    - token from the authenticated user
     * @return int  -    employee id
     */
    public function findEmployeeId($token) :int{
        $queryEmployeeDepartment= "SELECT employee_id FROM employees WHERE token LIKE :token";
        $statementEmployeeDepartment= $this ->db->prepare($queryEmployeeDepartment);
        $statementEmployeeDepartment ->bindValue(':token',$token);
        $statementEmployeeDepartment ->execute();
        $department = $statementEmployeeDepartment ->fetchAll(PDO::FETCH_COLUMN);
        return $department[0]; //Retuns employee id

        //todo create an exeption if there is no employee with the token found.

    }


}//End of AuthenticationModel class
/**
$token ="022224c9a11805494a77796d671bec4c5bae495af78e906694018dbbc39bf2cd";
$user = new  AuthenticationModel();
$userid = $user ->findEmployeeId($token);
print("Userid of a employee is: " . $userid);
**/