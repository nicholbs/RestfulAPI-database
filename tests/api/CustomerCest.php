<?php

const TOKEN_CUSTOMER = "2927ebdf56c20cbb90fbd85cac5be30d60e3dfb9f9c9eda869d0fdce36043a85";      // Belongs to Lars Monsen in customer table

class CustomerCest
{
    public function _before(ApiTester $I)
    {
    }

    // Tests
    public function getOrder(ApiTester $I)
    {
        //Set cookie
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_CUSTOMER);
        $I->getClient()->getCookieJar()->set($cookie);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/customer/2/order/6');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseContainsJson(["order_nr" => "6", "name" => "Snowy Plains Inc.", "total" => "5330.00"]);
    }

    public function createOrder(ApiTester $I)
    {
        //Order body
        $reqBody = array();
        $reqBody['customer'] = 2;
        $reqBody['skis'] = array();
        
        //Suborder body
        $row = array();
        $row['type'] = 2;
        $row['quantity'] = 4;
        array_push($reqBody['skis'], $row);
        $row['type'] = 3;
        $row['quantity'] = 5;
        array_push($reqBody['skis'], $row);

        //Encode to JSON
        $jsonBody = json_encode($reqBody); //encode the body to json

        //Set cookie
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_CUSTOMER);
        $I->getClient()->getCookieJar()->set($cookie);

        $I->sendPost('customer/2/order',$jsonBody); //Send the request
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/customer/2/order/7');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["buying_price" => "0.65", "total" => "10042.50", "name" => "Snowy Plains Inc."]);
    }

    public function deleteOrder(ApiTester $I)
    {
        //Set cookie
        $cookie = new Symfony\Component\BrowserKit\Cookie('token', TOKEN_CUSTOMER);
        $I->getClient()->getCookieJar()->set($cookie);
        $I->haveHttpHeader('Content-Type', 'application/json');

        $I->sendGet('/customer/2/order/2');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200

        $I->sendDelete('/customer/2/order/2');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200

        $I->sendGet('/customer/2/order/2');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NOT_FOUND); // 404
    }
}
