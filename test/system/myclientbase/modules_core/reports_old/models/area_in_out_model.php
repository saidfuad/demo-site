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

class Area_in_out_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function area_in_out_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "area_inout_log";
		$this->tbl_assets = "assests_master";
		$this->tbl_current = "tbl_last_point";
		$this->tbl_area = "areas";
    
    }
	function getAllData($cmd){
		
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		$area = $this->input->get('area');
		$group = $this->input->get('group');
		//$device = $this->input->get('device');
		$device=trim($this->input->get('device'),",");
		if($sdate != "" && $edate != ""){	//search by date
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
		
		
		$where = ""; 
		$searchField = isset($_GET['searchField']) ? $_GET['searchField'] : false;
		$searchOper = isset($_GET['searchOper']) ? $_GET['searchOper']: false;
		$searchString = isset($_GET['searchString']) ? $_GET['searchString'] : false;
		if($searchField=="tm.date_time")
			{
				$searchString=date("Y-m-d",strtotime($searchString));
			//	echo $searchString;
				//exit(0);
			}
			
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
			/*echo $searchField;
			exit(0);*/
				$where = "$searchField $ops '$searchString' "; 
			

		}
		$user = $this->session->userdata('user_id');   
		
		
		//$SQL = "SELECT count(distinct(tm.id)) as total FROM ".$this->table_name." as tm left join ".$this->tbl_assets." am on am.id = tm.device_id left join ".$this->tbl_area." as ta on tm.area_id=ta.polyid WHERE am.id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))"; 
		$sub = '';
		if($this->session->userdata('usertype_id') != 1){
			$sub = " and am.id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";
		}
		$SQL = "SELECT count(distinct(tm.id)) as total FROM ".$this->table_name." as tm left join ".$this->tbl_assets." am on am.id = tm.device_id left join ".$this->tbl_area." as ta on tm.area_id=ta.polyid WHERE 1 $sub"; 
		$SQL .= " AND tm.date_time BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		// CONVERT_TZ(tm.date_time,'+00:00','".$this->session->userdata('timezone')."')
		
		if($area != ""){
			$SQL .=" AND tm.area_id = '$area'";
		}
		if($group != ""){
			$SQL .=" AND am.assets_group_id = $group";
		}
		if($device != "")
		{
			//$SQL .=" AND tm.device_id = '".$device."'";
			$SQL .=" AND tm.device_id IN($device)";
		}else{
			return;
			die();
		}
		
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

				
		//$SQL = "SELECT distinct(tm.id), CONVERT_TZ(tm.date_time,'+00:00','".$this->session->userdata('timezone')."') as date, tm.inout_status as status, am.assets_name as device, ta.polyname as area FROM ".$this->table_name." as tm left join ".$this->tbl_assets." am on am.id = tm.device_id left join ".$this->tbl_area." as ta on tm.area_id=ta.polyid WHERE am.id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))"; 
		$sub = '';
		
		if($this->session->userdata('usertype_id') != 1){
			$sub = " and am.id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";
		}

		// CONVERT_TZ(tm.date_time,'+00:00','".$this->session->userdata('timezone')."') as date
		$SQL = "SELECT distinct(tm.id), tm.date_time as date, tm.inout_status as status, am.assets_name as device, ta.polyname as area FROM ".$this->table_name." as tm left join ".$this->tbl_assets." am on am.id = tm.device_id left join ".$this->tbl_area." as ta on tm.area_id=ta.polyid WHERE 1 $sub"; 
		$SQL .= " AND tm.date_time BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		// CONVERT_TZ(tm.date_time,'+00:00','".$this->session->userdata('timezone')."') 
		if($area != ""){
			$SQL .=" AND tm.area_id = '$area'";
		}
		if($group != ""){
			$SQL .=" AND am.assets_group_id = $group";
		}
		if($device != "")
		{
			//$SQL .=" AND tm.device_id = '".$device."'";
			$SQL .=" AND tm.device_id IN($device)";
		}else{
			return;
			die();
		}
		
		$SQL .= " ORDER BY $sidx $sord";
			
		$export_sql = $SQL;

		$SQL .= " LIMIT $start, $limit";
		
		$query = $this->db->query($SQL);
		
		if($cmd=="export") 
		{
			$result = $this->db->query($export_sql);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=area_in_out". date("s").".xls"); 
			$EXCEL = "";
			$fitr="";
			
			//session date & time format
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			$fitr .="<tr>"; 
			$fitr.="<th>Assets</th>";
			$fitr.="<th>Area</th>";
			$fitr.="<th>Date</th>";
			$fitr.="<th>Status</th>";
			$fitr .="</tr>"; 
			 
			foreach($result->result_array() as $data)
				{
					$Date = $data['date'];
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data['device']."</td>"; 
					$EXCEL.="<td>".$data['area']."</td>";
					//$EXCEL.="<td>".$data['add_date']."</td>";
					$EXCEL.="<td>&nbsp;".date("$date_format $time_format", strtotime($Date))."</td>"; 
					$EXCEL.="<td>".$data['status']."</td>";
					if($this->session->userdata('id')==1)
					{
						$EXCEL.="<td>".$data['Owner']."</td>";
					}
					$EXCEL .="</tr>";
					$device_name = $data['device'];
				}
				if($this->session->userdata('id')==1)
					$count=3;
				else
					$count=2;
				
				echo "<table border='1'>";
				echo "<tr><th colspan='4'> Area In Out</th></tr>";
				echo "<tr><th colspan='1'>Start Date</th><th colspan='1'>End Date</th><th colspan='2'>Assets Name</th></tr>";
				echo "<tr><th colspan='1'>&nbsp;".date("$date_format $time_format", strtotime($sdate))."</th><th colspan='1'>&nbsp;".date("$date_format $time_format", strtotime($edate))."</th><th colspan='2'></th></tr>";
				echo $fitr;
				echo $EXCEL;
				echo "</table>";
				die(); 
		}
		
		$data = array();
		$data['result'] = $query->result();
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;
		$data['sql'] = $SQL;
		return $data;
	}
	//this function for data display in grid
	public function get_allpoints() 
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
		
		$this->db->select("tm.id, CONVERT_TZ(tm.date_time,'+00:00','".$this->session->userdata('timezone')."') as add_date, am.assets_name, tm.device_id, tm.lati, tm.longi, tm.speed, tm.address")->from($this->table_name. " tm");
		$this->db->join($this->tbl_assets. " am", "am.device_id = tm.device_id", 'LEFT');
		$this->db->where("CONVERT_TZ(tm.date_time,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'");
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
		
		//$this->db->select('id,add_date,phone_imei,device_id,lati,longi,speed,address');
		//$this->db->select("id,CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."'),phone_imei,device_id,lati,longi,speed,address");
		$qry="id, CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."'), phone_imei, device_id, lati, longi, speed, address FROM ";
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-d");
			$edate = date("Y-m-d");
		}
		$sdate = $sdate." 00:00:00";
		$edate = $edate." 23:59:59";
		$qry.=" WHERE CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		//$this->db->where("CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'");
		if($device){
			//$this->db->where('device_id', $device);
			$qry.=" AND device_id=$device";
		}
		//$this->db->order_by('id');
		$qry.=" Order by id";
		//$query = $this->db->get($this->table_name);
		$query = $this->db->query($qry);
		return $query->result();
	}
	
	public function prepareCombo(){
		
		$user = $this->session->userdata('user_id');
		$this->db->select("assets_name, device_id", FALSE);
		//$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = '.$user.'))');
		$query = $this->db->get($this->tbl_assets);
		$option = "<option value=''>Please Select</option>";
		foreach ($query->result() as $row) {
              $option .= "<option value='".$row->device_id."'>".$row->assets_name." (".$row->device_id.")</option>";
        }		  
		return $option;
	}
	public function get_area($user) 
	{
		$query = $this->db->query("select * from areas where Audit_Status=1 AND Audit_Enter_uid = $user group by polyname asc");
		return $query->result();
		
	}
	function getLogData($cmd){
		
		// $sdate = $this->input->get('sdate');
		// $edate = $this->input->get('edate');
		$area = $this->input->get('area');
		$group = $this->input->get('group');
		//$device = $this->input->get('device');
		$device=trim($this->input->get('device'),",");
		/*
		if($sdate != "" && $edate != ""){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-d");
			$edate = date("Y-m-d");
		}
		$sdate = $sdate." 00:00:00";
		$edate = $edate." 23:59:59";
		*/
		
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:10; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'id'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';
		
		
		$where = ""; 
		$searchField = isset($_GET['searchField']) ? $_GET['searchField'] : false;
		$searchOper = isset($_GET['searchOper']) ? $_GET['searchOper']: false;
		$searchString = isset($_GET['searchString']) ? $_GET['searchString'] : false;
		
		if($searchField=="tm.date_time") {
				$searchString=date("Y-m-d",strtotime($searchString));
		}
			
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
			/*echo $searchField;
			exit(0);*/
				$where = "$searchField $ops '$searchString' "; 
			

		}
		$user = $this->session->userdata('user_id');   
		
		$sub = '';

		if($this->session->userdata('usertype_id') != 1){
			$sub = " AND am.id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";
		}
		
		$SQL ="SELECT count(am.assets_name) as total from ".$this->tbl_assets." am left join ".$this->tbl_current." lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null AND lm.area_id > 0 $sub";
		
		if($area != ""){
			$SQL .=" AND lm.area_id = '$area'";
		}
		if($group != ""){
			$SQL .=" AND am.assets_group_id = $group";
		}
		if($device != "")
		{
			//$SQL .=" AND tm.device_id = '".$device."'";
			$SQL .=" AND find_in_set(am.id,'$device')";
		}
		
		$query = $this->db->query($SQL, FALSE);
		$data_arr = $query->result_array();
		
		$totaldata = $data_arr[0]['total'];
		
		if($limit == "all"){
			$limit = $totaldata;
		}
		
		$lmt = $limit;
		
		if( $totaldata > 0 ) {
			$total_pages = ceil($totaldata/$limit);	
			if ($page > $total_pages) $page=$total_pages;
			$start = $limit*$page - $limit;	
		} else {
			$total_pages = 0;
			$start = 0;
		}
		
		$SQL = "SELECT am.assets_name, lm.current_area from ".$this->tbl_assets." am left join ".$this->tbl_current." lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null AND lm.area_id > 0 $sub";
		
		if($area != ""){
			$SQL .=" AND lm.area_id = '$area'";
		}
		if($group != ""){
			$SQL .=" AND am.assets_group_id = $group";
		}
		if($device != "")
		{
			$SQL .=" AND find_in_set(am.id,'$device')";
		}
		
		$SQL .= " ORDER BY $sidx $sord";
		
		$export_sql = $SQL;
		
		$SQL .= " LIMIT $start, $limit";
		
		if($cmd=="export") 
		{
			$result = $this->db->query($export_sql);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=area_in_out". date("s").".xls"); 
			$EXCEL = "";
			$fitr="";
			
			//session date & time format
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			$fitr .="<tr>"; 
			$fitr .="<th>Area</th>";
			$fitr .="<th>Assets</th>";
			$fitr .="</tr>"; 
			 
			foreach($result->result_array() as $data)
				{
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data['current_area']."</td>";
					$EXCEL.="<td>".$data['assets_name']."</td>"; 
					//$EXCEL.="<td>".$data['add_date']."</td>";
					$EXCEL .="</tr>";
				}
				echo "<table border='1'>";
				echo "<tr><th colspan='2'> Area Report</th></tr>";
				echo $fitr;
				echo $EXCEL;
				echo "</table>";
				die(); 
		}
		
		$query = $this->db->query($SQL);
		
		$data = array();
		$data['result'] = $query->result();
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $totaldata;
		$data['sql'] = $SQL;
		return $data;
	}
}
?>