<?php
const TOKEN_CUSTOMERRep = "839d6517ec104e2c70ce1da1d86b1d89c5f547b666adcdd824456c9756c7e261";

class customerRepCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function tryToTest(ApiTester $I)
    {
    }

    /**
     * This function chekt tath filter with status new works
     */
    public function orderFilterNew(ApiTester $I){
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_CUSTOMERRep );
        $I->getClient()->getCookieJar()->set($cookie);
        $I ->sendGet('customer-rep/orders?status=new');
       // $I ->seeResponseContains('{"2":{"price_total":"58500","orderstate":"new","skis":{"2":{"type_id":"2","model":"Redline","temperature":"warm","grip":"Grippers","size":"167","weight_class":"40-50","ski_quantity":"50"}}},"6":{"price_total":"5330","orderstate":"new","skis":{"1":{"type_id":"1","model":"Active Pro","temperature":"cold","grip":"IntelliWax","size":"182","weight_class":"50-60","ski_quantity":"2"},"2":{"type_id":"2","model":"Redline","temperature":"warm","grip":"Grippers","size":"167","weight_class":"40-50","ski_quantity":"1"}}}}');
        $I->seeResponseContains('{"1":{"price_total":"208000","orderstate":"new"');
    }

    /**
     * This filter is status=ski-available and chek taht we get a valid response
     */
    public function  orderfilterSkisAvaiable(ApiTester $I){
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_CUSTOMERRep );
        $I->getClient()->getCookieJar()->set($cookie);
        $I ->sendGet('customer-rep/orders?status=skis-available');
        $I ->seeResponseContains('{"4":{"price_total":"7200","orderstate":"skis-available",');

    }

    /**
     * This filter cheks tath we can filter on multiple status
     */
    public function orderFilterAny(ApiTester $I){
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_CUSTOMERRep );
        $I->getClient()->getCookieJar()->set($cookie);
        $I ->sendGet('customer-rep/orders?status=new,skis-available');
        $I -> seeResponseIsJson('{"4":{"price_total":"7200","orderstate":"skis-available",');
        $I->seeResponseIsJson('{"2":{"price_total":"58500","orderstate":"new"');
    }

    /**
     * This function chek a message if there is a missspell in the filter name.
     */
    public function  orderFilerNotFound(ApiTester $I){
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_CUSTOMERRep );
        $I->getClient()->getCookieJar()->set($cookie);
        $I ->sendGet('customer-rep/orders?fail=new');
        $I->seeResponseCodeIs(404);

    }

    /**
     * This function cheks thath we get a message if there is no skiis found in a filter
     */
    public  function  orderFilterNoSkiis(ApiTester $I)
    {
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_CUSTOMERRep);
        $I->getClient()->getCookieJar()->set($cookie);
        $I->sendGet('customer-rep/orders?status=nomatch'); //serch for a filtervalue thath dosent match any skis
        $I->seeResponseIsJson(array('message' => 'There wasent any order matching your filtertype')); //cheks the respons
    }

    /**
     * This test chek tath we can sucesfully set a order from new to open
     */
    public function changeOrderStateFromNewToOpen(ApiTester $I){
        //bulding a body
        $reqBody = array();
        $reqBody['orderNumber'] = 1;
        $reqBody['status']= 'open';
        $coded = json_encode($reqBody); //encode the body to json
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_CUSTOMERRep);
        $I->getClient()->getCookieJar()->set($cookie);
       // $I ->sendPut('customer-rep/state',['orderNumber' => 1,'status' =>'open']);
        $I ->sendPut('customer-rep/state',$coded); //Send the request
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson(array('status' => 'ok')); //cheks the respons
    }
    //}
    /**
     * This test chek tath we get a proper arror message if we try to change a order ilegal status
     */
    public function changeOrderStateIllegal(ApiTester $I){
        //bulding a body
        $reqBody = array();
        $reqBody['orderNumber'] = 4;
        $reqBody['status']= 'open';
        $coded = json_encode($reqBody); //encode the body to json
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_CUSTOMERRep);
        $I->getClient()->getCookieJar()->set($cookie);
        $I ->sendPut('customer-rep/state',$coded); //Send the request
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson(array('reason' => 'Current orderstate is: skis-available you can only change from order state new to orderstate open')); //cheks the respons


    }


}
