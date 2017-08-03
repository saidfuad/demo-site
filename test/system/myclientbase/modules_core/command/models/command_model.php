<?php 
class command_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function command_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
    }
	function getAllData(){
	
		$user_id = $this->session->userdata('user_id');
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
			
		$SQL = "SELECT count(*) as total FROM assests_command_master where status=1 and del_date is null";
		if($where != "")
			$SQL .= " AND $where";
		$result = $this->db->query($SQL);
		$data_arr=$result->result_array();
		
		$count = $data_arr[0]['total'];
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);    
		} else {
			$total_pages = 0;
		}

		if ($page > $total_pages) 
			$page=$total_pages;
			
		$SQL = "SELECT cm.*, class.assets_class_name FROM assests_command_master cm left join assests_class_master class on class.id = cm.assets_class_id where cm.status=1 and cm.del_date is null";
		if($where != "")
			$SQL .= " AND $where";
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		$query = $this->db->query($SQL);
		//die($SQL);
		$data = array();
		$data['result'] = $query->result();
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;
		return $data;
	}
	
	function save($db_array, $id=NULL, $set_flashdata = TRUE) {

		$success = TRUE;
		
		$user = $this->session->userdata('user_id');
		$id = uri_assoc('id');
		$this->db->insert("assests_command_master", $db_array);
		return $success;

	}
	function delete_command()
	{
		$ids = $_POST["id"];
		$date=gmdate("Y-m-d H:i:s");
		$delete_assets = $this->db->query("UPDATE `assests_command_master` SET status=0, del_uid=".$this->session->userdata('user_id').", del_date='".$date."' WHERE id in(".$ids.")");
		return TRUE;
	}
	public function prepare_assets_class()
	{
	  	$this->db->select('*');
	  	$this->db->where("status",1);
	 	$this->order_by = 'id';
		$query = $this->db->get('assests_class_master');
		return $query->result();
	}
}
?>