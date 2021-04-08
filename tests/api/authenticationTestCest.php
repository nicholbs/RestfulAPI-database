<?php

const TOKEN_STOREKEEPER = "e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855";   // Belongs to Didrik Disk in employee table
const TOKEN_PLANNER = "022224c9a11805494a77796d671bec4c5bae495af78e906694018dbbc39bf2cd";       // Belongs to Njalle Nøysom in employees table
const TOKEN_REP = "839d6517ec104e2c70ce1da1d86b1d89c5f547b666adcdd824456c9756c7e261";           // Belongs to Sylvester Sølvtunge in employees
const TOKEN_CUSTOMER = "2927ebdf56c20cbb90fbd85cac5be30d60e3dfb9f9c9eda869d0fdce36043a85";      // Belongs to Lars Monsen in customer table
class authenticationTestCest
    /**
     * The code:
     $cookie = new Symfony\Component\BrowserKit\Cookie('auth_token', 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855');
      $I->getClient()->getCookieJar()->set($cookie);
      is taken from Rune Hjertsvol
     *
     */

{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function _after(ApiTester $I)
    {
    }

    /**
     * This function tests if we sucessfully can access the storekeeper api with a storekeeper token
     */
    public function storekeeperAuth(ApiTester $I){
       // $cookie = new Symfony\Component\BrowserKit\Cookie('auth_token', 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855');
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_STOREKEEPER );
        $I->getClient()->getCookieJar()->set($cookie);
        $I->sendGet('/storekeeper/orders');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
    }

    /**
     * This function test if we get an errorcode 403 when trying ta acess an endpoint with a wrong token . In this case a production-planner token
     */
    public function StorekeeperUnauthorized(ApiTester $I){
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_PLANNER);
        $I->getClient()->getCookieJar()->set($cookie);
        $I->sendGet('/storekeeper/orders');
        $I->seeResponseCodeIs(403);
    }
    /**
     * This function tests if we can access the /orders api with a customer token
     */
    public  function ordersAuth(ApiTester $I){
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_CUSTOMER);
        $I->getClient()->getCookieJar()->set($cookie);
        $I->sendGet('/orders');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
    }
    /**
     * This function tests thath we are not autorized to acess the endpoint from a production-planner
     */
    public function ordersUnauthorized(ApiTester $I){
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_PLANNER);
        $I->getClient()->getCookieJar()->set($cookie);
        $I->sendGet('/storekeeper/orders');
        $I->seeResponseCodeIs(403);
    }
    /**
     * This function tests if we can access the customer api with a customer token
     */
    public  function customersAuth(ApiTester $I){
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_CUSTOMER);
        $I->getClient()->getCookieJar()->set($cookie);
        $I->sendGet('/customer');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
    }
    /**
     * This function tests thath we are not autorized to acess the endpoint from a production-planner
     */
    public function customerUnauthorized(ApiTester $I){
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_PLANNER);
        $I->getClient()->getCookieJar()->set($cookie);
        $I->sendGet('/customer');
        $I->seeResponseCodeIs(403);
    }
    /**
     * This function tests if we can access the shipment api with a storekeeper token
     */
    public  function shipmentAuth(ApiTester $I){
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_STOREKEEPER);
        $I->getClient()->getCookieJar()->set($cookie);
        $I->sendGet('/shipment');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200

    }
    /**
     * This function tests thath we are not autorized to acess the endpoint from a production-planner
     */
    public function shipmentUnauthorized(ApiTester $I){
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_PLANNER);
        $I->getClient()->getCookieJar()->set($cookie);
        $I->sendGet('/customer');
        $I->seeResponseCodeIs(403);
    }

}
