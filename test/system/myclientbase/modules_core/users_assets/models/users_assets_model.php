<?php 
class Users_assets_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Users_assets_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl = "user_assets_map";
    }
	function getAllData(){
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
			
		$SQL = "SELECT uam.id, CONCAT(um.first_name,' ',um.last_name) as user,(SELECT GROUP_CONCAT(`assets_name`) FROM assests_master WHERE  find_in_set(`id`,`assets_ids`)) as `assets` FROM user_assets_map uam left join tbl_users um on um.user_id = uam.user_id WHERE um.del_date is null and um.status=1";
		if($this->session->userdata('usertype_id')!=1){
			$SQL .= " AND add_uid = ".$this->session->userdata('user_id');
		}
		if($where != "")
			$SQL .= " AND $where";
		$result = $this->db->query($SQL);
		$count = $result->num_rows();
		//$count = $this->db->count_all_results('tbl_users'); 
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);    
		} else {
			$total_pages = 0;
		}

		if ($page > $total_pages) 
			$page=$total_pages;
			
		$SQL = "SELECT uam.id, CONCAT(um.first_name,' ',um.last_name) as user, (SELECT GROUP_CONCAT(`group_name`) FROM group_master WHERE find_in_set(`id`,`group_id`)) as `group`, (SELECT GROUP_CONCAT(`assets_name`) FROM assests_master WHERE  find_in_set(`id`,`assets_ids`)) as `assets` FROM user_assets_map uam left join tbl_users um on um.user_id = uam.user_id WHERE um.del_date is null and um.status=1";
	//	die($SQL);
		if($this->session->userdata('usertype_id')!=1){
			$SQL .= " AND add_uid = ".$this->session->userdata('user_id');
		}
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
	public function delete_users() 
	{
		$ids = $_POST["id"];
		$delete_group = $this->db->query('DELETE FROM user_assets_map WHERE id in('.$ids.')');		
		return TRUE;
	}
	
	public function get_users_assets() 
	{
		//Build contents query
		$uid = $this->session->userdata('user_id');
		
		$this->db->select('uam.id, um.first_name, um.last_name,(SELECT GROUP_CONCAT(`assets_name`) FROM assests_master WHERE  find_in_set(`id`,`assets_ids`)) as `assets`', false)->from($this->tbl." uam");
		$this->db->join('tbl_users um', 'um.user_id = uam.user_id', 'LEFT');
		$this->db->where('add_uid', $this->session->userdata('user_id'));		
		str_replace(")`", ")", $this->CI->flexigrid->build_query());
		
		//Get contents
		$return['records'] = $this->db->get(); 
		//echo $this->db->last_query();
		//exit;
		//Build count query
		$this->db->select('count(id) as record_count')->from($this->tbl);
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
	public function prepare_assets($grps) 
	{		
		/*
		$user = $this->session->userdata('user_id');
		$this->db->select('*');
		if($this->session->userdata('usertype_id')!=1){
			$this->db->where('find_in_set(id, (select assets_ids from user_assets_map where user_id = '.$user.'))');
		}
		$this->db->where('status',1);
		$this->db->where('del_date',null);
		$this->order_by = 'id';
		$query = $this->db->get('assests_master');
		*/
		if($grps != '') {
			$sql = "SELECT id, assets_name, device_id FROM assests_master WHERE assets_group_id IN ($grps) AND status = 1 AND del_date IS NULL order by assets_name ASC";
			$query = $this->db->query($sql);
			return $query->result();
		}
		else {
			return array();
		}
	}

	public function get_group() 
	{
		$sql = "select id, group_name from group_master where status=1 AND del_date is null ORDER by group_name ASC";
		$query = $this->db->query($sql);
		return $query->result();
		
	}
	
	public function get_user_name($id) 
	{		
		$this->db->select('*');
		$this->db->where('user_id', $id);
		$query = $this->db->get('tbl_users');
		return $query->result();
	}
}
?>