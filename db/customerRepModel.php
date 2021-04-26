<?php
//require_once 'RESTConstants.php';
// require_once 'db/OrderModel.php';
require_once 'DB.php';
require_once 'AuthenticationModel.php'; //In use in the function ChangeOrderState for retriving the nemployee id

class customerRepModel extends DB
{


    /**
     * This function return all orders of certan(s) status
     *
     * @param array $specificQuery - The query data from frontend
     * @see this -> getAllSubordersWithInfromation()
     * @return array - Return an array with all skis of certan(s) status
     */
    public function getOrdersFilter(array $specificQuery) :array{
        echo("\n Status getOrdersFilter");
        $filter = explode(',', $specificQuery['status']); //divde the filter and save the result to a query

        //creating querystring for all the status elements
        $queryOrders = "SELECT order_nr, price, state FROM `orders` WHERE state IN (:arg0";
        for($i =1; $i <count($filter); $i++){
            $queryOrders .= ",:";
            $queryOrders .= "arg$i";
        }
        $queryOrders .= ')';
        $statmentqueryOrders = $this ->db ->prepare($queryOrders);

        //Binding the values from status
        for ($i = 0; $i < count($filter); $i++) {
            $temp = ":arg$i";
            $statmentqueryOrders->bindValue($temp, $filter[$i]);
        }
        $statmentqueryOrders->execute();

        //Store the infromation retrived from orders
        $ordersTable = array();
        $ordersTable = $statmentqueryOrders ->fetchAll(PDO::FETCH_ASSOC);

       $resultArray = array();

        //Here we wil create the result
        foreach ($ordersTable as $row){
            //creating an array inside the resultarry with order_nr as key. Afhter thath i am first adding prixce and orderstate.
            $resultArray[$row['order_nr']] = array(
                "price_total" => $row['price'],
                "orderstate" => $row['state'],
                "skis" => array( //building an empty array for skis, in the next section we will add skis to this array
                ),
            );
            //array for storing the suborder temporary
            $tempArray = array();
            //Getting all the skis from a specific order
            $tempArray = $this ->getAllSubordersWithInformation($row['order_nr']);
            //Adding the skis with the key of type_id on a certan model. Afhter tath i am appending the hole object.
            foreach ($tempArray as $temprow){
                $resultArray[$row['order_nr']]['skis'][$temprow['type_id']]= $temprow ;

            }

        }
       // print_r($resultArray);
        return $resultArray;

    }

    /**
     * This function returns all skis in a order
     * @param int $ordernr - The ordernumber
     * @return array - All skis of a specific order.
     */

    public function getAllSubordersWithInformation(int $ordernr) : array{
        $query = "SELECT ski_types.type_id, ski_types.model,ski_types.temperature,ski_types.grip,ski_types.size,ski_types.weight_class,sub_orders.ski_quantity FROM `sub_orders`
INNER JOIN ski_types ON sub_orders.type_id = ski_types.type_id
WHERE order_nr LIKE :arg0";
        $statement = $this ->db ->prepare($query);
        $statement->bindValue(':arg0',$ordernr);
        $statement ->execute();
        $result = $statement ->fetchAll(PDO::FETCH_ASSOC);
       // print_r($result);

        return  $result;



    }

    /**
     * This function update the orderstate. In this case of the project we assume tath you are only allowed  to change from new to open for a order
     * @param array $specificQuery - not in use
     * @param array $requestBodyJson - the json object with ordernumber and state for updating the order
     * @param string $token - the token from frontend, we hare using this to find the employee id
     * @return array - with information tatht the cfurrent order has got the status updated
     * @throws APIException - if there is som error ore the update is not allowed
     * @see $this ->orderStatusChek()
     */
    public function changeOrderState(array $specificQuery, array $requestBodyJson, string $token ) :array{
        $dataInputOk= false; //Used for cheking tath the keys in the array from user is ok
        $orderStatusFromOk = false;
        $orderstateFrom = "new"; //If a future implementation request wil allow custom from status we can easirer rewrite the code
        $orderHistoryQuery = "INSERT INTO order_history(order_nr,state,customer_rep) VALUES(:arg0,:arg1,:arg2)";
        $ordersQuery= "UPDATE orders SET state = :arg0 WHERE order_nr = :arg1";
        $currentOrderState = $this ->orderStatusChek($requestBodyJson['orderNumber']);


    //Retrive the employee based on the token
      $employeeid = (new  AuthenticationModel()) ->findEmployeeId($token); //Finding the employee id based on token provider

        //Cheking tath the json file consist of the required keys
       if(array_key_exists('orderNumber',$requestBodyJson)){
           $dataInputOk = true ;
       };
       if(array_key_exists('status',$requestBodyJson) && $dataInputOk == true){
           $dataInputOk = true;
       }
       else{
           $dataInputOk = false;
       }
       //If the dadastructure dosent match the json keys we send a error message frontend
       if($dataInputOk == false){
           throw new APIException(404, "The json format dosent mach the requirements");
           $errorMess= array();
           $errorMess['message'] ="Datastructure dosent match the expected input";
           return $errorMess; //Quit prosessing the rest of the code, error alredy trhown.
       }
       //If the orderstate of the current order is on a level there is not allowed to change from. In ths code you are only allowed to change from new to open
       elseif ($dataInputOk == true && $currentOrderState!= $orderstateFrom){
           $message = "Current orderstate is: " . $currentOrderState . " you can only change from order state " . $orderstateFrom . " to orderstate open ";
           throw new APIException(404, $message);
           return null;


       }
       //If all hte cheks is okay we are updating the orderstate in the database
       else{
           $orderNumber = $requestBodyJson['orderNumber']; //ordernumber from the json
           $updateStatus = $requestBodyJson['status']; //status from the json
           $res = array();
           //starting by updating the order_history
           try {
               //First updating the orderHistory table
               $this -> db ->beginTransaction(); //start an transaction
               $staementOrderHistory =$this ->db ->prepare($orderHistoryQuery);
               $staementOrderHistory ->bindValue(':arg0',$orderNumber);
               $staementOrderHistory ->bindValue(':arg1',$updateStatus);
               $staementOrderHistory ->bindValue(':arg2',$employeeid);
               $staementOrderHistory -> execute();

               // then Updating the orders table
               $staementOrders = $this ->db ->prepare($ordersQuery);
               $staementOrders ->bindValue(':arg0',$updateStatus);
               $staementOrders ->bindValue(':arg1',$orderNumber);
               $staementOrders ->execute();

               $this ->db ->commit(); //If we sucesfully executed both statments we wil commit the changes to the db
               $res = array();
               $res['status']= "ok";
               $res['orderNumber'] = $orderNumber;
               $res['message'] = "The order" . $orderNumber . "  status changed to " .$updateStatus;
               return $res;

           }
           //if updating fails in one of the queries, we will rollback
           catch (Exception $e){
               $this ->db ->rollBack();

           }



           return $res;

       }




    }

    /**
     * This function find the current state of a roder
     * @param int $ordernumber - ordernumer for finding the current state
     * @return string - return the state of the current order.
     */
    public function orderStatusChek(int $ordernumber) :string {
        $query="SELECT state FROM orders WHERE order_nr like :arg0";
        $statement = $this ->db -> prepare($query);
        $statement ->bindValue(':arg0',$ordernumber);
        $statement ->execute();
        $res = $statement ->fetchAll(PDO::FETCH_COLUMN);
        return $res[0];

    }
    public function createSki()
    {

    }
}
