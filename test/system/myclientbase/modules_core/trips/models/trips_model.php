<?php 
class Trips_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Trips_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_trips = "trips_master";
		$this->icon_master = "icon_master";
    }
	function getAllData(){
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:3; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'user_id'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';         
		$start = $limit*$page - $limit; 
		$start = ($start<0)?0:$start; 

		$where = ""; 
		$having= "";
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
			if($searchField == "assets")
			{
				$having="HAVING assets "."$ops '$searchString' ";
			}
			else
			{
				$where = "$searchField $ops '$searchString' "; 
			}
		}

		if(!$sidx) 
			$sidx =1;
			
		
		//$SQL = "SELECT tr.id ,tr.routename, (SELECT GROUP_CONCAT(assets_name) FROM assests_master as am WHERE  find_in_set(device_id,tr.deviceid)) as assets FROM  tbl_routes as tr WHERE tr.status=1 and tr.del_date is null and tr.add_uid = ".$this->session->userdata('user_id');
		$SQL = "SELECT count(*) as total FROM tbl_routes as tr left join assests_master as am on am.status=1 and find_in_set(am.current_trip,tr.id)  WHERE tr.status=1 and tr.del_date is null and tr.add_uid = ".$this->session->userdata('user_id');
		
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
			
		$SQL = "SELECT tr.id ,tr.routename, GROUP_CONCAT(am.assets_name) as assets FROM  tbl_routes as tr left join assests_master as am on am.status=1 and find_in_set(am.current_trip,tr.id)  WHERE tr.status=1 and tr.del_date is null and tr.add_uid = ".$this->session->userdata('user_id');
		
		if($where != "")
			$SQL .= " AND $where";
		$SQL .= " GROUP BY tr.id $having ORDER BY $sidx $sord LIMIT $start, $limit";
		//DIE($SQL);
		$query = $this->db->query($SQL);
		
		$data = array();
		$data['result'] = $query->result();
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;
		return $data;
	}
	public function delete_trips() 
	{
		$ids = $_POST["id"];
		$dt=gmdate("Y-m-d H:i:s");

		$delete_group = $this->db->query("UPDATE tbl_routes SET `status`=0, `del_date`='".$dt."', `del_uid`=".$this->session->userdata('user_id')." WHERE id in(".$ids.")");		
		return TRUE;
	}
	
	public function get_trips() 
	{
		//Build contents query
		$uid = $this->session->userdata('user_id');
		
		$this->db->select("SELECT tr.id ,tr.routename, (SELECT GROUP_CONCAT(assets_name) FROM assests_master as am WHERE  find_in_set(device_id,tr.deviceid)) as assets FROM  tbl_routes as tr WHERE tr.status=1 and tr.del_date is null and tr.add_uid = ".$uid);
		$this->db->where('add_uid', $uid);
		
		str_replace(")`", ")", $this->CI->flexigrid->build_query());
		
		//Get contents
		$return['records'] = $this->db->get();
		//echo $this->db->last_query();
		//exit;
		//Build count query
		$this->db->select('count(id) as record_count')->from($this->tbl_trips);
		$this->db->where('add_uid', $this->session->userdata('user_id'));
		$this->CI->flexigrid->build_query(FALSE);
		$record_count = $this->db->get();
		$row = $record_count->row();
		//Get Record Count
		$return['record_count'] = $row->record_count;
	
		//Return all
		return $return;
		
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
	public function prepare_assets() 
	{		
		$user = $this->session->userdata('user_id');
		$this->db->select('*');
		$this->db->where('find_in_set(id, (select assets_ids from user_assets_map where user_id = '.$user.'))');
		$this->order_by = 'id';
		$query = $this->db->get('assests_master');
		return $query->result();
	}		
	public function assets(){
		$id=uri_assoc('id');
		if($id!="")
		{
			$this->db->select('deviceid');
			$this->db->where('id',$id);
			$query = $this->db->get('tbl_routes');
			$arr=$query->result_array();
			return $arr[0]['deviceid'];
		}
	}
	public function save_1($dbarr, $id)
	{
		if(isset($dbarr['deviceid'])){
		$device=$dbarr['deviceid'];
		}else{
			$device="";
		}
		$this->db->query("UPDATE assests_master SET current_trip='null' WHERE current_trip=".$id."");
		if($device!="" && $device!=null){
			$this->db->query("UPDATE assests_master SET current_trip=".$id." WHERE device_id in(".$device.")");
		}
		return;
	}
	public function getRouteNames($routenm)
	{
		$SQL = "SELECT tr.id ,tr.routename FROM  tbl_routes as tr  WHERE tr.status=1 and tr.del_date is null and tr.add_uid = ".$this->session->userdata('user_id');
		$query = $this->db->query($SQL);
		$opt="";
		foreach ($query->result_array() as $row) {
		//	die(print_r($row));
				$opt .= '<option value="'.$row['id'].'"';
				if($row['routename'] == $routenm)
					$opt .= ' selected="selected"';
				$opt .= '>'.$row['routename'].'</option>';
			}
		return $opt;
	}
	function getIds($id)
	{
		$SQL = "SELECT device_id, assets_name from assests_master as am left join tbl_routes as tr on FIND_IN_SET(am.id,tr.deviceid) WHERE am.status=1 and tr.id=".$id;
		$query = $this->db->query($SQL);
		$data=$query->result_array();
		
		$SQL = "SELECT device_id from assests_master where current_trip=".$id;
		$query = $this->db->query($SQL);
		$dt=$query->result_array();
		$dta=array();
		foreach($dt as $rs)
		{
			$dta[]=$rs['device_id'];
		}
		$opt="";
		foreach($data as $row)
		{
			$opt .= "<option value='".$row['device_id']."'";
				if(in_array($row['device_id'], $dta))
				$opt .= " selected='selected'";
			$opt .= ">".$row['assets_name']." (".$row['device_id'].")</option>";
		}
		
		return $opt;
	}
}
?>