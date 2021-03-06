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

class Distancereport_model extends Model
{
	/**
	* Instanciar o CI
	*/
	public function distancereport_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "tbl_track";
    }
	
	public function get_distancereport($cmd) 
	{
		$user = $this->session->userdata('user_id');
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		$group = $this->input->get('group');
		//$device = $this->input->get('device');
		$device=trim($this->input->get('device'),",");
		
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-d");
			$edate = date("Y-m-d");
		}
		$sdate = $sdate." 00:00:00";
		$edate = $edate." 23:59:59";
		
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:10; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'id'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';
	//	$cmd=uri_assoc('cmd');
		
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
			
		/*$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		$device = $this->input->get('device');
		*/
		//SELECT id, lati, longi, phone_imei, dt as add_date, speed, device_id, dt, ignition, address, odometer FROM tbl_track  WHERE dt BETWEEN $sdate AND $edate  AND assets_id=$device
		$qry_rs="SELECT tt.id, am.assets_name as devices , sum(tt.distance_by_latlong) as total_distance FROM tbl_track tt left join assests_master am on am.id = assets_id  WHERE ";
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d H:i:s", strtotime($sdate));
			$edate = date("Y-m-d H:i:s", strtotime($edate));
		}else{
			$sdate = date("Y-m-d H:i:s");
			$edate = date("Y-m-d H:i:s");
		}
		
		
		$qry_rs.="dt BETWEEN '". $sdate ."' AND '" . $edate . "'";
		
		if($device){
			//$qry_rs.="AND assets_id=$device ";
			$qry_rs.="AND find_in_set(tt.assets_id,'$device')";

		}
		
		if($group != ""){
			$qry_rs .=" AND am.assets_group_id = $group ";
		}
		//$SQL .= " AND dm.assets_id = $device";
			
		if($where != "")
			$qry_rs .= " AND $where";
		$qry_rs .="GROUP BY assets_id";	

		$result = $this->db->query($qry_rs);
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
		
		//$qry_rs="SELECT tt.id, tt.lati, tt.longi, tt.phone_imei, tt.dt as add_date, tt.speed, tt.device_id, dt, ignition, address, odometer, am.assets_name as devices FROM tbl_track tt left join assests_master am on am.id = assets_id  WHERE ";
		$qry_rs="SELECT tt.id, am.assets_name as devices , sum(tt.distance_by_latlong) as total_distance FROM tbl_track tt left join assests_master am on am.id = assets_id  WHERE ";

		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d H:i:s", strtotime($sdate));
			$edate = date("Y-m-d H:i:s", strtotime($edate));
		}else{
			$sdate = date("Y-m-d H:i:s");
			$edate = date("Y-m-d H:i:s");
		}
		
		
		$qry_rs.="dt BETWEEN '". $sdate ."' AND '" . $edate . "'";
		
		if($device){
			//$qry_rs.="AND assets_id=$device ";
			$qry_rs.="AND find_in_set(tt.assets_id,'$device')";

		}
		
		if($group != ""){
			$qry_rs .=" AND am.assets_group_id = $group ";
		}
		if($where != "")
			$qry_rs .= " AND $where";
		$qry_rs .="GROUP BY assets_id";	
		$export_sql="";
		$export_sql=$qry_rs;
		$qry_rs .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		$query = $this->db->query($qry_rs);
		 
		if($cmd=="export")   
		{
			$result = $this->db->query($export_sql);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=Distance_Report.xls"); 
			$EXCEL = ""; 
			$fitr="";
			
			//session date & time format 
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			$fitr .="<tr>"; 
			$fitr.="<th>Date</th>";
			$fitr.="<th>Distance(KM)</th>";
			$fitr.="<th>Device</th>";
			$fitr .="</tr>"; 
			//var_dump($result);
			foreach($result->result_array() as $data)
				{
					$add_date = $data['add_date'];
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td> &nbsp;".date($date_format,strtotime($add_date))." </td>"; 
					$EXCEL.="<td>".$data['distance']."</td>";
					$EXCEL.="<td>".$data['assets_id']."</td>"; 
					$EXCEL .="</tr>";
					$device_name = $data['assets_id'];
				}
			if($this->session->userdata('id')==1)
				$count=3;
			else
				$count=2;
			
			echo "<table border='1'>";
			//echo "<tr><th colspan='3'>Distance Between " . date($date_format,strtotime($sdate)) . " AND " . date($date_format,strtotime($edate)) . "</th></tr>";
			echo "<tr><th colspan='3'>Distance</th></tr>";
			echo "<tr><th colspan='1'>Start Date</th><th colspan='1'>End Date</th><th colspan='1'>Assets Name</th></tr>";
			echo "<tr><th colspan='1'>&nbsp;".date("$date_format $time_format", strtotime($sdate))."</th><th colspan='1'>&nbsp;".date("$date_format $time_format", strtotime($edate))."</th><th colspan='1'></th></tr>";
			echo $fitr;
			echo $EXCEL;
			echo "</table>";
			die(); 
		}
		/*if($cmd == "pdf")
		{ 
			$data1 = $this->db->query($export_sql);
			return $data1;
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
		
		*/
		$data = array();
		$data['result'] = $query->result();
		$data['sql'] = $SQL;
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;
		return $data; 
		
		
		/*
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
		
		$this->db->select('id,add_date,phone_imei,device_id,lati,longi,speed')->from($this->table_name);
		$this->db->where("date(add_date) BETWEEN '" . $sdate . "' AND '" . $edate . "'");
		if($device){	//search by device
			$this->db->where('device_id',$device);
		}
		$this->CI->flexigrid->build_query(FALSE);
		
		//Get contents
		$return['records'] = $this->db->get();
		//echo $this->db->last_query();
		//exit;
		
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
		return $return;*/
	}
	public function get_stop_time($assets_id, $date)
	{
		//$query = $this->db->query("SELECT SUM( TIME_TO_SEC( TIMEDIFF( IF( ignition_on <>  '', ignition_on, CONVERT_TZ( NOW( ) , '+00:00',  '+05:30' ) ) , ignition_off ) ) ) AS stop_time FROM tbl_stop_report WHERE device_id = '$assets_id' and date(ignition_off) = '".$date."'", FALSE);
		$sql = "SELECT SUM( TIME_TO_SEC( TIMEDIFF( CONVERT_TZ(ignition_on,'+00:00','".$this->session->userdata('timezone')."') , CONVERT_TZ(ignition_off,'+00:00','".$this->session->userdata('timezone')."')  ) ) ) AS stop_time FROM tbl_stop_report WHERE device_id = '$assets_id' and date(CONVERT_TZ(ignition_off,'+00:00','".$this->session->userdata('timezone')."')) = '".$date."' and date(CONVERT_TZ(ignition_on,'+00:00','".$this->session->userdata('timezone')."')) = '".$date."'";
		// echo "Time : $sql";
		$query = $this->db->query($sql, FALSE);
		return $query->row();
	}
	public function get_stop_time_1($assets_id, $date)
	{
		$tdate = date('Y-m-d', strtotime($date . '-1 days'));
		$sql = "SELECT TIME_TO_SEC( TIMEDIFF( CONVERT_TZ(ignition_on,'+00:00','".$this->session->userdata('timezone')."'), '$date 00:00' ) ) AS stop_time FROM tbl_stop_report WHERE device_id = '$assets_id' and date(CONVERT_TZ(ignition_on,'+00:00','".$this->session->userdata('timezone')."')) = '".$date."' and date(CONVERT_TZ(ignition_off,'+00:00','".$this->session->userdata('timezone')."')) = '".$tdate."'";
		// echo "Time 1 : $sql";
		$query = $this->db->query($sql, FALSE);
		
		return $query->row();
	}
	public function get_stop_time_2($assets_id, $date)
	{
		$ndate = date('Y-m-d', strtotime($date . '+1 days'));
		$sql = "SELECT TIME_TO_SEC( TIMEDIFF( '$ndate 00:00', CONVERT_TZ(ignition_off,'+00:00','".$this->session->userdata('timezone')."') ) ) AS stop_time FROM tbl_stop_report WHERE device_id = '$assets_id' and date(CONVERT_TZ(ignition_off,'+00:00','".$this->session->userdata('timezone')."')) = '".$date."' and date(CONVERT_TZ(ignition_on,'+00:00','".$this->session->userdata('timezone')."')) = '$ndate'";
		// echo "Time 2 : $sql";
		$query = $this->db->query($sql, FALSE);
		return $query->row();
	}
	//this function for data display in grid
	public function get_distancegraph() 
	{
		//Select table name
		
		$sdate = $this->input->post('sdate');
		$edate = $this->input->post('edate');
		$group = $this->input->post('group');
		//$device = $this->input->post('device');
		$device=trim($this->input->post('device'),",");
		
		/*
		$this->db->select('id,add_date,phone_imei,device_id,lati,longi,speed');
		
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-d");
			$edate = date("Y-m-d");
		}
		$this->db->where("date(add_date) BETWEEN '" . $sdate . "' AND '" . $edate . "'");
			
		if($device == ''){	//search by device
			$device = '0';
			
		}
		$this->db->where('device_id',$device);
		$this->db->from($this->table_name)->orderBy('add_date');
		//$this->CI->flexigrid->build_query();
		
		//Get contents
		$return['records'] = $this->db->get();
		
		//$this->CI->flexigrid->build_query(FALSE);
		*/
		//$qryFinal ="SELECT id, assets_id, CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, first_reading, current_reading, distance, running_time from distance_master";
		//$qryFinal ="SELECT dm.id, group_concat(dm.assets_id) as assets_id, group_concat(am.assets_name) as devices, CONVERT_TZ(dm.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, group_concat(dm.distance) as distance from distance_master dm left join assests_master am on am.id=dm.assets_id";
		$qryFinal ="SELECT dm.fuel_used, dm.id, dm.assets_id as assets_id, am.assets_name as devices, am.fuel_in_out_sensor,  CONVERT_TZ(dm.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, dm.distance as distance from distance_master dm left join assests_master am on am.id=dm.assets_id";
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-d");
			$edate = date("Y-m-d");
		}
		$sdate = $sdate." 00:00:00";
		$edate = $edate." 23:59:59";
		$qryFinal .= " WHERE CONVERT_TZ(dm.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		if($device == ''){	//search by device
			$device = '0';
		}
		$qryFinal .= " AND find_in_set(assets_id,'$device')";
		if($group != ""){
			$SQL .=" AND am.assets_group_id = $group";
		}
		$query = $this->db->query($qryFinal, FALSE);
		return $query->result();
	}
}