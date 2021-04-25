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

//This code is directely copyed from Rune Hjertsvold repo:
if(strlen($requestBody)>0){
    $requestBody = json_decode($requestBody,true); // converting the body to JSON object
}
else{
    $requestBody = array(); //Making an empty array
}

$token = isset($_COOKIE['token']) ? $_COOKIE['token'] : 'notAuthenticated';

try {
    if((new controller())->authentication($dividedUri,$token)){
        //sends the information to the controller
        $controller = new controller();
        $res = $controller->request($dividedUri,$specificQuery,$requestType,$requestBody);
        echo json_encode($res); //send the respons back to frontend
    }
    else{
        http_response_code(403); 
        print("\nYou are not allowed to acccess this resource, please check if the token provided is valid");
    } 
} 
/**
 * Handles APIExceptions thrown throughout each transaction
 * 
 * @param APIException $event - the generated exeption 
 * @author Nicholas Bodvin Sellevåg 
 */
catch (APIException $event) {
    http_response_code($event->getCode());
    echo json_encode(generateErrorResponseContent($event->getDetailCode(), $event->getReason(), $event->getExcept()));
} 
/**
 * Handles BusinessExceptions thrown throughout each transaction
 * 
 * @param BusinessException $event - the generated exeption 
 * @author Nicholas Bodvin Sellevåg 
 */
catch (BusinessException $event) {
    http_response_code($event->getCode());
    echo json_encode(generateErrorResponseContent($event->getDetailCode(), $event->getReason(), $event->getExcept()));
} 
/**
 * Handles errors generated from using methods through the PDO object
 * 
 * @param PDOException $event - the generated exeption 
 * @author Nicholas Bodvin Sellevåg 
 */
catch (PDOException $event) {
    http_response_code(httpErrorConst::serverError);
    echo json_encode(generateErrorResponseContent($event->getCode(), $event->getMessage(), "PDOException"));
}

/**
 * Generates an array holding the information to be passed to the client.
 * @param int $error_code the HTTP error code causing the error
 * @param string $reason the URI of the resource detecting the error
 * @param string $error declares what type of error was generated
 * @author Rune Hjelsvol
 */
function generateErrorResponseContent($error_code, string $reason, string $error): array {
    $res = array();

    $res['error_code'] = $error_code;
    $res['reason'] = $reason;
    $res['exception'] = $error;

    return $res;
}








