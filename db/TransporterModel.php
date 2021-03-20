<?php
//require_once 'RESTConstants.php';
// require_once 'db/OrderModel.php';

class TransporterModel extends DB
{
    // retrieve an order
    public function retrieveCustomerOrder($shipmentNr)
    {
        $stmt = $this ->db ->prepare('UPDATE `shipments` SET `state`="picked-up" WHERE `shipment_nr` = ?');
        $stmt->execute([$shipmentNr]);
        
        // $res =$stmt->fetchAll();
        return "Success";
    }
  
}