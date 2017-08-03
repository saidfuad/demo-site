<?php 
class Route_out_log_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Route_out_log_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_group = "group_master";
		$this->icon_master = "icon_master";
    }
	function get_map_data(){
		
		if($_REQUEST['cmd']=='route_out_log')
		{
			$user = $this->session->userdata('user_id');   
			$id = $this->input->get('id');
			$qry="select tl.id, tl.device_id, tl.trip_id, CONVERT_TZ(tl.date_time,'+00:00','".$this->session->userdata('timezone')."') as date_time, tl.lat, tl.lng, tl.distance, tl.on_route, concat(am.assets_name, concat('(',am.device_id,')')) as device_name, rm.routename as name, rm.distance_unit from route_out_log tl LEFT JOIN tbl_routes rm ON tl.trip_id = rm.id LEFT JOIN assests_master am ON am.id = tl.device_id where tl.id = $id";
			$query = $this->db->query($qry);
			return $query->result();	
		} 
		
	}
	function getAllData(){ 
		//$session_data = $this->session->all_userdata();
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		//$device = $this->input->get('device');
		$device=trim($this->input->get('device'),",");
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d H:i:s", strtotime($sdate." 00:00:00"));
			$edate = date("Y-m-d H:i:s", strtotime($edate." 23:59:59"));
		}else{
			$sdate = date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 00:00:00"));
			$edate = date("Y-m-d H:i:s",strtotime(date("Y-m-d")." 23:59:59"));
			//$edate = date("Y-m-d H:i:s");
		}
		
		$whereSearch = "";
		
		$whereSearch .=" AND CONVERT_TZ(tl.date_time,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		if($device!="")
		{
			$whereSearch .=" AND find_in_set(tl.device_id,'$device')";
		}
		$user = $this->session->userdata('user_id');
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
		//$SQL = "SELECT * FROM route_out_log where device_id in (select device_id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";
		$SQL = "select count(*) as total from route_out_log tl LEFT JOIN tbl_routes rm ON tl.trip_id = rm.id LEFT JOIN assests_master am ON am.id = tl.device_id where tl.device_id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";
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
		
		$SQL = "select tl.id, CONVERT_TZ(tl.date_time,'+00:00','".$this->session->userdata('timezone')."') as date_time, tl.distance, tl.on_route ,concat(am.assets_name, concat('(',am.device_id,')')) as device_name, rm.routename as name, rm.distance_unit from route_out_log tl LEFT JOIN tbl_routes rm ON tl.trip_id = rm.id LEFT JOIN assests_master am ON am.id = tl.device_id where tl.device_id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";
		
		if($whereSearch != "")
		{
			$SQL .= " $whereSearch ";
		}
		if($where != "")   
			$SQL .= " AND $where";
			
		$export_sql="";
		$export_sql=$SQL;
		
		if($cmd=="export")
		{
			$result = $this->db->query($export_sql);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=route_out_log". date("s").".xls"); 
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
			$fitr.="<th>Device Name</th>";
			$fitr.="<th>Route Name</th>";
			$fitr.="<th>Date Time</th>";
			$fitr.="<th>Distance From Route</th>"; 
			$fitr.="<th>On/Out</th>";
			$fitr .="</tr>"; 
			foreach($result->result_array() as $data)     
				{
					$date_time = $data['date_time'];
					$data['on_route'] = ($data['on_route'] == 0) ? "In" : "Out";
					
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data['device_name']."</td>"; 
					$EXCEL.="<td>".$data['name']."</td>";
					$EXCEL.="<td>&nbsp;".date("$date_format $time_format", strtotime($date_time))."</td>";
					$EXCEL.="<td>".$data['distance']."</td>";
					$EXCEL.="<td>".$data['on_route']."</td>";
					$EXCEL .="</tr>";
					$device_name = $data['device_name'];
				}
			if($this->session->userdata('id')==1)
				$count=3;
			else
				$count=2;
			
			echo "<table border='1'>";
			echo "<tr><th colspan='5'> Route Out Log</th></tr>";
			echo "<tr><th colspan='1'>Start Date</th><th colspan='1'>End Date</th><th colspan='3'>Assets Name</th></tr>";
			echo "<tr><th colspan='1'>&nbsp;".date("$date_format $time_format", strtotime($sdate))."</th><th colspan='1'>&nbsp;".date("$date_format $time_format", strtotime($edate))."</th><th colspan='3'></th></tr>";
			echo $fitr;
			echo $EXCEL;
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
?>