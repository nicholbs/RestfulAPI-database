<?php
//require_once 'RESTConstants.php';
// require_once 'db/OrderModel.php';
require_once 'DB.php';

class customerRepModel extends DB
{
    public function retrieveOrders(): array
    {
        $stmt = $this ->db ->query("SELECT * FROM orders");
        $res =$stmt ->fetchAll();

        return $res;
    }

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

    public function createSki()
    {

    }
}
/**
$test = new customerRepModel();
$test ->getAllSubordersWithInformation(1);

$test2 = $test ->getAllSubordersWithInformation(1);
print_r($test2);

$testArray= array();

foreach ($test2 as $row){
    print($row['type_id']);
}
**/