<?php
require_once 'db/TransporterModel.php';

class TransporterEndpoint
{
    public function handleRequest($uri, $requestType, $requestBody)
    {
        $lengde = count($uri);
        if ($lengde == 2 && $requestType == "GET") {
            $this->validateURI($uri);
            $arr = array('shipping_address', 'scheduled_pickup', 'transporter', 'driver_id');
            $this->validateBody($requestBody, $arr);
            return $this->updateShipmentStatus($uri[1], $requestBody);
        } else {
            throw new BusinessException(httpErrorConst::badRequest, "Request is not valid for the designated endpoint");
        }
        
    
    }
    // get 4 week product plan
    private function updateShipmentStatus($order_nr, $requestBody)
    {
        return (new TransporterModel())->updateShipment($order_nr, $requestBody);
        
    }
    private function validateURI(array $uri) {
        if (!is_numeric($uri[1])) {
            throw new BusinessException(400, "Endpoint expected customer id to be a number");
        }; 
    }
    private function validateBody($requestBody, array $arr) {
       
        foreach ($arr as &$value) {
            
            if (!array_key_exists($value, $requestBody)) {
                $reason = "Request was not processed due to missing the value: ";
                $reason .= $value;
                throw new BusinessException(400, $reason);
            } 
        }
    }
}