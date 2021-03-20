<?php

class DeleteCustomerCest
{
    // public function _before(ApiTester $I)
    // {
    // }

    // tests
    public function deleteCustomerOrder(ApiTester $I)
    {
    // $I->haveHttpHeader('accept', 'application/json');
    $I->haveHttpHeader('Content-Type', 'application/json');
    $I->sendDelete('/customer/1/order/1');
    $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
    // $I->seeResponseIsJson();
    $I->seeResponseContains('Success');
    // $I->seeResponseContainsJson(['order_nr' => '1', 'customer_id' => '1']);
    }
}
