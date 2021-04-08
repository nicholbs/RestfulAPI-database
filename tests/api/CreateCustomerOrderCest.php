<?php
const TOKEN_STOREKEEPER = "e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855";   // Belongs to Didrik Disk in employee table
const TOKEN_PLANNER = "022224c9a11805494a77796d671bec4c5bae495af78e906694018dbbc39bf2cd";       // Belongs to Njalle Nøysom in employees table
const TOKEN_REP = "839d6517ec104e2c70ce1da1d86b1d89c5f547b666adcdd824456c9756c7e261";           // Belongs to Sylvester Sølvtunge in employees
const TOKEN_CUSTOMER = "2927ebdf56c20cbb90fbd85cac5be30d60e3dfb9f9c9eda869d0fdce36043a85";      // Belongs to Lars Monsen in customer table

class CreateCustomerOrderCest
{
    // public function _before(ApiTester $I)
    // {
    // }

    // tests



    public function createCustomerOrder(ApiTester $I)
    {
    $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_CUSTOMER);
    $I->getClient()->getCookieJar()->set($cookie);
    // $I->haveHttpHeader('accept', 'application/json');
    $I->haveHttpHeader('Content-Type', 'application/json');
    $I->sendPost('/customer/1/order/1');
    $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
    // $I->seeResponseIsJson();
    $I->seeResponseContains('Success');
    $I->seeInDatabase('orders', ['order_nr' => '6']);
    // $I->seeInDatabase('orders', ['customer_id' => '1', 'price' => '70']);
    }
}
