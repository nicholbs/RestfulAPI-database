<?php

class publicEndpointCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function tryToTest(ApiTester $I)
    {
     $I ->sendGet('/public/skis?grip=dd');
     $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
    }

    public function gripfilter(ApiTester $I){
        $I ->sendGet('/public/skis?grip=dd');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseContainsJson(array('type_id' => 1,'grip'=> 'dd'), ); //Cheks taht we get return a skitype wit id 1
    }
    public function modelfilter(ApiTester $I){
        $I ->sendGet('/public/skis?model=tester');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
       $I->seeResponseContainsJson(array('type_id' => 0,'model'=> 'tester'), ); //Cheks taht we get return a skitype with model tester
    }
    public function modelandgripfilter(ApiTester $I){
        $I ->sendGet('/public/skis?model=tester&grip=dd');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseContainsJson(array('type_id' => 0,'model'=> 'tester'), );
        $I->seeResponseContainsJson(array('type_id' => 1,'grip'=> 'dd'), ); //Cheks taht we get return a skitype wit id 1
    }



}
