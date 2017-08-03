 <?php 
 class Sendmail
 {
   function send_mail($emeil,$subj,$message){    
        $config = Array(
      'protocol' => 'smtp',
      'smtp_host' => 'smtp-pulse.com',
      'smtp_port' => 2525,
      'smtp_user' => 'app@raindrops.co.ke', // change it to yours
      'smtp_pass' => '8tK4JQSEset', // change it to yours
      'mailtype' => 'html',
      'charset' => 'iso-8859-1',
      'wordwrap' => TRUE
    );

    $CI =& get_instance();
    
        // $this->load->library('smtpapi');
         //      $this->load->library('emailsend');
          $CI->load->library('email', $config);
          $CI->email->set_newline("\r\n");
          $CI->email->from('app@raindrops.co.ke', 'HAWK System'); // change it to yours
          $CI->email->to($emeil);// change it to yours
          $CI->email->subject($subj);
          $CI->email->message($message);
          $check = $CI->email->send();
          return $check;
    }

 }