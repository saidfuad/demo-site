<?php 
class Device_down_emails_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Device_down_emails_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_emails = "device_down_email";
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
			
		
		$SQL = "SELECT * FROM device_down_email WHERE del_date is null";
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
			
		
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		$query = $this->db->query($SQL);
		
		$data = array();
		$data['result'] = $query->result();
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;
		return $data;
	}
	public function delete_emails()
	{
		$ids = $_POST["id"];
		$dt=gmdate("Y-m-d H:i:s");
		$delete_emails = $this->db->query("UPDATE device_down_email SET `status`=0, `del_date`='".$dt."' WHERE id in(".$ids.")");
		return TRUE;
	}
	
	public function get_emails() 
	{
		//Build contents query
		$uid = $this->session->userdata('user_id');
		
		$this->db->select('`id`, `device_down_email`,(SELECT emails_CONCAT(`assets_name`) FROM assests_master WHERE  find_in_set(`id`,`assets`)) as `assets`', false)->from($this->tbl_emails);
		$this->db->where('add_uid', $this->session->userdata('user_id'));
		
		str_replace(")`", ")", $this->CI->flexigrid->build_query());
		
		//Get contents
		$return['records'] = $this->db->get();
		//echo $this->db->last_query();
		//exit;
		//Build count query
		$this->db->select('count(id) as record_count')->from($this->tbl_emails);
		$this->db->where('add_uid', $this->session->userdata('user_id'));
		$this->CI->flexigrid->build_query(FALSE);
		$record_count = $this->db->get();
		$row = $record_count->row();
		//Get Record Count
		$return['record_count'] = $row->record_count;
	
		//Return all
		return $return;
		
	}
		
	public function validate() {
		
		$this->form_validation->set_rules('device_down_email', 'emails Name');
				
		return parent::validate();

	}
	
	function save($db_array, $id=NULL, $set_flashdata = TRUE) {

		$success = TRUE;
		$this->db->query("emails_master", $db_array);

		return $success;

	}
	
	public function checkemailsDuplicate($emailsName,$id)
	{
		$qry="select device_down_email from emails_master where ";

		if($id!="")
		{
			$qry.=" id!=".$id." AND ";
		} 
		$qry.=" device_down_email='".$emailsName."' AND status=1 And del_date is null And add_uid=".$this->session->userdata('user_id');
		$rarr=$this->db->query($qry);
		if($rarr->num_rows()<1)
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
}
?>