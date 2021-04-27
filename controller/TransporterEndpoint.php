<?php
//require_once 'RESTConstants.php';
// require_once 'db/StorekeeperModel.php';
require_once 'db/TransporterModel.php';

class TransporterEndpoint
{
    public function handleRequest($uri,$specificQuery,$requestType, $token, $requestBody)
    {
        $lengde = count($uri);
        if ($lengde == 2 && $requestType == "GET") {
            $this->validateURI($uri);
            $arr = array('shipping_address', 'scheduled_pickup', 'transporter', 'driver_id');
            $this->validateBody($requestBody, $arr);
            return $this->updateShipmentStatus($uri[1], $token, $requestBody);
        } else {
            throw new BusinessException(httpErrorConst::badRequest, "Request is not valid for the designated endpoint");
        }
        
    
    }
    // get 4 week product plan
    private function updateShipmentStatus($order_nr, $token, $requestBody)
    {
        return (new TransporterModel())->updateShipment($order_nr, $token, $requestBody);
        
    }
    private function validateURI(array $uri) {
        if (!is_numeric($uri[1])) {
            throw new BusinessException(400, "Endpoint expected customer id to be a number");
        }; 
    }
    private function validateBody($requestBody, array $arr) {
       
        foreach ($arr as &$value) {
            
            if (!array_key_exists($value, $requestBody)) {
                $reason = "Request was not processed due to missing the value: ";
                $reason .= $value;
                throw new BusinessException(400, $reason);
            } 
        }
    }
  
    // /**
    //  * This function find a employee id and returns the employeeid
    //  * @param $token    - token from the authenticated user
    //  * @return int  -    employee id
    //  */
    // public function findEmployeeId($token) :int{
    //     $queryEmployeeDepartment= "SELECT employee_id FROM employees WHERE token LIKE :token";
    //     $statementEmployeeDepartment= $this ->db->prepare($queryEmployeeDepartment);
    //     $statementEmployeeDepartment ->bindValue(':token',$token);
    //     $statementEmployeeDepartment ->execute();
    //     $department = $statementEmployeeDepartment ->fetchAll(PDO::FETCH_COLUMN);
    //     return $department[0]; //Retuns employee id

    //     //todo create an exeption if there is no employee with the token found.

    // }



}