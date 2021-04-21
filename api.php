<?php

/**
 * This file getting all the request from the webbroser, and respond to the request
 *
 * The code is inspired by Rune Hjelsvold from his api.php located on the repo: git@git.gvk.idi.ntnu.no:runehj/sample-rest-api-project.git
 */
require_once 'controller/APIException.php';
require_once 'controller/BusinessException.php';
require_once 'controller/controller.php';
header('Content-Type: application/json'); //formaterer headerene til å bli json format

//variabels:
$specificQuery= array(); // lagrer unna query objektet i json
$dividedUri = array(); //Lagre hver del av api url.

//Working with the requests:
$requestType = $_SERVER['REQUEST_METHOD']; // what metod the request is.
parse_str($_SERVER['QUERY_STRING'],$specificQuery); //save the hole query
$dividedUri = explode( '/', $specificQuery['request']); //save the path in the URI separeted with /
unset($specificQuery['request']); // removes request from specific Query.

//Working with the body of the request
$requestBody= file_get_contents('php://input'); //getting the body content
return file_get_contents('php://input');
//This code is directely copyed from Rune Hjertsvold repo:
if(strlen($requestBody)>0){
    $requestBodyJson = json_decode($requestBody,true); // converting the body to JSON object
}
else{
    $requestBodyJson = array(); //Making an empty array
}

$token = isset($_COOKIE['token']) ? $_COOKIE['token'] : 'notAuthenticated';

//if((new controller())->authentication($dividedUri,$token)){
    //sends the information to the controller
    $controller = new controller();
    try {
        //$res = $controller ->request($dividedUri,$specificQuery,$requestType,$requestBodyJson);
        $res = "rBody:\t" + $requestBody + "\n\nrBJSON:\t" + $requestBodyJson;
        echo json_encode($res); //send the respons back to frontend. Viser pr nå meldingen vi er i controller

    } catch (APIException $event) {
        http_response_code($event->getCode());
        echo json_encode(generateErrorResponseContent($event->getDetailCode(), $event->getReason(), $event->getExcept()));
    } catch (BusinessException $event) {
        http_response_code($event->getCode());
        echo json_encode(generateErrorResponseContent($event->getDetailCode(), $event->getReason(), $event->getExcept()));
    }
/*}
else{
    http_response_code(403); 
    print("\nYou are not allowed to acccess this resource, Please chek if the token provided is ok Ore somthing bad thing has happend ");
}*/





//Gammel metode, fjernes så snart vi er klar for authentisering
//sends the information to the controller
/*$controller = new controller();
$res = $controller ->request($dividedUri,$specificQuery,$requestType,$requestBodyJson);
echo json_encode($res); //send the respons back to frontend. Viser pr nå meldingen vi er i controller*/



/**
 * Generates an array holding the information to be passed to the client.
 * @param int $error_code the HTTP error code causing the error
 * @param string $reason the URI of the resource detecting the error
 * @param int $detailCode the code of the specific type of error
 * @param Throwable $e the exception that caused the error - if applicable
 * @return array an array of the form array("error-code": nn, "title": "...", "detail": "...", "reason": "...")
 * @global array ERROR_MESSAGES
 * @author Rune Hjelsvol
 */
function generateErrorResponseContent(int $error_code, string $reason, string $error): array {
    $res = array();

    $res['error_code'] = $error_code;
    $res['reason'] = $reason;
    $res['exception'] = $error;

    return $res;
}








