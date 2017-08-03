<?php 
class Landmark_images_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Landmark_images_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_assets = "landmark_images";
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
			
		$SQL = "SELECT count(*) as total FROM landmark_images WHERE del_date is null and status=1 and add_uid=$user";
		if($where != "")
			$SQL .= " AND $where";
		$result = $this->db->query($SQL);
		$data=$result->result_array();
		$count = $data[0]['total'];
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);	 
		} else {
			$total_pages = 0;
		}

		if ($page > $total_pages) 
			$page=$total_pages;
			
		$SQL = "SELECT * FROM landmark_images WHERE del_date is null and status=1 and add_uid=$user";
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
		$dt = gmdate("Y-m-d H:i:s");
		$delete_assets = $this->db->query("UPDATE landmark_images SET del_uid=".$this->session->userdata('user_id').", del_date='".$dt."', status=0 WHERE id in(".$ids.")");
	
		return TRUE;
	}
	
	public function validate() {
		$this->form_validation->set_rules('image_path', "Image Path",'required');
		return parent::validate();
	}
	
	function save($db_array, $id=NULL, $set_flashdata = TRUE) {
		
		$success = TRUE;
		$id = uri_assoc('id');
		$this->db->where('id', $id);
		$this->db->update("landmark_images", $db_array);
		return $success;
	}
	
}
?>