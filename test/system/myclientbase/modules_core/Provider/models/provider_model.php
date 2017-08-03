<?php 
class Provider_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Provider_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->table = "app_menu_master";
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
			
		/*$this->db->select('count(user_id) as record_count')->from($this->tbl_users);
		$this->db->where('admin_id', $this->session->userdata('user_id'));
		$record_count = $this->db->get();
		$row = $record_count->row();
		$count = $row->record_count;
		*/
		$SQL = "SELECT * FROM ".$this->table." WHERE del_date is null and status = 1";
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

		if ($page > $total_pages)  $page=$total_pages;
			
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		
		$query = $this->db->query($SQL);
		
		$data = array();
		$data['result'] = $query->result();
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;
		return $data;
	}
	
	public function delete_provider($ids) 
	{

		$this->db->where("id",$ids);
		$data = array(
               'del_date' => date('Y-m-d H:i:s'),
               'del_uid' => $this->session->userdata('id'),
               'status' => 1
            );
		$this->db->update($this->table,$data);
		return "Successfully Deleted";
	}
	
		
	public function validate() {
		
		$this->form_validation->set_rules('first_name', 'First Name');
		$this->form_validation->set_rules('last_name', 'Last Name');
		return parent::validate();

	}
	
	function save($db_array, $id=NULL, $set_flashdata = TRUE) {

		$success = TRUE;
		$this->db->insert($this->table, $db_array);
		$insert_id = $this->db->insert_id();
		
		return $success;
	}
	function chk_provider($nm, $id=0)
	{
		//$this->db->where("provider",$nm);
		$this->db->where("del_date",null);
		$this->db->where("del_uid",null);
		$this->db->where_not_in("id",$id);
		$this->db->where("status",1);
		$this->db->from('app_menu_master');
		
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