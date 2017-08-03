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
	
	$checkInvoce = false;
	
	$entry_table = $_REQUEST["action"];
	
	$html = '<table width="100%" border=1 cellpadding=0 cellspacing=0><tr><th>Address</th><th>Latitude</th><th>Longitude</th></tr>';
	
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
	
	$colArray = array('Location', 'Lat', 'Long', 'State', 'District');
	
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
		$html.="<tr><td colspan=\"3\" align=\"center\">&nbsp;</tr>";
		$html.="<tr><td colspan=\"3\" align=\"center\"><strong>".$counterRows." Records will be import out of ".($highestRow-1)." Records.</strong></td></tr>";
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
	global $html, $userid;
	$num_rows=0;
	if($imp_data['Lat']!="" && $imp_data['Long']){
		$qry="SELECT * FROM tbl_cell_data where latitude=".$imp_data['Lat']." and longitude=".$imp_data['Long']." Limit 1";
		$qryRes=mysql_query($qry);
		$num_rows = mysql_num_rows($qryRes);
	}
	
		$locations=$imp_data['Location'];
		if($imp_data['District']!=""){
			$locations.=", ".$imp_data['District'];
		}
		if($imp_data['State']!=""){
			$locations.=", ".$imp_data['State'];
		}
	if($display)
	{
		if($num_rows > 0){
				$html .= '<tr style=\'background:darkseagreen\'><td align="center">'.$locations.'</td><td align="center">'.$imp_data['Lat'].'</td><td align="center">'.$imp_data['Long'].'</td><tr>';		
		}else if($imp_data['Location'] != "" && $imp_data['Lat']!="" && $imp_data['Long']){
				$html .= '<tr><td align="center">'.$locations.'</td><td align="center">'.$imp_data['Lat'].'</td><td align="center">'.$imp_data['Long'].'</td><tr>';
				$counterRows++;
		}else{
			$html .= '<tr style=\'background:darkseagreen\'><td align="center">'.$locations.'</td><td align="center">'.$imp_data['Lat'].'</td><td align="center">'.$imp_data['Long'].'</td><tr>';
		}
	}else{
		if($num_rows == 0 && $imp_data['Location'] != "" && $imp_data['Lat']!="" && $imp_data['Long']){		
			$values = "('".$imp_data['Lat']."', '".$imp_data['Long']."','".$locations."', '".date("Y.m.d H:i:s")."')";
			
			$query = "INSERT INTO tbl_cell_data (latitude, longitude, address, add_date) VALUES " . $values;
			
			mysql_query($query) or generateMSG('',"Error in Data Insert".$query,false);		
		}		
	}
	return true;
}
?>
