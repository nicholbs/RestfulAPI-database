<?php
echo("\n Info: ");
header('Content-Type: application/json'); //formaterer headerene til å bli json format

//variabels:
$specificQuery= array(); // lagrer unna query objektet i json
$dividedQuery = array(); //Lagre hver del av api url.

//Working with the requests:
$requestType = $_SERVER['REQUEST_METHOD']; // what metod the request is.
parse_str($_SERVER['QUERY_STRING'],$specificQuery); //save the hole query
$dividedQuery = explode( '/', $specificQuery['request']); //save the path in the URI separeted with /

//Working with the body of the request
$requestBody= file_get_contents('php://input'); //getting the body content
$requestBodyJson = json_decode($requestBody,true); // converting the body to JSON object





//Testing the server:
echo ("Her sjekker vi de forskjellige requestene: \n");
echo("\n requestType: $requestType \n");
echo("\n The URI: ");
print_r($specificQuery);
echo ("\n The divided uri into parts: ");
print_r($dividedQuery);
echo("\n Body content: \n");
print_r($requestBodyJson);

echo("\nHer ser vi rådata motatt i api.php filen: \n \n");
print_r($_SERVER); //leser ut all info fra requeste









