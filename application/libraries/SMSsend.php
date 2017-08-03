<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Be sure to include the file you've just downloaded
require_once('AfricasTalkingGateway.php');

class SMSsend {

    function send_text_message ($recipients, $message) {
        // Specify your login credentials
        $username   = "fahmy";
        $apikey     = "867936733d4f732c6ed0b87b30a10c5c72694a17a7636d0fb64957f06cc0b00b";
        // Create a new instance of our awesome gateway class
        $gateway    = new AfricasTalkingGateway($username, $apikey);
        // NOTE: If connecting to the sandbox, please add the sandbox flag to the constructor:
        /*************************************************************************************
                     ****SANDBOX****
        $gateway    = new AfricasTalkingGateway($username, $apiKey, "sandbox");
        **************************************************************************************/
        // Any gateway error will be captured by our custom Exception class below, 
        // so wrap the call in a try-catch block
        try 
        { 
            // Thats it, hit send and we'll take care of the rest. 
            $results =  $gateway->sendMessage($recipients, $message);
            return $results;
        }
        catch ( AfricasTalkingGatewayException $e )
        {
          return "Encountered an error while sending: ".$e->getMessage();
        }
        
        
/*         $ci =& get_instance();
        $ci->load->library('africastalkinggateway');

        $phones = array();
        foreach ($reciever as $k=>$phone) {
            array_push($phones , $phone);
        }


        $reciever = implode(',', array_unique($phones));
        $result = false;

        ///$Obj = new AfricasTalkingGateway();
        $res = $ci->africastalkinggateway->sendMessage($reciever, $message);
        print_r($res);
  echo "reciever $reciever message $message res $res<br/>";
        $ress = 0;
        foreach ($res as $key => $value) {
            $result = $value->status;
            if ($result=='Success') {
                $ress++;    
            }
        }

       
        return $result;*/
   }
}


