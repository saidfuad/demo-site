<?php include("db.php"); ?>
<?php
	$cmd 		= $_REQUEST['cmd'];
	$current	= date(DATE_TIME);
	
	$page 		= $_REQUEST['page']; // get the requested page
	$start		= $_REQUEST["start"];
	$limit 		= $_REQUEST['rows']; // get how many rows we want to have into the grid
	$sidx 		= $_REQUEST['sidx']; // get index row - i.e. user click to sort
	$sord 		= $_REQUEST['sord']; // get the direction
	
	if(!$sidx) $sidx =1;
	
	$totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows']: false;
	if($totalrows) $limit = $totalrows;
	
	//select all area that s not deleted
	if($cmd=="")
	{
		$responce = new stdClass();
		$query = "SELECT * FROM tbl_track where 1";
		
		if($_REQUEST['device_id'] != ""){
			$query .= " and device_id = '".$_REQUEST['device_id']."'";
		}else{
			$responce->page = 0;
			$responce->total = 0;
			$responce->records = 0;
			print(json_encode($responce));
			exit;
		}
		if($_REQUEST['sdate'] != "" && $_REQUEST['edate'] != ""){
			$query .= " and CONVERT_TZ(add_date,'+00:00','+05:30') between '".date('Y-m-d H:i:s', strtotime($_REQUEST['sdate']))."' and '".date('Y-m-d H:i:s', strtotime($_REQUEST['edate']))."'";
		}
		
		if($searchOn=='true') {
    		$query .= $wh;
		}
		
		$num_result = mysql_query ($query) or die ("SQL : " . $query . "Error: " . mysql_error ());

		$totaldata = mysql_num_rows($num_result);
		
		if( $totaldata > 0 ) {
			$total_pages = ceil($totaldata/$limit);			
			if ($page > $total_pages) $page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		
		} else {
			$total_pages = 0;
			$start = 0;
		}
				
		$query .= " ORDER BY $sidx $sord LIMIT $start, $limit";

		$result = mysql_query($query) or die ("SQL : " . $query . "Error: " . mysql_error ());


		
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $totaldata;
		
		$s = 0;
		
		while ($row=mysql_fetch_array($result))
		{
			$row['add_date'] = date('d.m.Y h:i:s a',strtotime($row['add_date'] . " +5 hours 30 minutes"));
			$responce->rows[$s] = $row;
			$s++;
		}
			
		print(json_encode($responce));
	}
	if($cmd=="export"){
		$query = "SELECT * FROM tbl_track where 1";
		
		if($_REQUEST['device_id'] != ""){
			$query .= " and device_id = '".$_REQUEST['device_id']."'";
		}else{
			$responce->page = 1;
			$responce->total = 0;
			$responce->records = 0;
			print(json_encode($responce));
		}
		if($_REQUEST['sdate'] != "" && $_REQUEST['edate'] != ""){
			$query .= " and CONVERT_TZ(add_date,'+00:00','+05:30') between '".date('Y-m-d H:i:s', strtotime($_REQUEST['sdate']))."' and '".date('Y-m-d H:i:s', strtotime($_REQUEST['edate']))."'";
		}
		$result = mysql_query ($query) or die ("SQL : " . $query . "Error: " . mysql_error ());
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename='.$_REQUEST['device_id'].'.xls');
		
		$htmlTable = '<table border=1>';
		
		$htmlTable .= '<tr style="font-weight:bold">';
		$num_fields = mysql_num_fields($result);
		$fieldArr = array();
		for($i = 0; $i < $num_fields; $i++)
		{
			$field = mysql_fetch_field($result,$i);
			
			if($field->name != "phone_imei" && $field->name != "rfid" && $field->name != "fuel_percent" && $field->name != "temperature"){
				$fieldArr[] = $field->name;
				$htmlTable .= '<td>' . $field->name . '</td>';
			}
		}
		$htmlTable .= '</tr>';
		
		
		$l = 1;
		while ($row=mysql_fetch_array($result))
		{
			$htmlTable .= '<tr>';
			for($i=0; $i<count($fieldArr); $i++){
				if($fieldArr[$i] == 'add_date'){
					$row['add_date'] = date('d.m.Y h:i a',strtotime($row['add_date'] . " +5 hours 30 minutes"));
				}
				$htmlTable .= '<td>' . $row[$fieldArr[$i]] . '</td>';
			}
			$htmlTable .= '</tr>';
			$l++;
		}
		
		$htmlTable .= '</table>';
		
		die($htmlTable);
		
	}
?>