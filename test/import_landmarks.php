<?php
	session_start();
	set_time_limit(0);
	include("db.php");
	$userid = $_SESSION['userid'];
	$dir = "Excel/files";
	$cmd = $_REQUEST['cmd'];
	$Excel_file=$_REQUEST['file'];
	$counterRows=0;
	$extension = explode(".",$Excel_file);
	$extension = $extension[sizeof($extension) - 1]; 
	$path = $dir."/".$Excel_file;
	$insertArray = array();
	$grpArray = array();
	
	$checkInvoce = false;
	
	$entry_table = $_REQUEST["action"];

	$html = '<table width="100%" border="1" cellpadding="0" cellspacing="0"><tr><th>Name</th><th>Latitude</th><th>Longitude</th><th>Radius</th><th>Unit</th><th>Group</th><th>Address</th></tr>';
	
	error_reporting(E_ALL);
	
	require_once 'Excel/PHPExcel/IOFactory.php';

	if($extension == "xlsx"){
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objReader->setReadDataOnly(false);	
		$objPHPExcel = $objReader->load($path);
		
	}
	else{
		// Automatic file type resolving
		$objPHPExcel = PHPExcel_IOFactory::load($path);
	}
	
	if(isset($_REQUEST['sheet']))
		$sheet=$_REQUEST['sheet'];
	else
		$sheet=0;
	
	$colArray = array('Name', 'Latitude', 'Longitude', 'Radius', 'Unit', 'Group', 'Address');
	
	if($cmd == "sheet"){
		foreach ($objPHPExcel->getSheetNames() as $sheetIndex => $sheetName) {
			$option="<option value='".$sheetIndex."'";
			if($sheetIndex==$sheet)
				$option.=" selected='true'";
			$option.=">".$sheetName."</option>";
			echo $option;
		}
	}
	else if($cmd == "fields"){
		$objWorksheet = $objPHPExcel->setActiveSheetIndex($sheet);
	//	$colArray = array( 'from_area', 'to_area', 'beat_number');
		$j=0;
		foreach($colArray as $arr){
			echo '<tr><td><label for="'.$arr.'">'.$arr.'</label></td><td>';
			
			foreach ($objWorksheet->getRowIterator() as $row) {
			  $cellIterator = $row->getCellIterator();
			  $cellIterator->setIterateOnlyExistingCells(false);
			  echo '<select class="select ui-widget-content ui-corner-all" id="'.$arr.'" name="'.$arr.'">';
			  echo '<option value="-1">Please Select</option>';
			  $i=0;
			  foreach ($cellIterator as $cell) {
				echo "<option value='".$i."'";
				if($i==$j){
					echo " selected='true'";
				}
				echo ">".$cell->getValue()."</option>";
				$i++;
			  }
			  break;
			}
			echo '</select></td></tr>';
			$j++;
		}
		echo '</table>';
	}
	else if($cmd == "display" || $cmd=="insert"){
		$objWorksheet = $objPHPExcel->setActiveSheetIndex($sheet);
		$highestRow = $objWorksheet->getHighestRow(); // e.g. 10
		$highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5
		
		for ($row = 2; $row <= $highestRow; ++$row)
		{		
			$imp_data = array();
			
			foreach($colArray as $key)
			{
				$imp_data[$key] = trim($objWorksheet->getCellByColumnAndRow($_POST[$key], $row)->getValue());
			}
			insertData($imp_data,$row,$cmd=="display"?true:false,$row,$highestRow);
		}
		$html.="<tr><td colspan=\"7\" align=\"center\">&nbsp;</tr>";
		$html.="<tr><td colspan=\"7\" align=\"center\"><strong>".$counterRows." Records will be import out of ".($highestRow-1)." Records.</strong></td></tr>";
		if($cmd=="display"){
			$data['result'] = "true";
			
			$data['html'] = $html;
			die(json_encode($data));
		}
		else{
			
			generateMSG('',"Imported Successfully",TRUE);
		}		
	}
	

function insertData($imp_data,$i,$display=false,$row,$highestRow)
{
	global $html, $userid, $counterRows, $grpArray;
	$num_rows = 0;
	$grp_rows = 0;
	
	
	if($imp_data['Latitude']!="" && $imp_data['Longitude']){
		$qry = "SELECT id FROM landmark WHERE lat=".$imp_data['Latitude']." and lng=".$imp_data['Longitude']." Limit 1";
		$qryRes = mysql_query($qry);
		$num_rows = mysql_num_rows($qryRes);
	}
	
	if($imp_data['Group']!=""){
		$grp_qry = "SELECT id FROM group_master WHERE group_name = '".$imp_data['Group']."' AND status = 1 Limit 1";
		$grpRes = mysql_query($grp_qry);
		$grp_rows = mysql_num_rows($grpRes);
		$grp_row = mysql_fetch_assoc($grpRes);
		$grp_id = $grp_row['id'];
		
		if(! isset($grpArray[$imp_data['Group']]) && $grp_rows > 0) {
			$dev_sql = "SELECT group_concat(id) as devices FROM `assests_master` where assets_group_id = $grp_id";
			$devRes = mysql_query($dev_sql);
			$dev_row = mysql_fetch_assoc($devRes);
			$grpArray[$imp_data['Group']] = array('id' => $grp_id, 'dev' => $dev_row['devices']);
		}
		
	}
	
	if($display)
	{
		if($num_rows > 0){
			$html .= '<tr style=\'background:darkseagreen\'><td align="center">'.$imp_data['Name'].'</td><td align="center">'.$imp_data['Latitude'].'</td><td align="center">'.$imp_data['Longitude'].'</td><td align="center">'.$imp_data['Radius'].'</td><td align="center">'.$imp_data['Unit'].'</td><td align="center">'.$imp_data['Group'].'</td><td align="center">'.$imp_data['Address'].'</td></tr>';		

		}else if($imp_data['Latitude']!="" && $imp_data['Longitude'] != '' && $grp_rows > 0){
			$html .= '<tr><td align="center">'.$imp_data['Name'].'</td><td align="center">'.$imp_data['Latitude'].'</td><td align="center">'.$imp_data['Longitude'].'</td><td align="center">'.$imp_data['Radius'].'</td><td align="center">'.$imp_data['Unit'].'</td><td align="center">'.$imp_data['Group'].'</td><td align="center">'.$imp_data['Address'].'</td></tr>';
			$counterRows++;
		}else{
			$html .= '<tr style=\'background:darkseagreen\'><td align="center">'.$imp_data['Name'].'</td><td align="center">'.$imp_data['Latitude'].'</td><td align="center">'.$imp_data['Longitude'].'</td><td align="center">'.$imp_data['Radius'].'</td><td align="center">'.$imp_data['Unit'].'</td><td align="center">'.$imp_data['Group'].'</td><td align="center">'.$imp_data['Address'].'</td></tr>';
		}
	}else{
		if($num_rows == 0 && $grp_rows > 0 && $imp_data['Name'] != "" && $imp_data['Latitude']!="" && $imp_data['Longitude']!=""){
			$values = "('".addslashes($imp_data['Name'])."', '".addslashes($imp_data['Address'])."', '".$imp_data['Radius']."', '".$imp_data['Unit']."', '".$grpArray[$imp_data['Group']]['dev']."', '".$imp_data['Latitude']."', '".$imp_data['Longitude']."', '".$grpArray[$imp_data['Group']]['id']."', '".date("Y-m-d H:i:s")."', 'assets/landmark_images/flag.gif', $userid)";
			
			$query = "INSERT INTO landmark (name, address, radius, distance_unit, device_ids, lat, lng, group_id, add_date, icon_path, add_uid) VALUES " . $values;
			
			mysql_query($query) or generateMSG('',"Error in Data Insert : SQL : ".$query,false);		
		}		
	}
	return true;
}
?>