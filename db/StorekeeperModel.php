<?php
require_once 'db/DB.php';

class StorekeeperModel extends DB
{
    public function retrieveOrders(): array
    {
        // NB! Each row returns data for order AND suborder, providing redundant order data for orders with multiple suborders
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
            //while($i in scope of $orders && next row has same order_nr)
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

    /**
     * This function sets a storekeeper tranisiton record
     * @param array $requestBodyJson()  - the request from frontend with the ordernumber and ski serialnumber
     * @return $res - respons with message
     * @see $this -> orderExist()
     * @see $this -> skiExist()
     */
    public  function transitionRecord(array $requestBodyJson) : array {
        $orderNumber = $requestBodyJson['orderNumber'];
        $serialNr = $requestBodyJson['serialNr'];
        $query="UPDATE skis SET order_assigned = :arg0 WHERE serial_nr = :arg1";
        $res = array(); //the responsmessage send frontend

        //if bouth the order and the ski serialnumber exist we will create the transistion (give the ski a order)
        if($this->orderExist($orderNumber) && $this->skiExist($serialNr)){
            $statement = $this ->db->prepare($query);
            $statement ->bindValue(':arg0',$orderNumber);
            $statement ->bindValue(':arg1',$serialNr);
            $statement ->execute();

            //building a return message:
            $message = "The order: " . $orderNumber . " with the ski serialnumber: " . $serialNr . " was sucesfully set";
            $res['status'] = "ok";
            $res['orderNr']= $orderNumber;
            $res['serialNr'] = $serialNr;
            $res['message'] =$message;
            return $res;


        }
        //If the ordernumber dosent exist
        elseif (!$this->orderExist($orderNumber) ){
            $message= "Ordernumber: " . $orderNumber . " Dosent exist";
            throw new APIException(404, $message);
        }
        //If the sli serialnumber dosent exist
        elseif (!$this->skiExist($serialNr)){
            $message = "Ski with the serialnumber: " . $serialNr . " dosent exist";
            throw new APIException(404, $message);
        }
        //If we somhow dosent match the code
        else{
            $message="Somthing wrong happend, No case match";
            throw new APIException(500, $message);
        }




    }

    /**
     * This function chek if a order exist in the orders table
     * @param int $orderNr - ordernumber we want to chek
     * @return bool - return true if a order exist, else false
     */
    public function orderExist(int $orderNr) : bool{
        $query="SELECT COUNT(order_nr) AS orderNr FROM orders WHERE order_nr = :arg0";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':arg0',$orderNr);
        $statement->execute();
        $rees = $statement->fetchAll(PDO::FETCH_COLUMN);
        //If we find the order in orders table we can confirm the match
        if($rees[0] ==1){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * This function chek if a ski exist
     * @param int $serialNr - serialNumber of the ski for chel
     * @return bool - return true if the ski exist
     */
    public function skiExist(int $serialNr): bool{
        $query="SELECT COUNT(serial_nr) AS serialNr FROM skis WHERE serial_nr = :arg0";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':arg0',$serialNr);
        $statement->execute();
        $rees = $statement->fetchAll(PDO::FETCH_COLUMN);
        //If we find the order in orders table we can confirm the match
        if($rees[0] ==1){
            return true;
        }
        else{
            return false;
        }
    }

}

