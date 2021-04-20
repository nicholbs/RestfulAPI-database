<?php
//require_once 'RESTConstants.php';
// require_once 'db/OrderModel.php';

class customerRepModel extends DB
{
    public function retrieveOrders(): array
    {
        $stmt = $this ->db ->query("SELECT * FROM orders");
        $res =$stmt ->fetchAll();

        return $res;
    }

    /**
     * This function find orders based on a certan status
     */
    public function getOrdersFilter(array $specificQuery) :array{
        echo("\n Status getOrdersFilter");
        $filter = explode(',', $specificQuery['status']); //divde the filter and save the result to a query

        $query="SELECT orders.order_nr, orders.ski_quantity, orders.state, ski_types.type_id, ski_types.model, ski_types.type FROM orders
LEFT JOIN skis on orders.order_nr = skis.order_assigned LEFT JOIN ski_types on skis.ski_type = ski_types.type_id
WHERE state LIKE 'skis-available'";

        $kommando = $this ->db ->query($query);
        $res = $kommando ->fetchAll(PDO::FETCH_ASSOC);
        print_r($res);
        return $res;

    }

    public function createSki()
    {

    }
}