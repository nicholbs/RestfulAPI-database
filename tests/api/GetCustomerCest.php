<?php

class GetCustomerCest
{
    // public function _before(ApiTester $I)
    // {
    // }

    // tests
    public function getCustomer(ApiTester $I)
    {
    // $I->haveHttpHeader('accept', 'application/json');
    $I->haveHttpHeader('Content-Type', 'application/json');
    $I->sendGet('/customer/1/order/1');
    $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
    $I->seeResponseIsJson();
    $I->seeResponseContainsJson(['order_nr' => '1', 'customer_id' => '1']);
    }
}
