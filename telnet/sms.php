<?php

	function send_sms($mobile, $smsText, $template=0, $template_data = NULL) {

		if(trim($mobile) == '') return '';
		$mobile = explode(",", $mobile);
		$user = "61498988348";
		$pass = "123qwaszX";
		$mobNew = array();
		foreach($mobile as $mob){
			if(strlen($mob) >= 9)
			$mobNew[] = trim($mob);
		}
		$mob = implode(",", $mobNew);
		// &mobileID=$mobileID&password=$password&to=$to&text=$text&from=$mobileID
		$url  = "http://api.aussiesms.com.au/?sendsms&msg_type=SMS_TEXT";
		$url .= "&mobileID=" . urlencode($user);
		$url .= "&password=" . urlencode($pass);
		$url .= "&to=" . urlencode($mob);
		$url .= "&text=" . urlencode($smsText);
		$url .= "&from=" . urlencode('iHound');
		
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		curl_close($ch); 		
		
		return htmlspecialchars($output);		
	}
	
$mobile = '61498988348';
$smsText = 'Take 3 from Kunal, for iHound testing';

$ret = send_sms($mobile, $smsText);

print_r($ret);
