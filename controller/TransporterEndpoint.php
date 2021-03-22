<?php
//require_once 'RESTConstants.php';
// require_once 'db/StorekeeperModel.php';
require_once 'db/TransporterModel.php';

class TransporterEndpoint
{
    public function handleRequest($uri,$specificQuery,$requestType)
    {
        return $this->updateShipmentStatus($uri[1]);
    
    }
    // get 4 week product plan
    private function updateShipmentStatus($shipmentNr)
    {
        return (new TransporterModel())->retrieveCustomerOrder($shipmentNr);
        
    }

  



}