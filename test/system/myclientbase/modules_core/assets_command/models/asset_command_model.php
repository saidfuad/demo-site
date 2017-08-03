<?php 
class Asset_command_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Asset_command_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_assets  = "assests_category_master";
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
			
		$SQL = "SELECT acm.*, ac.assets_class_name as c_name FROM assests_command_master AS acm LEFT JOIN assests_class_master AS ac ON acm.assets_class_id = ac.id WHERE acm.status=1 AND acm.del_date IS NULL";
		
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
			
		$SQL = "SELECT acm.*, ac.assets_class_name as c_name FROM assests_command_master AS acm LEFT JOIN assests_class_master AS ac ON acm.assets_class_id = ac.id WHERE acm.status=1 AND acm.del_date IS NULL";
		
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
	public function delete_commands() 
	{
		$ids = $_POST["id"];
		//$date=date("Y-m-d H:i:s");
		$date = gmdate("Y-m-d H:i:s");
		$sql = "UPDATE `assests_command_master` SET status=0, del_uid=".$this->session->userdata('user_id').", del_date='".$date."' WHERE id in(".$ids.")";
		$delete_assets = $this->db->query($sql);
		
		return TRUE;
	}
	
	public function validate() {
		
		$this->form_validation->set_rules('command', 'Asset Command');
				
		return parent::validate();

	}
	
	function save($db_array, $id=NULL, $set_flashdata = TRUE) {

		$success = TRUE;
		
		$user = $this->session->userdata('user_id');
		$id = uri_assoc('id');
		$this->db->insert("assests_command_master", $db_array);
		$insert_id = $this->db->insert_id();
		
		return $success;

	}

	function fillClassCombo()
	{
		$qry="SELECT id, assets_class_name as name FROM `assests_class_master` WHERE status=1 AND del_date IS NULL";
		$query = $this->db->query($qry);
		$data=$query->result_array();
		return $data;
	}
	
	function check_assets_command()
	{
		$name = uri_assoc('nm');
		$id = uri_assoc('id')?uri_assoc('id'):-1;
	
		$this->db->where("command",$name);
		$this->db->where("status",1);
		$this->db->where_not_in("id",$id);
		$this->db->from('assests_command_master');
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