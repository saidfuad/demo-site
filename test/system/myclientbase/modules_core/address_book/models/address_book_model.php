<?php 
class Address_book_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Address_book_model()
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
			
		$SQL = "SELECT count(*) as total FROM addressbook as ab left join addressbook_group as ag on ab.group_id=ag.id where ab.add_uid=".$user_id." and ab.status=1 and ab.del_date is null";
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
			
		$SQL = "SELECT ab.*,ag.group_name as group_name FROM addressbook as ab left join addressbook_group as ag on ab.group_id=ag.id where ab.add_uid=".$user_id." and ab.status=1 and ab.del_date is null";
		if($where != "")
			$SQL .= " AND $where";
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		$query = $this->db->query($SQL);
	//	die($SQL);
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
		$this->db->insert("addressbook", $db_array);
		
		return $success;

	}
	function get_Groups($group_id)
	{
		$user_id = $this->session->userdata('user_id');
		$opt="<option value=''>Please Select</option>";
		$SQL="select id,group_name from addressbook_group where add_uid=".$user_id." and del_date is null and status=1";
		$query = $this->db->query($SQL);
		
		foreach($query->result() as $row)
		{
			$opt.="<option value='".$row->id."' ";
			if($row->id==$group_id)
				$opt.=" selected='selected'";
			$opt.=">".$row->group_name."</option>";
		}
		return $opt;
	}
	function delete_addressbook()
	{
		$ids = $_POST["id"];
		$date=gmdate("Y-m-d H:i:s");
		$delete_assets = $this->db->query("UPDATE `addressbook` SET status=0, del_uid=".$this->session->userdata('user_id').", del_date='".$date."' WHERE id in(".$ids.")");
		return TRUE;
	}
}
?>