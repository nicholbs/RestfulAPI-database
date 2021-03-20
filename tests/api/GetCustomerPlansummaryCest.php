<?php

class getCustomerPlansummaryCest
{
    // public function _before(ApiTester $I)
    // {
    // }

    // tests
    public function getCustomerPlanSummary(ApiTester $I)
    {
    // $I->haveHttpHeader('accept', 'application/json');
    $I->haveHttpHeader('Content-Type', 'application/json');
    $I->sendGet('/customer/plansummary');
    $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
    $I->seeResponseIsJson();
    $I->seeResponseContainsJson(['day' => '2021-03-19', 'day' => '2021-02-28']);
    }
    
}
