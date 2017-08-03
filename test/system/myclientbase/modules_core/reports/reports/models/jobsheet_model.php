<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Eye View Design CMS module Ajax Model
 *
 * PHP version 5
 *
 * @category  CodeIgniter
 * @package   EVD CMS
 * @author    Frederico Carvalho
 * @copyright 2008 Mentes 100Limites
 * @version   0.1
*/

class jobsheet_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function jobsheet_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "tbl_track";
    }
	
	public function get_data($cmd) 
	{
		$user = $this->session->userdata('user_id');
		$sdate = $this->input->get_post('sdate');
		$sdate1 = $this->input->get_post('sdate');
		
		
		$device=trim($this->input->get_post('device'),",");
		
		if($sdate){	//search by date
			$sdate = date("Y-m", strtotime($sdate));
		}
		
		$edate = $sdate;
		
		if($cmd=="export")   
		{	
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=JobSheet.xls");
		}
		
		
		$EXCEL = ""; 
		
		//session date & time format 
		$date_format = $this->session->userdata('date_format');  
		$time_format = $this->session->userdata('time_format'); 
		
		$usertype_id  	= $this->session->userdata("usertype_id");
		$assets_name	= "";
		$driver_name	= "";
		
		$SQL = "SELECT dm.id, am.assets_name,am.driver_name , am.id as aId, CONVERT_TZ(dm.intime,'+00:00','".$this->session->userdata('timezone')."') as intime, CONVERT_TZ(dm.outtime,'+00:00','".$this->session->userdata('timezone')."') as outtime, CONVERT_TZ(dm.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, dm.from_address, dm.to_address, dm.first_reading , dm.current_reading   from tbl_jobsheet_mst dm left join assests_master am on am.id = dm.assets_id WHERE DATE_FORMAT(CONVERT_TZ(dm.add_date,'+00:00','".$this->session->userdata('timezone')."'),'%Y-%m') = '" . $edate . "' ";
			
		if($usertype_id !=1) 	$SQL .=  " and  am.id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user))) ";
		
		if($device) $SQL .= " AND find_in_set(dm.assets_id,'$device')";
		
		$SQL .= " order by date(CONVERT_TZ(dm.add_date,'+00:00','".$this->session->userdata('timezone')."')) asc ";
		
		
		$start_km	 	= "";
		$from_address	= "";
		
		$in_time	 	= "";
		
		$end_km	 		= "";
		$to_address	 	= "";
		$outtime	 	= "";
		$add_date	 	= "";
		
		//die($SQL."");
		$result = $this->db->query($SQL);
		foreach($result->result_array() as $data)
		{
			$start_km 	= intval($data['first_reading']);
			$end_km 	= intval($data['current_reading']);
			
			$from_address 	= $data['from_address'];
			$to_address 	= $data['to_address'];
			
			$assets_name = $data['assets_name'];
			$driver_name = $data['driver_name'];
			
			if($data['intime'] == "")
				$in_time 	= "";
			else
				$in_time 	= date($time_format,strtotime($data['intime']));
			
			if($data['outtime'] == "")
				$outtime 	= "";
			else
				$outtime 	= date($time_format,strtotime($data['outtime']));
				
			$add_date 	= $data['add_date'];
			
			
			if($from_address != "") $from_address .= " <b>TO</b> "; 
			
			$diff_km = number_format($end_km - $start_km,0);
		
			$EXCEL .="<tr align='center'>";
			$EXCEL.="<td>".date("d.m.Y",strtotime($add_date))." </td>"; 
			$EXCEL.="<td>".$in_time." </td>"; 
			$EXCEL.="<td>".$outtime." </td>"; 
			$EXCEL.="<td>".$start_km." </td>"; 
			$EXCEL.="<td>".$end_km."</td>";
			$EXCEL.="<td>".$diff_km."</td>";
			$EXCEL.="<td>".$driver_name."</td>";
			$EXCEL.="<td>".$from_address."".$to_address."</td>";
			$EXCEL .="</tr>";
		}
		
		$fitr = "";
		if($cmd=="export")   
		{	
			$fitr = "<style> th,td { border: 1px solid; } </style>";
		}
		$fitr	.= "<table style='border:1px solid;' rules='all' width='100%'><tr><th colspan='8'>VEHICLE LOG BOOK</th></tr>";
		
		$fitr	.="<tr><th align='left' colspan='8'>VEHICLE NUMBER :- $assets_name</th></tr>";
		$fitr	.="<tr><th align='left' colspan='8'>Month :- $sdate1</th></tr>";
		
		$fitr .="<tr>";
		$fitr.="<th>Date</th>";
		$fitr.="<th>Out Time</th>";
		$fitr.="<th>In Time</th>";
		$fitr.="<th>Start Km</th>";
		$fitr.="<th>End Km</th>";
		$fitr.="<th>Difference</th>";
		$fitr.="<th>Name of Driver</th>";
		$fitr.="<th>Tour Description</th>";
		$fitr .="</tr>"; 
		
		if($this->session->userdata('id')==1)
			$count=3;
		else
			$count=2;
		
		return $fitr.$EXCEL."</table>";
		
	}
	
	function get_map_data(){
			$id=uri_assoc('id');
			
			$squery="select id, device_id, CONVERT_TZ(ignition_off,'+00:00','".$this->session->userdata('timezone')."') as ignition_off, CONVERT_TZ(ignition_on,'+00:00','".$this->session->userdata('timezone')."') as ignition_on, duration, address, lat, lng, CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, alert_given, current_area, current_landmark from ".$this->table_name." where id=$id Limit 1";
			$query = $this->db->query($squery);
			return $query->result();	
	
	}
}
?>