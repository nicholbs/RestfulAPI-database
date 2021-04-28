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
    public function testChangeOrderState(){   //Test som skal generere en feil
        $token = "839d6517ec104e2c70ce1da1d86b1d89c5f547b666adcdd824456c9756c7e261";
       $uri=array();
        // $uri = [('customer-rep'), ('state')];
        $uri[0]= "customer-rep";
        $uri[1] = "state";
        $requestMethod = 'PUT';
        $specificQuery = array();
        $requestBodyJson = array();
        $requestBodyJson['orderNumber'] = 1;
        $requestBodyJson['status'] = "open";
        
        $Successfull = false;
        try {
          $test = new customerRepEndpoint();
          $test ->handleRequest($uri,$specificQuery,$requestMethod,$requestBodyJson,$token); 
          $this ->tester->seeInDatabase('orders',['order_nr' =>'1','state' =>'open']);
        } catch (APIException $event) {
          $Successfull = true;
        }

        if ($Successfull == true) {
          //testen er gått igjennom og er riktig
        } else {
          throw new APIException(httpErrorConst::badRequest, "Request generated an exception");
        }
       // $this->tester->seeInDatabase('order_history',['order_nr' =>'1','state' => 'open']);


     /**
        $resArr= array();
        $emptyArr= array();
        $resArr['orderNumber'] = 1;
        $resArr['status'] = "open";

        $testobj = new  customerRepModel();
        $testobj ->changeOrderState($emptyArr, $resArr,$token);
        $this->tester->seeInDatabase('orders', ['order_nr' => '1']);
        **/
      /**
        $this->tester->seeInDatabase('orders',['order_nr' => 10,'state' => 'open']);
        $this->tester->seeInDatabase('orders', array('order_nr' => 25));
        $this ->tester->assert($this->tester->seeInDatabase('orders',['order_nr' => 10,'state' => 'open']));
       * **/
    }

    /**
     * This function tests if we sucsessfully change the orderState
     */
    public function changeOrderState(){
        $emptyArray = array();
        $requestBodyJson = array();
        $requestBodyJson['orderNumber'] = 1;
        $requestBodyJson['status'] = "open";
        $token ="839d6517ec104e2c70ce1da1d86b1d89c5f547b666adcdd824456c9756c7e261";

        //$konvert= json_encode($requestBodyJson);

        //try {
            $test = new customerRepModel();
           // $test->changeOrderState($emptyArray, $requestBodyJson, $token);
            $test->changeOrderState($emptyArray, $requestBodyJson, $token);
            //$this->tester->seeInDatabase('orders', ['order_nr' => '1', 'state' => 'open']);
        //}
        //catch (APIException $event){
          //  console.log($event);
       // }

    }
}