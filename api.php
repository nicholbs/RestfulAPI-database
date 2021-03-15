<?php
echo("hei");
print ("\nmiddag");
header('Content-Type: application/json'); //formaterer headerene til å bli json format
print_r($_SERVER); //leser utt all info fra requestem
//echo ($_SERVER);
//echo $_SERVER['REQUEST_METHOD'];

echo ("\n begynner å porssesere \n");
$typeRequest = $_SERVER['REQUEST_METHOD']; //Henter ut type request
echo  ("\n Type request er $typeRequest \n");

$requestArry = array(); //brukes til å lagre unna delene av requesten hvis det sendes som en query strng

parse_str($_SERVER['QUERY_STRING'],$requestArry); // Finner innholdet i skuffen QUERY_STRING og lagrer unna dataene i en array

$requestArryDeltOpp=  explode( '/', $requestArry['request']); // lagrer hvert skille med / i en egen skuff

echo("\nSkriver ut splittet request uri \n");
print_r($requestArryDeltOpp); //srkvier ut hver skuff av request

//Leker litt med return;


