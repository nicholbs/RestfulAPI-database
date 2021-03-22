<?php

class UpdateShipmentCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function deleteCustomerOrder(ApiTester $I)
    {
    // $I->haveHttpHeader('accept', 'application/json');
    $I->haveHttpHeader('Content-Type', 'application/json');
    $I->sendDelete('/shipment/1/state-to-shipped');
    $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
    // $I->seeResponseIsJson();
    $I->seeResponseContains('Success');
    $I->seeInDatabase('shipments', ['shipment_nr' => '1', 'state' => 'picked-up']);
    // $I->seeResponseContainsJson(['order_nr' => '1', 'customer_id' => '1']);
    }
}
