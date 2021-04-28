<?php
const TOKEN_STOREKEEPER = "e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855";
class storekeeperTransitionCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function tryToTest(ApiTester $I)
    {
    }
    /**
     * This test updates a transition record
     */
    public  function transitionRecordSucess(ApiTester $I){
        //bulding a body
        $reqBody = array();
        $reqBody['orderNumber'] = 1;
        $reqBody['serialNr']= 5;
        $coded = json_encode($reqBody); //encode the body to json
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_STOREKEEPER);
        $I->getClient()->getCookieJar()->set($cookie);
        $I ->sendPut('storekeeper/transitionrecord',$coded); //Send the request
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson(array('status' => 'ok')); //cheks the respons
    }
    /**
     * This test trys to update a transition record with a nonexisting orderNumber
     */
    public  function  transitionRecordNonExistingOrderNumber(ApiTester $I){
        //bulding a body
        $reqBody = array();
        $reqBody['orderNumber'] = 100;
        $reqBody['serialNr']= 5;
        $coded = json_encode($reqBody); //encode the body to json
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_STOREKEEPER);
        $I->getClient()->getCookieJar()->set($cookie);
        $I ->sendPut('storekeeper/transitionrecord',$coded); //Send the request
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson(array('reason' => 'Ordernumber: 100 Dosent exist"')); //cheks the respons
    }
    /**
     * This test try to update a transition record with a nonexisting ski
     */
    public function transitionRecordNonExistingSki(ApiTester $I){
        //bulding a body
        $reqBody = array();
        $reqBody['orderNumber'] = 1;
        $reqBody['serialNr']= 100;
        $coded = json_encode($reqBody); //encode the body to json
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_STOREKEEPER);
        $I->getClient()->getCookieJar()->set($cookie);
        $I ->sendPut('storekeeper/transitionrecord',$coded); //Send the request
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson(array('reason' => 'Ski with the serialnumber: 100 dosent exis')); //cheks the respons
    }

}
