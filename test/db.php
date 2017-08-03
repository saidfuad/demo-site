<?php

	define("DIHOST", "localhost");
	define("DIUSER", "root");
	define("DIPASS", "");
	define("DIDB", "trackeron_itms");
	
	$conn = @mysql_connect(DIHOST,DIUSER,DIPASS) or die('{"success":false,"errors":[{"msg": Connection Error: "'.mysql_error().'"}]}');
	mysql_select_db(DIDB,$conn);
	
	// Setting the Timezone to Asia/Calcutta, for storing dates and time in Indian format.
	putenv("TZ=Asia/Calcutta");
	// date_default_timezone_set ( "Asia/Calcutta" );
	
	define("DATE_TIME", "Y-m-d H:i:s");
	define("DATE_TIME_TRANS", "YmdHis");
	define("DATE", "Y-m-d");
	define("TIME", "h:i A");
	define("DISP_DATE", "d.m.Y");
	define("DISP_TIME", "d.m.Y h:i:s A");
	define("DUMP_BKUP_DAYS",30);
	define("MAX_LOGIN_TRY",3);
	function ago($time)
{
   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
   $lengths = array("60","60","24","7","4.35","12","10");

   $now = time();
	   $time = strtotime($time);
       $difference     = $now - $time;
       $tense         = "ago";

   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
       $difference /= $lengths[$j];
   }

   $difference = round($difference);

   if($difference != 1) {
       $periods[$j].= "s";
   }

   return "$difference $periods[$j]";
} 
	function generateMSG($id, $msg, $result=false) {
		if($result == false) {
			$data["result"] = $result;
			$data["eid"] = $id;
			$data["error"] = $msg;
		}
		else {
			$data["result"] = "true";
			$data["msg"] = $msg;
		}
		die(json_encode($data));
	}
?>