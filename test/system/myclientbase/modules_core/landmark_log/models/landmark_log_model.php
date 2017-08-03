<?php 
class Landmark_log_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Landmark_log_model()
    {
       parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_assets_type = "landmark_log";
		$this->icon_master = "icon_master";
    }
	function get_map_data(){
		if($_REQUEST['cmd']=='landmark_log')
		{
			$id = $this->input->get('id');
			$query = $this->db->query("SELECT lg.*,concat(am.assets_name,'(',am.device_id,')') as device_name, lm.name as landmark_name from landmark_log as lg LEFT JOIN landmark as lm ON lg.landmark_id = lm.id LEFT JOIN assests_master as am ON am.id = lg.device_id where lg.id=".$id);
			return $query->result();	
		} 
		
	}
	function getAllData(){
		
		$session_data = $this->session->all_userdata();
		$user = $this->session->userdata('user_id');
		
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
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
		$whereSearch = "";
	
		//$whereSearch .=" AND CONVERT_TZ(lg.date_time,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'"; 
		$whereSearch .=" AND lg.date_time BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		if(!empty($device) || $device != "")
		{
			$whereSearch .=" AND find_in_set(am.id,'$device')";
		}else{
			return;
			die();
		}
		
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:3; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'id'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';         
		$start = $limit*$page - $limit; 
		$start = ($start<0)?0:$start; 
		$cmd=uri_assoc('cmd');

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
			$sidx =1;
		
		$user = $this->session->userdata('user_id');		
		//$SQL = "SELECT * FROM landmark_log";
		//$SQL = "SELECT count(*) as total from landmark_log as lg LEFT JOIN landmark as lm ON lg.landmark_id = lm.id LEFT JOIN assests_master as am ON am.id = lg.device_id where lg.device_id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";
		$sub = '';
		if($this->session->userdata('usertype_id') != 1){	
			$sub = " and lg.device_id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";
		}
		$SQL = "SELECT count(*) as total from landmark_log as lg LEFT JOIN landmark as lm ON lg.landmark_id = lm.id LEFT JOIN assests_master as am ON am.id = lg.device_id where 1 $sub";

		if($whereSearch != "")
		{
			$SQL .= " $whereSearch ";
		}
		
		if($where != "")
			$SQL .= " AND $where";
		$result = $this->db->query($SQL);
		$data_arr=$result->result_array();
		
		$count = $data_arr[0]['total'];
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);    
		} else {
			$total_pages = 0;
		}

		if ($page > $total_pages) 
			$page=$total_pages;
			
		//$SQL = "SELECT lg.*,concat(am.assets_name,".("am.device_id").") as device_name, lm.name as landmark_name from landmark_log as lg LEFT JOIN landmark as lm ON lg.landmark_id = lm.id LEFT JOIN assests_master as am ON am.device_id = lg.device_id where lg.id=$user";
		
		//$SQL = "SELECT lg.id, lg.device_id, lg.landmark_id, CONVERT_TZ(lg.date_time,'+00:00','".$this->session->userdata('timezone')."') as date_time, lg.lat, lg.lng, lg.distance, lg.in_out,concat(am.assets_name, concat('(',am.device_id,')')) as device_name, lm.name as landmark_name from landmark_log as lg LEFT JOIN landmark as lm ON lg.landmark_id = lm.id LEFT JOIN assests_master as am ON am.id = lg.device_id where lg.device_id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";
		$sub = '';
		if($this->session->userdata('usertype_id') != 1){	
			$sub = " and lg.device_id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";
		}

		// CONVERT_TZ(tm.date_time,'+00:00','".$this->session->userdata('timezone')."') as date_time
		$SQL = "SELECT lg.id, lg.device_id, lg.landmark_id, lg.date_time as date_time, lg.lat, lg.lng, lg.distance, lg.in_out,concat(am.assets_name, concat('(',am.device_id,')')) as device_name, lm.name as landmark_name from landmark_log as lg LEFT JOIN landmark as lm ON lg.landmark_id = lm.id LEFT JOIN assests_master as am ON am.id = lg.device_id where 1 $sub";
		
		if($whereSearch != "")
		{
			$SQL .= " $whereSearch ";
		}
		
		if($where != "")
			$SQL .= " AND $where";
		
		$SQL .= " ORDER BY $sidx $sord";

		$export_sql=$SQL;
		
		$SQL .= " LIMIT $start, $limit";

		
		if($cmd=="export") 
		{  
			$result = $this->db->query($export_sql);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=landmark_log". date("s").".xls"); 
			$EXCEL = "";
			$fitr="";

			//session date & time format
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			if($this->session->userdata('id')==1)
			{
				$fitr.="<th>Owner</th>";
			}
			$fitr .="<tr>"; 
			$fitr.="<th>Assests Name(Device)</th>";
			$fitr.="<th>Landmark Name</th>";
			$fitr.="<th>Date Time</th>";
			$fitr.="<th>Distance</th>";
			$fitr .="</tr>"; 
			foreach($result->result_array() as $data)
				{
					$date_time = $data['date_time']; 
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data['device_name']."</td>"; 
					$EXCEL.="<td>".$data['landmark_name']."</td>";
					//$EXCEL.="<td>".$data['date_time']."</td>";
					$EXCEL.="<td>&nbsp;".date("$date_format $time_format", strtotime($date_time))."</td>";
					$EXCEL.="<td>".$data['distance']."</td>";
					if($this->session->userdata('id')==1)
					{
						$EXCEL.="<td>".$data['Owner']."</td>";
					}
					$EXCEL .="</tr>"; 
					$device_name = $data['device_name'];
				} 
			if($this->session->userdata('id')==1)  
				$count=3;  
			else
				$count=2;
		
			echo "<table border='1'>";
			echo "<tr><th colspan='4'> Landmark Log</th></tr>";
			echo "<tr><th colspan='1'>Start Date</th><th colspan='1'>End Date</th><th colspan='2'>Assets Name</th></tr>";
			echo "<tr><th colspan='1'>&nbsp;".date("$date_format $time_format", strtotime($sdate))."</th><th colspan='1'>&nbsp;".date("$date_format $time_format", strtotime($edate))."</th><th colspan='2'></th></tr>";
			echo $fitr;
			echo $EXCEL;
			die(); 
		} 
		
		$query = $this->db->query($SQL);  
		
		$data = array();
		$data['result'] = $query->result();
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;
		return $data;
		
	}
	
	public function validate() { 
		
		$this->form_validation->set_rules('group_name', 'Group Name');
		
		return parent::validate();

	}
	
	function save($db_array, $id=NULL, $set_flashdata = TRUE) {

		$success = TRUE;
		$this->db->query("group_master", $db_array);
		
		return $success;

	} 
}