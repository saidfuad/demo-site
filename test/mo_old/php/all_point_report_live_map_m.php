<?php
include("session.php");
require_once("../../db.php");
$cmd = $_REQUEST['cmd'];
$user 		= $_SESSION["user_id"];
$date_format = $_SESSION["date_format"];  
$time_format = $_SESSION['time_format'];  
		
if($cmd=="trackOnMap")
{
	
	$device = $_REQUEST['device'];
	$sdate = $_REQUEST['start_date'];
	$edate = $_REQUEST['end_date'];
	$user_tz = $_SESSION['timezone'];
	
	$qry_rs="SELECT id, lati, longi, phone_imei, CONVERT_TZ(add_date,'+00:00','$user_tz') as add_date, speed, device_id, dt, ignition, address, odometer FROM tbl_track WHERE ";
	if($sdate && $edate){	//search by date
		$sdate = date("Y-m-d H:i:s", strtotime($sdate));
		$edate = date("Y-m-d H:i:s", strtotime($edate));
	}else{
		$sdate = date("Y-m-d H:i:s");
		$edate = date("Y-m-d H:i:s");
	}
	$qry_rs.="CONVERT_TZ(add_date,'+00:00','$user_tz') BETWEEN '".$sdate."' AND '" . $edate . "'";
	if($device){
		$qry_rs.="AND assets_id=$device ";
	}
	$qry_rs.=" Order by id";
	
	$rows = mysql_query($qry_rs) or die($qry_rs. mysql_error());
	
	$lat = array();
	$lng = array();
	$html = array();
	$ignition_status = array();
	$count=0;
	$DistanceVal=0;
	
	if(sizeof($rows)>1)
	{
		$DistanceVal=floatval(($rows[sizeof($rows)-1]['odometer']-$rows[0]['odometer'])/1000);
	}
	for($i=0;$i<sizeof($rows)-1;$i++)
	{
	
			$lat[] = $rows[$i]['lati'];
			$lng[] = $rows[$i]['longi'];
			$text = 'Date : '.date($date_format.' '.$time_format, strtotime($rows[$i]['add_date']))."<br>";
			$text .= 'Speed : '.$rows[$i]['speed']."<br>";
			//$text .= 'Lat : '.$row->lati.'<br>';
			//$text .= 'Lng : '.$row->longi.'<br>';
			$text .= 'Address : '.$rows[$i]['address'].'<br>';
			if($this->session->userdata('show_map_inspection_button')==1){
				$text .="<span style=\"display:block\"><a href=\"#\" onClick=\"saveInspection(".$rows[$i]['id'].");\" style=\"color:blue;float:right;\">".$this->lang->line('Save Inspection')."</a></span>";
			}
			$html[] = $text;
			$ignition_status[]=1;
	}
	$lat2 = '';
	$lng2 = '';
	$distance = 0;
	$data['lat'] = $lat;
	$data['lng'] = $lng;
	$data['html'] = $html;
	$data['distance'] = $DistanceVal;
	$data['ignition_status'] = $ignition_status;
	die(json_encode($data));
	
}

?>