<?php
require_once "PDOTesting.php";
require_once "db/dbCredentials.php";

class CustomerTableTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
      /**
     * @var \PDODemo
     */
    protected $pdoDemo;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testUpdateShipment()
    {
        $pdoDemo = new PDOTesting();
        $pdoDemo->runUpdateShipment("1");
        $this->tester->seeInDatabase('shipments', ['shipment_nr' => '1', 'state' => 'picked-up']);
    }

}