<?php 
class Asset_category_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Asset_category_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_assets = "assests_category_master";
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
			
			IF($searchField=="assets_status"){
				if(strtoupper($searchString)=="INACTIVE")
				{
					$searchString='0';
				}
				else
				{
					$searchString='1';
				}
			}
			$where = "$searchField $ops '$searchString' "; 

		}

		if(!$sidx) 
			$sidx =1;
			
		$SQL = "SELECT cm.*,tm.assets_type_nm as t_name FROM assests_category_master as cm left join assests_type_master as tm on cm.assets_type_id=tm.id where cm.status=1 and cm.del_date is null";
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
			
		$SQL = "SELECT cm.*,tm.assets_type_nm as t_name, cm.assets_cat_image as icon_path FROM assests_category_master as cm left join assests_type_master as tm on cm.assets_type_id=tm.id where cm.status=1 and cm.del_date is null";
		if($where != "")
			$SQL .= " AND $where";
			//die($SQL);
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
		//$date=date("Y-m-d H:i:s");
		$date = gmdate("Y-m-d H:i:s");
		$delete_assets = $this->db->query("UPDATE `assests_category_master` SET status=0, del_uid=".$this->session->userdata('user_id').", del_date='".$date."' WHERE id in(".$ids.")");
		
		/*
		$query = $this->db->query("SELECT * from user_assets_map where find_in_set($asset_id, assets_ids)", FALSE);
		$rows = $query->result();
		
		foreach ($rows as $row) {
			$assets_ids = $row->assets_ids;
			$assets_ids = str_replace($asset_id, "", $assets_ids);
			$assets_ids = str_replace(",,", ",", $assets_ids);
			$assets_ids = rtrim($assets_ids, ',');
			$assets_ids = ltrim($assets_ids, ',');
			$this->db->query('update user_assets_map set assets_ids = "'.$assets_ids.'" WHERE id='.$row->id);
		}
		*/
		return TRUE;
	}
	
	//flexigrid
	public function get_assets() 
	{
		$user = $this->session->userdata('user_id');
		//Build contents query
		$this->db->select('am.id, am.assets_name, am.device_id, im.icon_path, am.sim_number')->from($this->tbl_assets." am");
		$this->db->join($this->icon_master. " im", "im.id = am.icon_id", 'LEFT');
		//$this->db->where('am.user_id', $this->session->userdata('user_id'));
		$this->db->where('find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = '.$user.'))');
		$this->db->order_by("am.id", "DESC");
		
		$this->CI->flexigrid->build_query();
		
		//Get contents
		$return['records'] = $this->db->get();
		
		//Build count query
		$this->db->select('count(id) as record_count')->from($this->tbl_assets);
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
		$this->db->insert("assests_category_master", $db_array);
		$insert_id = $this->db->insert_id();
		
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
		$query = $this->db->select()->from($this->table_name)->where('phone_imei',$device)->where('id >',$id)->order_by('id');
		return $query->result();
	}
	public function get_links() 
	{
		//Select table name
		$this->db->select('phone_imei');
		$this->db->distinct();
		$query = $this->db->get($this->table_name);
		// echo $this->db->last_query();
		return $query->result();
	}
	
	
	function filltypecombo()
	{
		$qry="select id, assets_type_nm as name from `assests_type_master` where status=1 and del_date is null";
		$query = $this->db->query($qry);
		$data=$query->result_array();
		return $data;
	}
	
	function check_assets_category()
	{
		$name = uri_assoc('nm');
		$id = uri_assoc('id')?uri_assoc('id'):-1;
	
		$this->db->where("assets_cat_name",$name);
		$this->db->where("status",1);
		$this->db->where_not_in("id",$id);
		$this->db->from('assests_category_master');
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