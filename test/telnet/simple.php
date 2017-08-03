<?php

ini_set('display_errors', "on");
//ini_set('error_reporting', E_ALL & ~E_NOTICE);
ini_set('error_reporting', E_ALL);
include_once 'human_gateway_client_api/HumanClientMain.php';

$account = "rastrearnanet.api";
$password="wjgEFUiW9i";
$sender = new HumanSimpleSend($account, $password);
$message = new HumanSimpleMessage("Take 3 from kunal", "558488021383", "_hide", "ID0091");
$response = $sender->sendMessage($message);
$statusEnvio = $response->getCode() . " - " . $response->getMessage();

echo "Mensagem enviada \n Status envio $statusEnvio \n";
$response = $sender->queryStatus("ID0091");

echo "\nConsultando status da mensagem de id 0001  \n";
echo $response->getCode() . " - " . $response->getMessage() . "<br />";

