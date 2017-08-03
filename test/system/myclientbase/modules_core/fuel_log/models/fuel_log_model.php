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

class Fuel_log_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function fuel_log_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "fuel_log";
    }
	
	public function get_stopdata($cmd) 
	{
		$user = $this->session->userdata('user_id');
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		$device = $this->input->get('device');
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d H:i:s", strtotime($sdate));
			$edate = date("Y-m-d H:i:s", strtotime($edate));
		}else{
			$sdate = date("Y-m-d H:i:s");
			$edate = date("Y-m-d H:i:s");
		}
		
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:10; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'tm.id'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';
		

		$where = ""; 
		$searchField = isset($_GET['searchField']) ? $_GET['searchField'] : false;
		$searchOper = isset($_GET['searchOper']) ? $_GET['searchOper']: false;
		$searchString = isset($_GET['searchString']) ? $_GET['searchString'] : false;

		if (isset($_GET['_search']) && $_GET['_search'] == 'true') {
			$ops = array(
			'eq'=>'=', 
			'ne'=>'<>',
			'lt'=>'<', 
			'le'=>'<=',
			'gt'=>'>', 
			'ge'=>'>=',
			'bw'=>'LIKE',
			'bn'=>'NOT LIKE',
			'in'=>'LIKE', 
			'ni'=>'NOT LIKE', 
			'ew'=>'LIKE', 
			'en'=>'NOT LIKE', 
			'cn'=>'LIKE', 
			'nc'=>'NOT LIKE' 
			);
			foreach ($ops as $key=>$value){
				if ($searchOper==$key) {
					$ops = $value;
				}
			}
			if($searchOper == 'eq' ) $searchString = $searchString;
			if($searchOper == 'bw' || $searchOper == 'bn') $searchString .= '%';
			if($searchOper == 'ew' || $searchOper == 'en' ) $searchString = '%'.$searchString;
			if($searchOper == 'cn' || $searchOper == 'nc' || $searchOper == 'in' || $searchOper == 'ni') $searchString = '%'.$searchString.'%';
			$where = "$searchField $ops '$searchString' "; 
		}

	
		if(!$sidx)  
			$sidx = 1;
		/*
		$SQL = "SELECT count(*) as total FROM ".$this->table_name." tm WHERE CONVERT_TZ(tm.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '".$edate."' and reason = 'E' and ignition=1 and analog_in_1 is not null and analog_in_1 <> '' and fuel_percent is not null";
		if($device){	//search by device
			$SQL .= " AND tm.assets_id = $device";		
		}else{
			$SQL .= " AND tm.assets_id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";		
		}
		if($where != "")
			$SQL .= " AND $where";
		
		$result = $this->db->query($SQL);
		$data_arr=$result->result_array();
		
		$count = $data_arr[0]['total'];
	
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);
			$start = ($limit*$page) - $limit;  
		} else {
			$total_pages = 0;
			$start = 0;
		}
		*/
		/*if ($page > $total_pages) 
			$page = $total_pages;
		*/
		
		//$SQL = "SELECT tm.id, tm.device_id, CONVERT_TZ(tm.add_date,'+00:00','".$this->session->userdata('timezone')."') as date_time, tm.analog_in_1 as fuel_reading, tm.fuel_percent, am.assets_name FROM ".$this->table_name." tm left join assests_master am on tm.assets_id=am.id WHERE CONVERT_TZ(tm.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '".$edate."' and reason='E' and ignition=0 and analog_in_1 is not null and analog_in_1 <> '' and analog_in_1 <> '18' and fuel_percent is not null";
		$SQL = "SELECT tm.id, tm.assets_id, CONVERT_TZ(tm.add_date,'+00:00','".$this->session->userdata('timezone')."') as date_time, tm.fuel_reading, tm.fuel_percent, tm.fuel_liters, am.assets_name FROM ".$this->table_name." tm left join assests_master am on tm.assets_id=am.id WHERE CONVERT_TZ(tm.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '".$edate."' and fuel_reading <> '18' and fuel_percent is not null";
		if($device){	//search by device
			$SQL .= " AND tm.assets_id = $device";		
		}else{
			$SQL .= " AND tm.assets_id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";		
		}
		if($where != "")
			$SQL .= " AND $where";
		//$result = $this->db->query($SQL);
		$export_sql="";
		$export_sql=$SQL;
		$SQL .= " ORDER BY $sidx $sord ";
		//LIMIT $start, $limit
		$query = $this->db->query($SQL);
		
		
		if($cmd=="export") 
		{
			$result = $this->db->query($export_sql);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=stopreport". date("s").".xls"); 
			$EXCEL = "";
			$fitr="";
			
			//session date & time format
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			$date="";
			//$device="";
			$fitr .="<tr>";
			$fitr.="<th>Assets Name</th>";			
			$fitr.="<th>Stop Time</th>";
			$fitr.="<th>Start Time</th>";
			$fitr.="<th>Location</th>";
			$fitr.="<th>Duration</th>";
			$fitr .="</tr>";
			
			foreach($result->result_array() as $data)
				{
					$ignition_off = $data['ignition_off'];
					$ignition_on = $data['ignition_on'];
					//$add_date = $data['add_date'];
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data['assets_name']."(".$data['device_id'].")</td>"; 
					$EXCEL.="<td>&nbsp;".date("$date_format $time_format", strtotime($ignition_off))."</td>"; 
					$EXCEL.="<td>&nbsp;".date("$date_format $time_format", strtotime($ignition_on))."</td>"; 
					$EXCEL.="<td>".$data['address']."</td>";
					$EXCEL.="<td>".$data['duration']."</td>";
					$EXCEL .="</tr>";
					$date=date("$date_format $time_format", strtotime($ignition_off));
					$device_name = $data['assets_name']." (".$data['device_id'].")";
				}
				$sdt = date("$date_format $time_format", strtotime($sdate));
				$edt = date("$date_format $time_format", strtotime($edate));
			if($this->session->userdata('id')==1)
				$count=3; 
			else
				$count=2; 
			
			if($device == '')
				$device = "ALL";
			else
				$device = $device_name;
				
 			echo "<table border='1'>";
			echo "<tr><th colspan='5'>Stop Report</th></tr>";
			echo "<tr><th colspan='2'>Start Date</th><th colspan='2'>Stop Date</th><th>Assets Name</th></tr>";
			echo "<tr><th colspan='2'>&nbsp;$sdt</th><th colspan='2'>&nbsp;$edt</th><th>$device</th></tr>";
			echo $fitr;
			echo $EXCEL;
			echo "</table>";
			die(); 
		}
		
		$data = array();
		$data['result'] = $query->result();
		/*$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;*/
		$data['page'] = 1;
		$data['total_pages'] = 1;
		$data['count'] = 1;
		return $data;
	}
	
	function get_map_data(){
			$id=uri_assoc('id');
			$squery="select id, device_id, CONVERT_TZ(ignition_off,'+00:00','".$this->session->userdata('timezone')."') as ignition_off, CONVERT_TZ(ignition_on,'+00:00','".$this->session->userdata('timezone')."') as ignition_on, duration, address, lat, lng, CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, alert_given, current_area, current_landmark from ".$this->table_name." where id=$id Limit 1";
			$query = $this->db->query($squery);
			return $query->result();	
	
	}
}
?>