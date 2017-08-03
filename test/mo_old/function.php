<?php

function getLastid(){
	//get max trans_id
	$sSql = "SELECT max(trans_id) as trans_id FROM track";
	$rs = mysql_query($sSql) or die(mysql_error());
	
	$row = mysql_fetch_array($rs);
	$trans_id = $row['trans_id'] + 1;
	
	return $trans_id;
}

function final_result($message){
	die("$message");
}

function final_result_xml($theString,$bool,$SQLError) {
	$MySql_string = str_replace("'","''",$SQLError);
	die("<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n<root>\n<result>$bool</result>\n<message>$theString</message>\n</root>");
}


?>