<?php
require_once 'db/dbCredentials.php';

/**
 * Class DB root for model - and other - classes needing access to the database
 */
class PDOTesting
{
    /**
     * @var PDO
     */
    protected $db;

    public function __construct()
    {
       $this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8',
                DB_USER, DB_PWD,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }

    public function runUpdateShipment($shipmentNr)
    {
        $stmt = $this->db->prepare('UPDATE `shipments` SET `state`="picked-up" WHERE `shipment_nr` = ?');
        $stmt->execute([$shipmentNr]);
    }
}


