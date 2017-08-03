<?php

function send_sms($mobile, $smsText, $template=0, $template_data = NULL) {
  
  include_once 'human_gateway_client_api/HumanClientMain.php';

  $account = "rastrear.nanet";
  //  $password= "ktomzWWv";
  $password = '2WpMHB9tAW';

  $body = $smsText;
  $to   = $mobile;
  
  $mobiles = explode(",", $mobile);

  $msgId = '00' . rand(11,100);
  
  $callbackOption = HumanSimpleSend::CALLBACK_INACTIVE;

  $sender = new HumanSimpleSend($account, $password);
  $message = new HumanSimpleMessage();
  $message->setBody($body);
  $message->setTo($to);
  $message->setMsgId($msgId);
  var_dump($sender);
  var_dump($message);

  $response = $sender->sendMessage($message, $callbackOption);
  var_dump($response->getCode());
}

$smsText = 'Take 2 from Kunal';
$mobile = '+558488021383';
send_sms($mobile, $smsText);