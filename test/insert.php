<?php
	header('Content-type: text/xml');
	
	require_once("db.php"); 
	require_once("function.php"); 
	
	$trans_id = getLastid();			
	$objDOM   = new DOMDocument();
	
	//data by xml
	$xml = stripslashes($_REQUEST["xml"]);
	
	if(trim($xml) != ""){ 
		if(!$objDOM->loadXML($xml)) {
			final_result_xml("XML Parsing Failed","false","");
		}
		$nodes = $objDOM->getElementsByTagName("sensor");
		
		foreach( $nodes as $node ){
			$sensor = $node->getAttribute("id");
			if(trim($sensor) == '' ){
				final_result_xml("Invalid Data Provided","false","");
			}
			foreach($node->childNodes as $child) {
				$param = $child->nodeName;
				$value = $child->nodeValue;
				$values[] = "('$sensor', '$param', '$value', '$trans_id')";
				
			}
		}
		
		$values = implode(",", $values);
		$sql = "INSERT INTO track (`sensor_id`, `parameter`, `values`, `trans_id`) VALUES ".$values;
		// $rs = mysql_query($sql) or final_result_xml(mysql_error(),"false","");
		final_result_xml("$sql","true","");
	}
	else {
		final_result_xml("XML Not Provided","true","");
	}
?>
