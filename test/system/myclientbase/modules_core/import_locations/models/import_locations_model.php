<?php 
class Import_locations_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Import_locations_model()
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
			
		$SQL = "SELECT count(*) as total FROM tbl_cell_data";
		if($where != "")
			$SQL .= " where $where";
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
			
		$SQL = "SELECT id, cell_id, lac, latitude, longitude, address, add_date FROM tbl_cell_data";
		if($where != "")
			$SQL .= " where $where";
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
		//$user = $this->session->userdata('user_id');
		//$id = uri_assoc('id');
		
		$db_array['longitude'];
		$db_array['address'];
		$db_array['add_date'];
		$qry="INSERT INTO tbl_cell_data values(NULL, NULL, NULL,'".$db_array['latitude']."', '".$db_array['longitude']."', '".$db_array['address']."', '".$db_array['add_date']."')";
		$this->db->query($qry);
		
		return $success;
	}
	function delete_driver_master()
	{
		$ids = $_POST["id"];
		$date=date("Y-m-d H:i:s");
		$delete_assets = $this->db->query("delete from tbl_cell_data WHERE id in(".$ids.")");
		return TRUE;
	}
	function checkDuplicate(){
		$id=uri_assoc('id');
		$lat=$_REQUEST['latitude'];
		$lng=$_REQUEST['longitude'];
		if($id!=""){
			$rs=$this->db->query("SELECT * FROM tbl_cell_data where latitude='$lat' and longitude='$lng' and id!=$id");
		}else{
			$rs=$this->db->query("SELECT * FROM tbl_cell_data where latitude='$lat' and longitude='$lng'");
		}
		
		return $rs->num_rows();
	}
}
?>