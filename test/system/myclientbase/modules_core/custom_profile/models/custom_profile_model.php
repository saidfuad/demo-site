<?php 
class custom_profile_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function custom_profile_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
    }
	function getAllData(){
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:3; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'id'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';         
		$start = $limit*$page - $limit; 
		$start = ($start<0)?0:$start; 
		$where = ""; 
		$searchField = isset($_GET['searchField']) ? $_GET['searchField'] : false;
		
		
		$searchOper = isset($_GET['searchOper']) ? $_GET['searchOper']: false;
		$searchString = isset($_GET['searchString']) ? $_GET['searchString'] : false;
		if($searchField=="status")
		{
			if(strtoupper($searchString)==strtoupper('active'))
				$searchString=1;
			else
				$searchString=0;
		}
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
	
		$SQL = "SELECT * FROM mst_custom_profile WHERE del_date is null";
	
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
		$SQL = "SELECT 	* from mst_custom_profile where del_date is null";

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
	
	public function validate() {
		
		$this->form_validation->set_rules('first_name', 'First Name');
		$this->form_validation->set_rules('last_name', 'Last Name');
		return parent::validate();

	}
	
	function save($db_array, $id=NULL, $set_flashdata = TRUE) {
		$success = TRUE;
		$menu_setting = isset($db_array ['menu_setting'])?$db_array ['menu_setting']:array();
		unset($db_array ['menu_setting']);
		$this->db->insert("mst_custom_profile", $db_array);
		$id= $this->db->insert_id();
		
		foreach($menu_setting as $menu_id=>$price){
			$setting_name = 'main';
			$data = array(
				"profile_id"=>$id,
				"menu_id"=>$menu_id,
				"price"=>$price,
				"setting_name"=>$setting_name,
				"add_uid"=>$this->session->userdata('user_id'),
				"add_date"=>date('Y-m-d H:i:s'),
				"status"=>1,
			);
			$this->db->insert("mst_custom_profile_setting", $data);
		}
		return $success;

	}
	public function get_json()
	{
		$query="select * from mst_country as cn left join mst_state as st on cn.id=st.FK_mst_country_p_id left join mst_city as ct on st.id=ct.FK_mst_state_p_id";
		$data = $this->db->query($query);
		return $data->result();
	}
	function subdata(){
		$row_id = $_GET["id"]; 
			
		$SQL = "SELECT ups.id, mm.menu_name as menu_id,ups.price from mst_custom_profile_setting ups left join main_menu_master mm on mm.id = ups.menu_id where ups.profile_id =".$row_id." and ups.del_date is null order by ups.id";
	
		$query = $this->db->query($SQL);
		
		$data = array();
		$data['result'] = $query->result();
		
		return $data;
	}
	
}
?>