<?php 
class Tooltip_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Tooltip_model() 
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_group = "group_master";
		$this->icon_master = "icon_master";
    }
	function getAllData(){
		
		$sdate = $this->input->get('tootip_sdate');
		$edate = $this->input->get('tootip_edate');
		
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-d");
			$edate = date("Y-m-d");
		}
		$sdate = $sdate." 00:00:00" ;
		$edate = $edate." 23:59:59" ;
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:3; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'smslog.user_id'; 
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
			
		  
		$SQL = "SELECT * from tooltip_msgs WHERE add_uid = ".$this->session->userdata('user_id')." and CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'"; 
		$SQL .= " ORDER BY id asc";
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
		
		$SQL = "SELECT * from tooltip_msgs WHERE add_uid = ".$this->session->userdata('user_id')." and CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'"; 
		
		if($where != "")   
			$SQL .= " AND $where";
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		//die($SQL);
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
	
	function save($db_array, $id=NULL, $set_flashdata = TRUE) {

		$success = TRUE;
		$this->db->query("group_master", $db_array);
		
		return $success;

	}
}
  