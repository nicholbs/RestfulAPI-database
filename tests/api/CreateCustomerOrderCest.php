<?php

class CreateCustomerOrderCest
{
    // public function _before(ApiTester $I)
    // {
    // }

    // tests
    public function createCustomerOrder(ApiTester $I)
    {
    // $I->haveHttpHeader('accept', 'application/json');
    $I->haveHttpHeader('Content-Type', 'application/json');
    $I->sendPost('/customer/1/order/1');
    $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
    // $I->seeResponseIsJson();
    $I->seeResponseContains('Success');
    $I->seeInDatabase('orders', ['order_nr' => '1', 'customer_id' => '1']);
    }
}
