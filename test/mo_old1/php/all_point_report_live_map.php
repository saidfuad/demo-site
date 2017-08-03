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
		$sdate = date("Y-m-d", strtotime($sdate));
		$edate = date("Y-m-d", strtotime($edate));
	}else{
		$sdate = date("Y-m-d");
		$edate = date("Y-m-d");
	}
	$qry_rs.="date(CONVERT_TZ(add_date,'+00:00','$user_tz')) BETWEEN '".$sdate."' AND '" . $edate . "'";
	if($device){
		$qry_rs.="AND assets_id=$device ";
	}
	$qry_rs.=" Order by id";
	
	$res = mysql_query($qry_rs) or die($qry_rs. mysql_error());
	
	$lat = array();
	$lng = array();
	$html = array();
	$ignition_status = array();
	$count=0;
	$DistanceVal=0;
	$i = 0;
	$count1 = mysql_num_rows($res);
	if(mysql_num_rows($res)>1){
		
		while($rows =mysql_fetch_array($res))
		{
			//$DistanceVal=floatval(($rows[$count1-1]['odometer']-$rows[0]['odometer'])/1000);
			$lat[] = $rows['lati'];
			$lng[] = $rows['longi'];
			$text = 'Date : '.date($date_format.' '.$time_format, strtotime($rows['add_date']))."<br>";
			$text .= 'Speed : '.$rows['speed']."<br>";
			//$text .= 'Lat : '.$row->lati.'<br>';
			//$text .= 'Lng : '.$row->longi.'<br>';
			$text .= 'Address : '.$rows['address'].'<br>';
			/*if($this->session->userdata('show_map_inspection_button')==1){
				$text .="<span style=\"display:block\"><a href=\"#\" onClick=\"saveInspection(".$rows['id'].");\" style=\"color:blue;float:right;\">".$this->lang->line('Save Inspection')."</a></span>";
			}*/
			$html[] = $text;
			$ignition_status[]=1;
			
			$i++;
		}
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