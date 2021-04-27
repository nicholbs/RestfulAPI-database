<?php
require_once 'db/DB.php';
//require_once 'DB.php';

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

