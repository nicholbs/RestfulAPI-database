<?php

require_once 'controller\StorekeeperEndpoint.php';

class StorekeeperUnitTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testRetrieveOrders()
    {
        $uri = [('storekeeper'), ('orders')];
        $requestMethod = 'GET';
        $specificQuery = array();
        $payload = array();

        $endpoint = new StorekeeperEndpoint();
        $res = $endpoint->handleRequest($uri, $specificQuery, $requestMethod, $payload);

        $this->tester->assertCount(5, $res);
        $resource = $res[2];
        $this->tester->assertEquals('3', $resource['order_nr']);
        $this->tester->assertEquals('3', $resource['ski_type']);
        $this->tester->assertEquals('30', $resource['ski_quantity']);
        $this->tester->assertEquals('32175', $resource['price']);
        $this->tester->assertEquals('open', $resource['state']);
        $this->tester->assertEquals('3', $resource['customer_id']);
        $this->tester->assertEquals('2021-03-19', $resource['date_placed']);
    }
}