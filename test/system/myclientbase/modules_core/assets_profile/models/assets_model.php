<?php 
class Assets_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Assets_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_group = "group_master";
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
			
		
		$SQL = "SELECT * FROM assets_profile_master where status=1 and del_date is null and add_uid = '".$this->session->userdata('user_id')."'";
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
			
		
		$SQL = "SELECT * FROM assets_profile_master where status=1 and del_date is null and add_uid = '".$this->session->userdata('user_id')."'";
		
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
	public function delete_assets_profile() 
	{
		$ids = $_POST["id"];
		$dt = gmdate("Y-m-d H:i:s");
		$delete_group = $this->db->query("UPDATE assets_profile_master SET del_date='".$dt."', status=0, del_uid=".$this->session->userdata('user_id')." WHERE id in(".$ids.")");		
		return TRUE;
	}
	
	public function get_group() 
	{
		//Build contents query
		$uid = $this->session->userdata('user_id');
		
		$this->db->select('`id`, `group_name`,(SELECT GROUP_CONCAT(`assets_name`) FROM assests_master WHERE  find_in_set(`id`,`assets`)) as `assets`', false)->from($this->tbl_group);
		$this->db->where('add_uid', $this->session->userdata('user_id'));
		
		str_replace(")`", ")", $this->CI->flexigrid->build_query());
		
		//Get contents
		$return['records'] = $this->db->get();
		//echo $this->db->last_query();
		//exit;
		//Build count query
		$this->db->select('count(id) as record_count')->from($this->tbl_group);
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
	public function chk_profile_nm($val,$id)
	{	
		$wh="";
		if($val!=="")
		{
			$wh="AND profile_name='".$val."'";
			if($id!="")
			{
				$wh.=" AND id!=".$id;
			}
		}
		$SQL = "SELECT * FROM assets_profile_master where status=1 and del_date is null and add_uid = '".$this->session->userdata('user_id')."'".$wh;
		$query = $this->db->query($SQL);
		if($query->num_rows()>0)
			return "Duplicate Profile name not allowed";
		else
			return "true";
		//return $query->num_rows();
	}
}
?>