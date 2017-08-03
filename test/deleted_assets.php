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
		$query = "SELECT * FROM assests_master where del_date is not null or status = 0";
		
		if($_REQUEST['device_id'] != ""){
			$query .= " and device_id = '".$_REQUEST['device_id']."'";
		}
		if($_REQUEST['user_id'] != ""){
			$query .= " and add_uid = '".$_REQUEST['user_id']."'";
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
		$result = mysql_query($query) or die ("SQL : " . $query . "Error: " . mysql_error ());
		echo "<table>";
		while ($row=mysql_fetch_array($result))
		{
			echo "<tr><td>".$row['id']."</td><td>".$row['device_id']."</td><td>".$row['assets_name']."</td></tr>";
		}
		echo "</table>";
		exit;
	}
	if($cmd=="update"){
		$id = $_REQUEST['id'];
		$device_id = $_REQUEST['device_id'];
		$update = "update assests_master set device_id = '$device_id' where id='$id'";
		mysql_query($update) or die(mysql_error());
		echo "Record Updated Successfully";
	}
?>