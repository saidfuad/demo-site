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

class activity_master_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function activity_master_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "activity_master";
    }
	
	public function get_data($cmd) 
	{
		$user = $this->session->userdata('user_id');
		$sdate = $this->input->get_post('sdate');
		$sdate1 = $this->input->get_post('sdate');
		$sdate_to = $this->input->get_post('sdate_to');
		
		
		$device=trim($this->input->get_post('device'),",");
		
		if($sdate){	//search by date
			$sdate = date("Y-m-d H:i:s", strtotime($sdate));
		}
		if($sdate_to){	//search by date
			$sdate_to = date("Y-m-d H:i:s", strtotime($sdate_to));
		}
		
		if($cmd=="export")   
		{	
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=activity_master.xls");
		}
		
		
		$EXCEL = ""; 
		
		//session date & time format 
		$date_format = $this->session->userdata('date_format');  
		$time_format = $this->session->userdata('time_format'); 
		
		$usertype_id  	= $this->session->userdata("usertype_id");
		$assets_name	= "";
		$driver_name	= "";
		
		$SQL = "SELECT dm.*, am.assets_name, CONVERT_TZ(dm.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date1 
		from activity_master dm 
		left join assests_master am on am.id = dm.assets_id 
		WHERE CONVERT_TZ(dm.add_date,'+00:00','".$this->session->userdata('timezone')."')  between '" . $sdate . "' and '" . $sdate_to . "' ";
			
		if($usertype_id !=1) 	$SQL .=  " and  am.id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user))) ";
		
		if($device) $SQL .= " AND find_in_set(dm.assets_id,'$device')";
		
		$SQL .= " order by dm.add_date asc ";
		
		
		$moving_idling	 	= "";
		$clocation			= "";
		$alert_header	 	= "";
		$speed	 			= "";
		$add_date	 		= "";
		$longitude	 		= "";
		$latitude	 		= "";
		$view_on_map 		= "";
		
		//die($SQL."");
		$result = $this->db->query($SQL);
		foreach($result->result_array() as $data)
		{
			$moving_idling 	= ($data['moving_idling']);
			$alert_header 	= ($data['alert_header']);
			
			$clocation 		= $data['clocation'];
			
			$longitude 		= $data['longitude'];
			$latitude 		= $data['latitude'];
			$view_on_map 	= "<a href='#' onclick='view_activity_master_map(".$data['id'].",\"".$data['assets_name']."\")'> <img src='".base_url()."assets/marker-images/mini-RED-BLANK.png'></a>";
			
			$speed 	= $data['speed'];
			
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
				
			$add_date 	= $data['add_date1'];
			
			$EXCEL .="<tr align='center'>";
			$EXCEL.="<td>".date("d.m.Y h:i A",strtotime($add_date))." </td>"; 
			$EXCEL.="<td>".$moving_idling." </td>"; 
			$EXCEL.="<td>".$alert_header."</td>";
			$EXCEL.="<td>".$speed."</td>";
			$EXCEL.="<td>".$clocation."</td>";
			$EXCEL.="<td>".$assets_name."</td>";
			$EXCEL.="<td>".$latitude."</td>";
			$EXCEL.="<td>".$longitude."</td>";
			$EXCEL.="<td>".$view_on_map."</td>";
			$EXCEL .="</tr>";
		}
		
		$fitr = "";
		if($cmd=="export")   
		{	
			$fitr = "<style> th,td { border: 1px solid; } </style>";
		}
		$fitr	.= "<table style='border:1px solid;' rules='all' width='100%'><tr><th colspan='8'>ACTIVITY REPORT</th></tr>";
		
		$fitr	.="<tr><th align='left' colspan='8'>Date :- ".date("d.m.Y h:i A",strtotime($sdate))." - ".date("d.m.Y h:i A",strtotime($sdate_to))." </th></tr>";
		
		$fitr .="<tr>";
		$fitr.="<th>Date</th>";
		$fitr.="<th>Status</th>";
		$fitr.="<th>Event</th>";
		$fitr.="<th>Speed Km</th>";
		$fitr.="<th>Location</th>";
		$fitr.="<th>Vehicle</th>";
		$fitr.="<th>Latitude</th>";
		$fitr.="<th>Longitude</th>";
		$fitr.="<th>View Map</th>";
		$fitr .="</tr>"; 
		
		if($this->session->userdata('id')==1)
			$count=3;
		else
			$count=2;
		
		return $fitr.$EXCEL."</table>";
		
	}
	
	function get_map_data(){
			$id=uri_assoc('id');
			
			$squery="select clocation , longitude , latitude ,  CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date from ".$this->table_name." where id=$id Limit 1";
			$query = $this->db->query($squery);
			return $query->result();	
	
	}
}
?>