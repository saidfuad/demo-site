<?php 
class Top_menu_master_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function user_menu_model()  
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
    }
	function getAllData(){
		$user_name = $this->input->get('user_name');
		
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
		
		if($user_name == ""){
			//$SQL = "select tu.user_id as id,CONCAT(tu.first_name,' ',tu.last_name) as user_name from tbl_users tu where tu.usertype_id !=1";
			$SQL = "select * from top_menu_master where del_date is NULL AND status = 1 and  Find_IN_SET(menu_id,(select Group_concat(id) from top_main_menu_master where status=1))";
		}
		else{ 
			//$SQL = "select tu.user_id as id,CONCAT(tu.first_name,' ',tu.last_name) as user_name from tbl_users tu where tu.usertype_id !=1 and user_id = '$user_name'";
			$SQL = "select * from top_menu_master where del_date is NULL AND user_id = '$user_name' and Find_IN_SET(menu_id,(select Group_concat(id) from top_main_menu_master where status=1))";
		}
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
		$SQL = "select * from top_menu_master where del_date is NULL and user_id = '$user_name' and status=1 and Find_IN_SET(menu_id,(select Group_concat(id) from top_main_menu_master where status=1))";
		
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
	public function check_status(){
		$id=$_REQUEST['id'];
		$query=$this->db->query("select status from top_menu_master where id=$id");
		$data = $query->result_array();
		echo $data[0]['status'];
		die();
		
	}
	public function validate() {
		
		$this->form_validation->set_rules('group_name', 'Group Name');
		
		return parent::validate();

	}
	 
	function save($db_array, $id=NULL) {
		
		$success = TRUE;
		$menu_id=$db_array['menu_id'];
		$status=$db_array['status'];
		$data = array(
               'status' => $status
            );
		$this->db->where('id', $menu_id);
		$this->db->update('top_menu_master', $data); 
		return $success;
		}
}
?>  