<?php 
class Landmarks_waypoints_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Landmarks_waypoints_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_landmarks_waypoints = "tbl_landmarks_waypoints";
    }
	function getAllData(){
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:3; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'tlw.id'; 
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
			
		/*$this->db->select('count(user_id) as record_count')->from($this->tbl_landmarks_waypoints);
		$this->db->where('admin_id', $this->session->userdata('user_id'));
		$record_count = $this->db->get();
		$row = $record_count->row();
		$count = $row->record_count;
		*/
	
		$SQL = "SELECT count(*) as total FROM tbl_landmarks_waypoints WHERE del_date is null AND add_uid = ".$this->session->userdata('user_id');
	
		if($where != "")
			$SQL .= " AND $where";
		$result = $this->db->query($SQL);
		$data_arr=$result->result_array();
		
		$count = $data_arr[0]['total'];
		//$count = $this->db->count_all_results('tbl_landmarks_waypoints'); 
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);    
		} else {
			$total_pages = 0;
		}

		if ($page > $total_pages) 
			$page=$total_pages;
			
		/*$this->db->select('user_id,first_name,last_name');
		$this->db->limit($limit);
		if($where != "")
			$this->db->where($where,NULL,FALSE);
		$this->db->order_by($sidx,$sord);
		$query = $this->db->get($this->tbl_landmarks_waypoints,$limit,$start);
		*/
		$SQL = "SELECT tlw.id, tlw.waypoint_name, (select name from landmark where id=tlw.landmark1) as landmark1, (select name from landmark where id=tlw.landmark2) as landmark2 FROM tbl_landmarks_waypoints tlw WHERE tlw.del_date is null AND tlw.status=1 AND tlw.add_uid = ".$this->session->userdata('user_id');
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
	public function delete_landmarks_waypoints() 
	{
		$ids = $_POST["id"];
		$dt = gmdate("Y-m-d H:i:s");
		$tblUsr="UPDATE `tbl_landmarks_waypoints` SET `status`=0, `del_date`='".$dt."', `del_uid`=".$this->session->userdata('user_id')."  WHERE id in(".$ids.")";
		$this->db->query($tblUsr) or die("error");
		
		return TRUE;
	}
	function checkDuplicate_way(){
		$user=$this->session->userdata('user_id');
		$id=uri_assoc('id');
		$waypoint_name=$_REQUEST['waypoint_name'];
		$landmark1=$_REQUEST['landmark1'];
		$landmark2=$_REQUEST['landmark2'];
		$qry_dup="SELECT * FROM tbl_landmarks_waypoints WHERE id!=$id and (landmark1=$landmark1 or landmark1=$landmark2) AND (landmark2=$landmark1 or landmark2=$landmark2) AND waypoint=(SELECT waypoint FROM tbl_landmarks_waypoints WHERE id=$id) AND status=1 and del_date is null and add_uid=$user";
		$rs=$this->db->query($qry_dup) or die("error");

		if(count($rs->result())>0){
			return false;
		}else{
			return true;
		}
	}
}
?>
