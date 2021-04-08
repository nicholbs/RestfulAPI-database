<?php

/**
 * This file getting all the request from the webbroser, and respond to the request
 *
 * The code is inspired by Rune Hjelsvold from his api.php located on the repo: git@git.gvk.idi.ntnu.no:runehj/sample-rest-api-project.git
 */

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

//linjen under er kommentert bort midlertidig og erstattet av if else rett under Odd 202010402
//$requestBodyJson = json_decode($requestBody,true); // converting the body to JSON object

//This code is directely copyed from Rune Hjertsvold repo, We have to give him credit:
if(strlen($requestBody)>0){
    $requestBodyJson = json_decode($requestBody,true); // converting the body to JSON object
}
else{
    $requestBodyJson = array(); //Making an empty array
}//End of copy paste

$token = isset($_COOKIE['token']) ? $_COOKIE['token'] : 'notAuthenticated';

//Ikke slett nedenunder, denne skal aktiveres så snart alle er klare for authentisering. Fungerende autentisering - Odd
//procede with the request
if((new controller())->authentication($dividedUri,$token)){
    //print("\nVi er autnetisert \n");

    //sends the information to the controller
    $controller = new controller();
    $res = $controller ->request($dividedUri,$specificQuery,$requestType,$requestBodyJson);
    echo json_encode($res); //send the respons back to frontend. Viser pr nå meldingen vi er i controller
}
else{
    http_response_code(403); //sets the responseheader to 404
    print("\nYou are not allowed to acccess this resource, Please chek if the token provided is ok Ore somthing bad thing has happend ");
}





//Gammel metode, fjernes så snart vi er klar for authentisering
//sends the information to the controller
/*$controller = new controller();
$res = $controller ->request($dividedUri,$specificQuery,$requestType,$requestBodyJson);
echo json_encode($res); //send the respons back to frontend. Viser pr nå meldingen vi er i controller*/











