<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Emailsend {

    function send_email_message($to, $subj, $message) {
        $ci = & get_instance();
        $ci->load->library('./PHPMailer/smtp');
        $ci->load->library('./PHPMailer/phpmailer');

        $mail = new PHPMailer();
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'html';
        //Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        //Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = 'tls';
        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;
        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = "itmslive16@gmail.com";
        //Password to use for SMTP authentication
        $mail->Password = "itms@2016*";

        //$mail = new PHPMailer;
        $mail->setFrom('itmslive16@gmail.com', 'ITMS Live ');
        //$mail->addAddress('makaweys@gmail.com', 'My Friend');
        foreach ($to as $key => $email) {
            $mail->addAddress($email); //Recipient name is optional
        }

        $mail->IsHTML(true);

        $mail->Subject = $subj;
        $mail->Body = $message;

        $mail_sent = false;

        if ($mail->Send()) {

            $mail_sent = true;
        }

        return $mail_sent;
    }

    function send_email_report($to, $subj, $message, $attachment) {

        $ci = & get_instance();
        $ci->load->library('./PHPMailer/smtp');
        $ci->load->library('./PHPMailer/phpmailer');
        $mail = new PHPMailer();
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'html';
        //Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        //Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = 'tls';
        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;
        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = "itmsreports16@gmail.com";
        //Password to use for SMTP authentication
        $mail->Password = "itmsreports16*";

        //$mail = new PHPMailer;
        $mail->setFrom('itmsreports16@gmail.com', 'ITMS Reports');
        //$mail->addAddress('makaweys@gmail.com', 'My Friend');
        //foreach ($to as $key => $email) {
        $mail->addAddress($to); //Recipient name is optional
        //}
        if ($attachment != "blank") {
            $mail->addAttachment($attachment);
        }
        $mail->IsHTML(false);

        $mail->Subject = $subj;
        $mail->Body = $message;

        $mail_sent = false;

        if ($mail->Send()) {

            $mail_sent = true;
        }

        return $mail_sent;
    }

}