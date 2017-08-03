<?php 
class Address_book_group_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Address_book_group_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_assets_type = "addressbook_group";
		$this->icon_master = "icon_master";
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
		
		$user_id = $this->session->userdata('user_id');
		$SQL = "SELECT * FROM addressbook_group where add_uid=".$user_id." and status=1 and del_date is null";
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
			
		$SQL = "SELECT * from addressbook_group where add_uid=".$user_id." and status=1 and del_date is null";
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
	public function delete_assets() 
	{
		$ids = $_POST["id"];
		$date=gmdate("Y-m-d H:i:s");
		$delete_assets = $this->db->query("UPDATE `addressbook_group` SET status=0, del_uid=".$this->session->userdata('user_id').", del_date='".$date."' WHERE id in(".$ids.")");

		return TRUE;
	}
	
	//flexigrid
	public function get_assets() 
	{
		$user = $this->session->userdata('user_id');
		//Build contents query
		$this->db->select('am.id, am.assets_name, am.device_id, im.icon_path, am.sim_number')->from($this->tbl_assets_type." am");
		$this->db->join($this->icon_master. " im", "im.id = am.icon_id", 'LEFT');
		//$this->db->where('am.user_id', $this->session->userdata('user_id'));
		$this->db->where('find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = '.$user.'))');
		$this->db->order_by("am.id", "DESC");
		
		$this->CI->flexigrid->build_query();
		
		//Get contents
		$return['records'] = $this->db->get();
		
		//Build count query
		$this->db->select('count(id) as record_count')->from($this->tbl_assets_type);
		//$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = '.$user.'))');
		$this->CI->flexigrid->build_query(FALSE);
		$record_count = $this->db->get();
		$row = $record_count->row();
		//Get Record Count
		$return['record_count'] = $row->record_count;
	
		//Return all
		return $return;
		
	}
	
	public function validate() {
		
		$this->form_validation->set_rules('asset_name', 'Asset Name');
				
		return parent::validate();

	}
	
	function save($db_array, $id=NULL, $set_flashdata = TRUE) {

		$success = TRUE;
		
		$user = $this->session->userdata('user_id');
		$id = uri_assoc('id');
		$this->db->insert("addressbook_group", $db_array);
		return $success;

	}
	public function prepare_icon() 
	{		
		$this->db->select('*');
		$this->order_by = 'id';
		$query = $this->db->get('icon_master');
		return $query->result();
	}	
	//////////////////////
	public function get_new_locations() 
	{
		//Select table name
		$id = $_POST['id'];
		$device = uri_assoc('id');
		$query = $this->db->select()->from($this->tbl_assets_type)->where('phone_imei',$device)->where('id >',$id)->order_by('id');
		return $query->result();
	}
	public function get_links() 
	{
		//Select table name
		$this->db->select('phone_imei');
		$this->db->distinct();
		$query = $this->db->get($this->tbl_assets_type);
		// echo $this->db->last_query();
		return $query->result();
	}
	function check_Addess_Group()
	{
		$name = uri_assoc('nm');
		$id = uri_assoc('id')?uri_assoc('id'):-1;
	
		$this->db->where("group_name",$name);
		$this->db->where_not_in("id",$id);
		$this->db->where("status",1);
	//	$this->db->where("add_uid",$this->session->userdata('id'));
		//$this->db->where_not_in('id', $id);
		$this->db->from('addressbook_group');
		if($this->db->count_all_results()<1)
		{
			return "true";
		}
		else
		{
			return "false";
		}
		
	}
	
}
?>