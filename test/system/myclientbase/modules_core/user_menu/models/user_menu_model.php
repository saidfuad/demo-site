<?php 
class User_menu_model extends Model 
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
			$SQL = "select * from app_menu_master where del_date is NULL AND status = 1 ";
		}
		else{ 
			//$SQL = "select tu.user_id as id,CONCAT(tu.first_name,' ',tu.last_name) as user_name from tbl_users tu where tu.usertype_id !=1 and user_id = '$user_name'";
			$SQL = "select * from app_menu_master where del_date is NULL AND user_id = '$user_name' and FIND_IN_SET (menu_id,(select GROUP_CONCAT(id) from main_menu_master where del_date is null and status=1))";
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
		
		$SQL = "select * from app_menu_master where del_date is NULL and user_id = '$user_name' and FIND_IN_SET (menu_id,(select GROUP_CONCAT(id) from main_menu_master where del_date is null and status=1))";
		
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
		
		$this->form_validation->set_rules('group_name', 'Group Name');
		
		return parent::validate();

	}
	
	function save($db_array, $id=NULL) {
		
		$success = TRUE;
		//$this->db->delete('app_menu_master', array('user_id' => $db_array['user_id'])); 
	//die(print_r($db_array));
		if(isset($db_array['menu_group']))
		{
			
			for($i=0;$i<count($db_array['menu_group']);$i++)
			{
				$data = array(
				   
				   'status' => isset($db_array['menu_group'])?"1":"0",
				   
				  'where_to_show' => isset($db_array['where_to_show'])?$db_array['where_to_show']:"link",
				  
				  'priority' => isset($db_array['priority'])?$db_array['priority']:"NULL",
				   
				); 
				//die(print_r($data));
				//$this->db->insert('app_menu_master', $data); 
				$this->db->where('id', uri_assoc('id'));
				$this->db->update('app_menu_master', $data); 
				
				//edit prioriyt in main menu master
				$data_main = array(
				  'priority' => isset($db_array['priority'])?$db_array['priority']:"NULL",
				);  
				$this->db->where('id', $db_array['menu_group']);
				$this->db->update('main_menu_master', $data_main); 
			} 
		} 
		else  
		{
			 
					$data = array(
					'status' => isset($db_array['menu_group'])?"1":"0",
					
				   'where_to_show' => isset($db_array['where_to_show'])?$db_array['where_to_show']:"link",
				    
					'priority' => isset($db_array['priority'])?$db_array['priority']:"NULL",
				  
				);
				//die(print_r($data));
				//$this->db->insert('app_menu_master', $data); 
				$this->db->where('id', uri_assoc('id'));
				$this->db->update('app_menu_master', $data); 
		
		}
		return $success;
	}
}
?>  