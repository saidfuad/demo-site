<?php
//phpinfo();

require('PHPMailer-master/class.phpmailer.php');
//require ('phpmailer/PHPMailerAutoload.php');
$mail = new PHPMailer(true); 
$mail->SMTPSecure = "tls";
$mail->SMTPAuth   = true;
$mail->Username   = 'rastrearnanet@rastrearna.net';
$mail->Password   = 'r4str34r@4190';
$mail_from        = 'rastrearnanet@rastrearna.net';
$subject          = 'vts test mail';
$body             = 'this is test mail from vts system';
$mail_to          = 'p.dahatonde@chateglobalservices.com';

$mail->IsSMTP(); 

try {
  $mail->Host       = "smtp.office365.com"; // SMTP server
  $mail->Port = "587"; //SMTP Port
  $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
  $mail->Debugoutput = 'html';
  $mail->AddAddress($mail_to, '');
  $mail->SetFrom($mail_from); 
  $mail->Subject = $subject;
  $mail->MsgHTML($body);
  $mail->Send();
  echo "Message Sent...<p></p>\n";
  echo "Click <a href=\"index.php\">here</a> to send another email";
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
  echo "<p>Using Username: ".$mail->Username."</p>";
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}?>