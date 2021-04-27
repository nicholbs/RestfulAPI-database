<?php
require_once 'db/DB.php';

class StorekeeperModel extends DB
{
    public function retrieveOrders(): array
    {
        // NB! Returns redundant order data for orders with several suborders
        $query = 'SELECT order_nr, name, state, buying_price, ROUND(price, 2) AS total, model, ski_quantity, msrp, subtotal FROM order_view';
        $stmt = $this->db->query($query);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $res = array();
        for($i = 0; $i < COUNT($orders); $i++)
        {
            // Prints order
            $res[$i] = array();
            $res[$i]['order_nr']        = $orders[$i]['order_nr'];
            $res[$i]['name']            = $orders[$i]['name'];
            $res[$i]['buying_price']    = $orders[$i]['buying_price'];
            $res[$i]['state']           = $orders[$i]['state'];
            $res[$i]['total']           = $orders[$i]['total'];

            // Prints obligatory suborder
            $res[$i]['sub_orders'] = array();
            $res[$i]['sub_orders'][0]['model']      = $orders[$i]['model'];
            $res[$i]['sub_orders'][0]['msrp']       = $orders[$i]['msrp'];
            $res[$i]['sub_orders'][0]['quantity']   = $orders[$i]['ski_quantity'];
            $res[$i]['sub_orders'][0]['subtotal']   = $orders[$i]['subtotal'];

            // Prints additional suborders
            $counter = 1;
            while($i + $counter < count($orders) && $orders[$i + $counter]['order_nr'] == $res[$i]['order_nr']){
                $sub_count = count($res[$i]['sub_orders']);
                $res[$i]['sub_orders'][$sub_count]['model']     = $orders[$i + $counter]['model'];
                $res[$i]['sub_orders'][$sub_count]['msrp']      = $orders[$i + $counter]['msrp'];
                $res[$i]['sub_orders'][$sub_count]['quantity']  = $orders[$i + $counter]['ski_quantity'];
                $res[$i]['sub_orders'][$sub_count]['subtotal']  = $orders[$i + $counter]['subtotal'];
                $counter++;
            }
            $i += $counter - 1; // Skips redundant orders
        }
        return $res;
    }

    public function createSki()
    {

    }
}