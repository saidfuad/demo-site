<?php 
class Rfid_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Rfid_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_rfid = "tbl_rfid";
		$this->assests_master = "assests_master";
    }
	function getAllData(){
		$user = $this->session->userdata('user_id');
		
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:3; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'user_id'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';         
		$start = $limit*$page - $limit; 
		$start = ($start<0)?0:$start; 

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
			
		$SQL = "SELECT * FROM ".$this->tbl_rfid." WHERE asset_id IN (SELECT assets_ids FROM user_assets_map WHERE user_id = ".$user.") and status=1";
		if($where != "")
			$SQL .= " AND $where";
			
		$result = $this->db->query($SQL);
		$count = $result->num_rows();
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);    
		} else {
			$total_pages = 0;
		}

		if ($page > $total_pages) 
			$page=$total_pages;
			 
		
		$SQL = "SELECT rf.id, rf.rfid, rf.person, am.assets_name, rf.inform_mobile, rf.inform_email, rf.send_sms, rf.send_email, rf.comments, lm.name as landmark_name FROM ".$this->tbl_rfid." rf LEFT JOIN ".$this->assests_master." am ON am.id = rf.asset_id LEFT JOIN landmark lm ON lm.id = rf.landmark_id WHERE rf.add_uid = ".$user." and rf.status=1 and rf.del_date is null";
		if($where != "")
			$SQL .= " AND $where";
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		$query = $this->db->query($SQL);
		
		$data = array();
		$data['result'] = $query->result();
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;
		return $data;
	}
	public function delete_rfid() 
	{
		$ids = $_POST["id"];
		$dt = gmdate("Y-m-d H:i:s");
		$delete_assets = $this->db->query("UPDATE ".$this->tbl_rfid." SET del_uid=".$this->session->userdata('user_id').", del_date='".$dt."', status=0 WHERE id in(".$ids.")");

		if($delete_assets) {
			return FALSE;
		} else {
			return TRUE;
		}
		
	}
	
	//flexigrid
	public function get_rfid() 
	{
		$user = $this->session->userdata('user_id');
		//Build contents query
		$this->db->select('rf.id, rf.rfid, rf.person, am.assets_name')->from($this->tbl_rfid." rf");
		$this->db->join($this->assests_master. " am", "am.id = rf.asset_id", 'LEFT');
		//$this->db->where('am.user_id', $this->session->userdata('user_id'));
		$this->db->where('asset_id IN (SELECT assets_ids FROM user_assets_map where user_id = '.$user.')');
		$this->db->order_by("rf.id", "DESC");
		
		$this->CI->flexigrid->build_query();
		
		//Get contents
		$return['records'] = $this->db->get();
		
		//Build count query
		$this->db->select('count(id) as record_count')->from($this->tbl_rfid);
		//$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('asset_id IN (SELECT assets_ids FROM user_assets_map where user_id = '.$user.')');
		$this->CI->flexigrid->build_query(FALSE);
		$record_count = $this->db->get();
		$row = $record_count->row();
		//Get Record Count
		$return['record_count'] = $row->record_count;
	
		//Return all
		return $return;
		
	}
	
	
	function save($db_array, $id=NULL, $set_flashdata = TRUE) {

		$success = TRUE;
		
		$user = $this->session->userdata('user_id');
		$id = uri_assoc('id');
		$this->db->insert($this->tbl_rfid, $db_array);
		$insert_id = $this->db->insert_id();

		return $success;

	}
	
	public function prepare_assets()
	{	
		$user = $this->session->userdata('user_id');
		$this->db->select('*');
		$this->db->where("status",1);
		$this->db->where("add_uid",$user);
		$this->order_by = 'id';
		$query = $this->db->get($this->assests_master);
		return $query->result();
	}
	public function prepare_landmark()
	{	
		$user = $this->session->userdata('user_id');
		$this->db->select('*');
		$this->db->where("status",1);
		$this->db->where("add_uid",$user);
		$this->order_by = 'id';
		$query = $this->db->get('landmark');
		return $query->result();
	}
	
}
?>