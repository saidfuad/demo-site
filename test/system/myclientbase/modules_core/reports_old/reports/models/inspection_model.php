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

class Inspection_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function inspection_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "tbl_inspection_track";
		$this->tbl_assets = "assests_master";
    }
	function getAllData($cmd){
		$user = $this->session->userdata('user_id');
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		//$device = $this->input->get('device');
		$device=trim($this->input->get('device'),",");
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d H:i:s", strtotime($sdate));
			$edate = date("Y-m-d H:i:s", strtotime($edate));
		}else{
			$sdate = date("Y-m-d H:i:s");
			$edate = date("Y-m-d H:i:s");
		}
	//	die($sdate);
	//	die($sdate."->".$edate);
	
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:10; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'id'; 
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
		
		//$SQL = "SELECT count(*) FROM ".$this->table_name." tm left join ".$this->tbl_assets." am on am.device_id = tm.device_id WHERE am.status=1 AND am.del_date is null AND date(tm.add_date) BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		$SQL = "SELECT count(*) as total FROM ".$this->table_name." tm left join assests_master am on am.id=tm.assets_id WHERE am.id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user))) and CONVERT_TZ(tm.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		
		if($device!=""){	//search by device
			$SQL .= " AND find_in_set(tm.assets_id,'$device')";
		}else{
			return;
			die();
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
		
		if ($page > $total_pages) 
			$page = $total_pages;
		
		//die($assets_nm);
		//$SQL = "SELECT tm.id, tm.add_date, (select assets_name from $this->tbl_assets where id=tm.device_id) as assets_name, tm.device_id, tm.lati, tm.longi, tm.speed, tm.address FROM ".$this->table_name." tm WHERE tm.add_date BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		
	
		//$SQL = "SELECT tm.*, am.assets_name FROM ".$this->table_name." tm left join assests_master am on am.id=tm.assets_id WHERE am.id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user))) and CONVERT_TZ(tm.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		$SQL = "SELECT tm.id, tm.assets_id, tm.lati, tm.longi, tm.phone_imei, CONVERT_TZ(tm.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, tm.speed, tm.url_id, tm.device_id, tm.gps, CONVERT_TZ(tm.dt,'+00:00','".$this->session->userdata('timezone')."') as dt, tm.tm, tm.ignition, tm.box_open, tm.altitude, tm.direction, tm.gsm_strength, tm.angle_dir, tm.power_st, tm.acc_st, tm.reserved, tm.mileage, tm.address, tm.msg_serial_no, tm.reason, tm.reason_text, tm.command_key, tm.command_key_value, tm.msg_key, tm.odometer, tm.sat_mode, tm.gsm_register, tm.gprs_register, tm.server_avail, tm.in_batt, tm.ext_batt_volt, tm.digital_io, tm.analog_in_1, tm.analog_in_2, tm.analog_in_3, tm.analog_in_4, tm.rfid, tm.fuel_percent, tm.temperature , am.assets_name FROM ".$this->table_name." tm left join assests_master am on am.id=tm.assets_id WHERE am.id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user))) and CONVERT_TZ(tm.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		//$SQL = "SELECT tm.id, tm.add_date, '' as assets_name, tm.device_id, tm.lati, tm.longi, tm.speed, tm.address FROM ".$this->table_name." tm WHERE tm.add_date BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		if($device!=""){	//search by devices
			$SQL .= " AND find_in_set(tm.assets_id,'$device')";
		}else{
			return;
			die();
		}
			
		if($where != "")
			$SQL .= " AND $where";
		//die($SQL);
		//$result = $this->db->query($SQL);
		$export_sql="";
		$export_sql=$SQL;
		if($cmd=="export") 
		{
			$result = $this->db->query($export_sql);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=inspection". date("s").".xls");
			$EXCEL = "";
			$fitr="";
			
			//session date & time format
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			$fitr .="<tr>"; 
			$fitr.="<th>". $this->lang->line("Datetime")."</th>";
			$fitr.="<th>". $this->lang->line("Assets Name")."</th>";
			$fitr.="<th>". $this->lang->line("Address")."</th>";
			$fitr.="<th>". $this->lang->line("Speed")."</th>";
			$fitr .="</tr>";
			foreach($result->result_array() as $data)
			{
				$add_date = $data['add_date'];
				
				$EXCEL .="<tr align='center'>";
				$EXCEL.="<td>&nbsp;".date("$date_format $time_format", strtotime($add_date))."</td>"; 
				$EXCEL.="<td>".$data['assets_name']."(".$data['device_id'].")</td>"; 
				$EXCEL.="<td>".$data['address']."</td>";
				$EXCEL.="<td>".$data['speed']."</td>";
				
				if($this->session->userdata('id')==1)
				{
					$EXCEL.="<td>".$data['Owner']."</td>";
				}
				$EXCEL .="</tr>";
				
				$device_name=$data['assets_name']." (".$data['device_id'].")";
			}
			if($this->session->userdata('id')==1)
				$count=3; 
			else
				$count=2; 
		
			echo "<table border='1'>";
			echo "<tr><th colspan='4'> ".$this->lang->line("All points")."</th></tr>";
			echo "<tr><th colspan='1'>".$this->lang->line("Start Date")."</th><th colspan='1'>".$this->lang->line("End Date")."</th><th colspan='2'>".$this->lang->line("Assets Name")."</th></tr>";
			echo "<tr><th colspan='1'>&nbsp;".date("$date_format $time_format", strtotime($sdate))."</th><th colspan='1'>&nbsp;".date("$date_format $time_format", strtotime($edate))."</th><th colspan='2'></th></tr>";
			echo $fitr;
			echo $EXCEL;
			echo "</table>";
			die(); 
		}
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		
		$query = $this->db->query($SQL);
		
		$data = array();
		$data['result'] = $query->result();
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;
		return $data;
	}
	//this function for data display in grid
	public function get_inspection() 
	{
		//Select table name
		
		$sdate = $this->input->post('sdate');
		$edate = $this->input->post('edate');
		$device = $this->input->post('device');
		if($device == "")
			$device = -1;
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-d");
			$edate = date("Y-m-d");
		}
		 
		$this->db->select('tm.id, tm.add_date, am.assets_name, tm.device_id, tm.lati, tm.longi, tm.speed, tm.address')->from($this->table_name. " tm");
		$this->db->join($this->tbl_assets. " am", "am.device_id = tm.device_id", 'LEFT');
		$this->db->where("date(tm.add_date) BETWEEN '" . $sdate . "' AND '" . $edate . "'");
		if($device){	//search by device
			$this->db->where('tm.device_id',$device);
		}
		$this->CI->flexigrid->build_query();
		
		//Get contents
		$return['records'] = $this->db->get();
		
		//Build count query
		$this->db->select('count(id) as record_count')->from($this->table_name);
		$this->db->where("date(add_date) BETWEEN '" . $sdate . "' AND '" . $edate . "'");
		if($device){	//search by device
			$this->db->where('device_id',$device);
		}
		$this->CI->flexigrid->build_query(FALSE);
		$record_count = $this->db->get();
		$row = $record_count->row();
		
		//Get Record Count
		$return['record_count'] = $row->record_count;
		
		//Return all
		return $return;
		
	}
	
	//this function for data display on map
	public function get_all_locations() 
	{
		//Select table name
		$device = $this->input->post('device');
		$sdate = $this->input->post('start_date');
		$edate = $this->input->post('end_date');
		$qry="SELECT id, lati, longi, phone_imei, add_date, speed, device_id, dt, ignition, address, odometer FROM ".$this->table_name;
		//$this->db->select('id,lati,longi,phone_imei,add_date,speed,device_id,dt,ignition,address,odometer');
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d H:i:s", strtotime($sdate));
			$edate = date("Y-m-d H:i:s", strtotime($edate));
		}else{
			$sdate = date("Y-m-d H:i:s");
			$edate = date("Y-m-d H:i:s");
		}
		//$this->db->where("date(add_date) BETWEEN '" . $sdate . "' AND '" . $edate . "'");
		$qry.=" WHERE date(add_date) BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		if($device){
			//$this->db->where('assets_id', $device);
			$qry.=" AND assets_id=$device";
		}
		//$this->db->order_by('id');
		$qry.=" Order By id";
		$query = $this->db->query($qry);
		return $query->result_array();
	}
	
	public function prepareCombo(){
		
		$user = $this->session->userdata('user_id');
		
		$this->db->select("assets_name, device_id", FALSE);
		//$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = '.$user.'))');
		$this->db->where('status',1);
		$this->db->where('del_date',null);
		$query = $this->db->get($this->tbl_assets);
		$option = '';
		if($query-> num_rows()!=1)
			$option = "<option value=''>".$this->lang->line("Please Select")."</option>";
		foreach ($query->result() as $row) {
              $option .= "<option value='".$row->device_id."'>".$row->assets_name." (".$row->device_id.")</option>";
        }		  
		return $option;
	}
	function get_map_data(){
		$id=uri_assoc('id');
		$query = $this->db->query("select ins.id, ins.assets_id, ins.lati, ins.longi, ins.phone_imei, CONVERT_TZ(ins.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date , ins.speed, ins.url_id, ins.device_id, ins.gps, ins.dt, ins.tm, ins.ignition, ins.box_open, ins.altitude, ins.direction, ins.gsm_strength, ins.angle_dir, ins.power_st, ins.acc_st, ins.reserved, ins.mileage, ins.address, ins.msg_serial_no, ins.reason, ins.reason_text, ins.command_key, ins.command_key_value, ins.msg_key, ins.odometer, ins.sat_mode, ins.gsm_register, ins.gprs_register, ins.server_avail, ins.in_batt, ins.ext_batt_volt, ins.digital_io, ins.analog_in_1, ins.analog_in_2, ins.analog_in_3, ins.analog_in_4, ins.rfid, ins.fuel_percent, ins.temperature ,am.assets_name as assets from ".$this->table_name." ins left join assests_master am on ins.assets_id=am.id where ins.id=$id Limit 1");
		return $query->result();	
	}
	
}
?>