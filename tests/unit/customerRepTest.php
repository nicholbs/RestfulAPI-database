<?php
require_once 'controller\customerRepEndpoint.php';
require_once 'controller/APIException.php';
require_once 'controller/BusinessException.php';
require_once 'constants.php';
require_once 'db/customerRepModel.php';
// require_once 'controller/api '
class customerRepTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testSomeFeature()
    {

    }

    public function testChangeOrderState()
    {   //Test som skal generere en feil

        /**
         * $resArr= array();
         * $emptyArr= array();
         * $resArr['orderNumber'] = 1;
         * $resArr['status'] = "open";
         *
         * $testobj = new  customerRepModel();
         * $testobj ->changeOrderState($emptyArr, $resArr,$token);
         * $this->tester->seeInDatabase('orders', ['order_nr' => '1']);
         **/
        /**
         * $this->tester->seeInDatabase('orders',['order_nr' => 10,'state' => 'open']);
         * $this->tester->seeInDatabase('orders', array('order_nr' => 25));
         * $this ->tester->assert($this->tester->seeInDatabase('orders',['order_nr' => 10,'state' => 'open']));
         * **/
    }

    /**
     * This function tests if we sucsessfully change the orderState
     */
    public function changeOrderState()
    {
        $emptyArray = array();
        $requestBodyJson = array();
        $requestBodyJson['orderNumber'] = 1;
        $requestBodyJson['status'] = "open";
        $token = "839d6517ec104e2c70ce1da1d86b1d89c5f547b666adcdd824456c9756c7e261";

        $test = new customerRepModel();
        $test->changeOrderState($emptyArray, $requestBodyJson, $token);
        $this->tester->seeInDatabase('orders', ['order_nr' => '1', 'state' => 'open']);


    }

    /**
     * This function chek tath you get an error message if a user try to change with an nonexiting order.
     */
    public function testchangeOrderStateIlegal()
    {
        $emptyArray = array();
        $requestBodyJson = array();
        $requestBodyJson['orderNumber'] = 100;
        $requestBodyJson['status'] = "open";
        $token = "839d6517ec104e2c70ce1da1d86b1d89c5f547b666adcdd824456c9756c7e261";

        $tes = false;
        //because of busniess logick, we want this to faild and send a expetion error instead.
        try {
            $test = new customerRepModel();
            $test->changeOrderState($emptyArray, $requestBodyJson, $token);
            //$this->tester->seeInDatabase('orders', ['order_nr' => '1', 'state' => 'open']);

            //If the test failed with an expetin, we are happy.
        } catch (BusinessException $l) {
            $tes = true;

        }
    if($tes ==false){
        throw new  BusinessException("33","sdsdsdds");
    }
    }
}