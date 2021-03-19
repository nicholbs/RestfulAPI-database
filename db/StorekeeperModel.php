<?php
//require_once 'RESTConstants.php';
require_once 'db/OrderModel.php';

class StorekeeperModel extends DB
{
    public function retrieveOrders(): array
    {
        $stmt = $this->db->query("SELECT * FROM orders");
        $res = $stmt->fetchAll();

        return $res;
    }

    public function createSki()
    {

    }
}