<?php

class authenticationTestCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function tryToTest(ApiTester $I)
    {
    }

    public function storekeeperAuth(ApiTester $I){
        $cookie = new Symfony\Component\BrowserKit\Cookie('auth_token', 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855');
        $I->getClient()->getCookieJar()->set($cookie);
        $I->sendGet('/storekeeper/orders');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200


}
}
