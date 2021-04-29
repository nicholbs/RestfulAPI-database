<?php
require_once 'db/DB.php';

class StorekeeperModel extends DB
{
    public function retrieveOrders(): array
    {
        // NB! Each row returns data for order and suborder, providing redundant order data for orders with multiple suborders
        $query = 'SELECT order_nr, name, state, buying_price, ROUND(price, 2) AS total, model, ski_quantity, msrp, subtotal FROM order_view';
        $stmt = $this->db->query($query);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $res = array();
        for($i = 0; $i < COUNT($orders); $i++)
        {
            $row = array();

            // Prints order
            $row['order_nr']        = $orders[$i]['order_nr'];
            $row['name']            = $orders[$i]['name'];
            $row['buying_price']    = $orders[$i]['buying_price'];
            $row['state']           = $orders[$i]['state'];
            $row['total']           = $orders[$i]['total'];

            // Prints obligatory suborder
            $row['sub_orders'] = array();
            $this->fillSubOrder($row, 0, $orders[$i]);

            // Prints additional suborders
            $counter = 1;
            while($i + $counter < count($orders) && $orders[$i + $counter]['order_nr'] == $row['order_nr']){
                $sub_count = count($row['sub_orders']);
                $this->fillSubOrder($row, $sub_count, $orders[$i + $counter]);
                $counter++;
            }
            array_push($res, $row);
            $i += $counter - 1; // Skips redundant orders
        }
        return $res;
    }

    private function fillSubOrder(&$target_array, $target_index, $source_array)
    {
        $target_array['sub_orders'][$target_index]['model']     = $source_array['model'];
        $target_array['sub_orders'][$target_index]['msrp']      = $source_array['msrp'];
        $target_array['sub_orders'][$target_index]['quantity']  = $source_array['ski_quantity'];
        $target_array['sub_orders'][$target_index]['subtotal']  = $source_array['subtotal'];
    }

    public function createSki($requestBody)
    {
        $res = array(); // Used to generate response

        // Check if all fields are present in body
        if(!array_key_exists('ski_type', $requestBody) || !array_key_exists('manufactured_date', $requestBody))
            throw new APIException(HTTPConstants::BAD_REQUEST, "Field missing in request body");

        $date = $requestBody['manufactured_date'];

        // Check unspecified or invalid date
        if($date == "")
            $date = strval(date('Y-m-d H:i:s'));
        elseif(!fnmatch('####-##-## ##:##:##', $date))
            throw new APIException(HTTPConstants::BAD_REQUEST, "Invalid manufacture date");

        //-- You've entered the TRANSACTION ZONE --
        $this->db->beginTransaction();

        $query = "INSERT into skis (ski_type, manufactured_date) VALUES (:ski_type, :manufactured_date)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":ski_type", $requestBody['ski_type']);
        $stmt->bindValue(":manufactured_date", $date);
        $stmt->execute();
        $res['serial_nr'] = $this->db->lastInsertId();

        $this->db->commit();
        
        $res['ski_type'] = $requestBody['ski_type'];
        $res['manufactured_date'] = $date;

        return $res;
    }
}