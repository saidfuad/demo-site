<?php 
class trip_log_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function trip_log_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_group = "group_master";
		$this->icon_master = "icon_master";
    }
	function get_map_data(){
		if($_REQUEST['cmd']=='trip_log')
		{
			$id = $this->input->get('id');
			$qry="select ll.id, ll.device_id, ll.landmark_id, CONVERT_TZ(ll.date_time,'+00:00','".$this->session->userdata('timezone')."') as date_time, ll.lat, ll.lng, ll.distance, ll.in_out,l.name as landmark_name , am.assets_name as device_name from landmark_log ll left join assests_master am on am.id=ll.device_id left join landmark l on l.id = ll.landmark_id where ll.id = ".$id;
			$query = $this->db->query($qry);
			return $query->result();	
		 
		} 
		
	}
	function getsubData(){
		$id = $this->input->get('id');
		$res = $this->db->query("select tr.landmark_ids,tl.trip_id,tl.device_id, CONVERT_TZ(tl.start_time,'+00:00','".$this->session->userdata('timezone')."') as start_time, CONVERT_TZ(tl.end_time,'+00:00','".$this->session->userdata('timezone')."') as end_time from trip_log tl left join tbl_routes tr on tr.id=tl.trip_id where tl.id=$id AND tr.landmark_ids is not NULL");
		$data = array();
		
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:3; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'id'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';         
		$start = $limit*$page - $limit;
		$start = ($start<0)?0:$start; 
		
		foreach($res->result() as $row)
		{
			$QUERY ="select ll.id, l.name, ll.device_id, CONVERT_TZ(ll.date_time,'+00:00','".$this->session->userdata('timezone')."') as date_time, ll.distance  from landmark_log ll left join landmark l on l.id = ll.landmark_id where ll.landmark_id in(".$row->landmark_ids.") and ll.device_id = ".$row->device_id." and CONVERT_TZ(ll.date_time,'+00:00','".$this->session->userdata('timezone')."') between '".date('Y-m-d H:i:s',strtotime($row->start_time))."' and '".date('Y-m-d H:i:s',strtotime($row->end_time))."'";
			$result = $this->db->query($QUERY);
			$count = $result->num_rows();
			$QUERY .= " ORDER BY $sidx $sord LIMIT $start, $limit";
			$result = $this->db->query($QUERY);
			if( $count > 0 ) {
				$total_pages = ceil($count/$limit);    
			} else {
				$total_pages = 0;
			}

			if ($page > $total_pages) 
				$page=$total_pages;
			
			
			$SQL = $this->db->query($QUERY);
			$data['result'] = $SQL->result();
			$data['page'] = $page;
			$data['total_pages'] = $total_pages;
			$data['count'] = $count;
		}
		return $data; 
	}
	function getAllData(){
		
		$sdate = $this->input->get('triplogsdate');
		$edate = $this->input->get('triplogedate');
		$device=trim($this->input->get('device'),",");
		$user = $this->session->userdata('user_id');
		
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
				
		$SQL = "select count(*) as total from trip_log tl LEFT JOIN tbl_routes rm ON tl.trip_id = rm.id LEFT JOIN assests_master am ON am.id = tl.device_id";
		
		$SQL .=" where CONVERT_TZ(tl.start_time,'+00:00','".$this->session->userdata('timezone')."') Between  '" . $sdate . "' AND '" . $edate . "' and tl.device_id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))"; 
		
		if($device != "")
		{
			$SQL .=" AND find_in_set(tl.device_id,'$device')";
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
		} else {
			$total_pages = 0;
		}

		if ($page > $total_pages) 
			$page=$total_pages;
		
		
		$SQL = "select tl.id, TIME_TO_SEC(TIMEDIFF(tl.end_time , tl.start_time)) as time_taken, CONVERT_TZ(tl.start_time,'+00:00','".$this->session->userdata('timezone')."') as start_time, CONVERT_TZ(tl.end_time,'+00:00','".$this->session->userdata('timezone')."') as end_time, concat(am.assets_name, concat('(',am.device_id,')')) as device_name, rm.routename as name , tl.id as trip_id from trip_log tl LEFT JOIN tbl_routes rm ON tl.trip_id = rm.id LEFT JOIN assests_master am ON am.id = tl.device_id";
		
		$SQL .=" where CONVERT_TZ(tl.start_time,'+00:00','".$this->session->userdata('timezone')."') Between  '" . $sdate . "' AND '" . $edate . "' and tl.device_id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))"; 
		if($device != "")
		{
			$SQL .=" AND find_in_set(tl.device_id,'$device')";
		}else{
			return;
			die();
		}
		
		if($where != "")   
			$SQL .= " AND $where";
			
		$export_sql="";
		$export_sql=$SQL;
	
		
		if($cmd=="export") 
		{
			$result = $this->db->query($export_sql);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=trip_log". date("s").".xls"); 
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
			$fitr.="<th>Device</th>";
			$fitr.="<th>Trip Name</th>";
			$fitr.="<th>Start Time</th>";
			$fitr.="<th>End Time</th>";
			$fitr.="<th>Total Time</th>";
			$fitr .="</tr>"; 
			foreach($result->result_array() as $data)
				{
					//time count 
					$seconds = $data['time_taken'];
					$hours = floor($seconds / (60 * 60));
					$divisor_for_minutes = $seconds % (60 * 60);
					$minutes = floor($divisor_for_minutes / 60);
					$data['time_taken'] = '';
					if($hours > 0)
						$data['time_taken'] .= $hours." Hour,";
					if($minutes > 0)	
						$data['time_taken'] .= $minutes." Min";
						
					$start_time = $data['start_time'];
					$end_time = $data['end_time'];
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data['device_name']."</td>"; 
					$EXCEL.="<td>".$data['name']."</td>";
					//$EXCEL.="<td>".$data['start_time']."</td>";
					//$EXCEL.="<td>".$data['end_time']."</td>";
					$EXCEL.="<td>&nbsp;".date("$date_format $time_format", strtotime($start_time))."</td>";
					$EXCEL.="<td>&nbsp;".date("$date_format $time_format", strtotime($end_time))."</td>";
					$EXCEL.="<td>".$data['time_taken']."</td>";
					
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
			echo "<tr><th colspan='5'> Trip Log</th></tr>";
			echo "<tr><th colspan='2'>Start Date</th><th colspan='2'>End Date</th><th colspan='1'>Assets Name</th></tr>";
			echo "<tr><th colspan='2'>&nbsp;".date("$date_format $time_format", strtotime($sdate))."</th><th colspan='2'>&nbsp;".date("$date_format $time_format", strtotime($edate))."</th><th colspan='1'>$nbsp;</th></tr>";
			echo $fitr;
			echo $EXCEL;
			die();
		}
		
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		//die($SQL);
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
	public function get_map_data_all()
	{
		$id = uri_assoc('id');
		$query = $this->db->query("select device_id, start_time, end_time from trip_log where id = ".$id);
		$data=$query->result_array();
		
		$device=$data[0]['device_id'];
		$sdate=$data[0]['start_time'];
		$edate=$data[0]['end_time'];
		$qry="SELECT id, CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') AS add_date, phone_imei, device_id, lati, longi, speed, address FROM tbl_track";
		//$this->db->select('id,add_date,phone_imei,device_id,lati,longi,speed,address');
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-d");
			$edate = date("Y-m-d");
		}
		$sdate = $sdate." 00:00:00";
		$edate = $edate." 23:59:59";
		$qry .=" WHERE date(add_date) BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		if($device){
			$qry .=" AND assets_id=$device";
		}
		$qry .=" Order By id";
		$query = $this->db->query($qry);
		return $query->result();
	}
	public function get_assets_name()
	{
		$id = uri_assoc('id');
		$query = $this->db->query("select assets_name from assests_master as am left join trip_log as tl on am.id=tl.device_id where tl.id = ".$id);
		$data=$query->result_array();
		return $data;
	}
}