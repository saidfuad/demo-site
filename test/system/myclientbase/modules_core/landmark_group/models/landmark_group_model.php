<?php 
class Landmark_group_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Landmark_group_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_assets_type = "assests_type_master";
		$this->icon_master = "icon_master";
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
			
		$SQL="select count(*) as total from landmark_group where user_id = $user";
	
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
			
		$SQL="select id, landmark_group_name ,landmark_group_name as landmark_group_list from landmark_group where user_id = $user";
	
		if($where != "")
			$SQL .= " AND $where";
		$SQL .= " ORDER BY landmark_group_name $sord LIMIT $start, $limit";		
			
		$query = $this->db->query($SQL);
		foreach($query->result() as $key =>$row)
		{
			$res = $this->db->query("select Group_ConCAT(name) as name from landmark where group_id= ".$row->id);
			if($res->num_rows>0)
			{
				$row1 = $res->result();
				$row->landmark_group_list = $row1[0] ->name;
			}
		//	die(var_dump($row));
		}

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
		$query=$this->db->query("select name from landmark where group_id in($ids)");
		$result=$query->result_array();
		for($i=0;$i<count($result);$i++)
		{
			$query=$this->db->query("update landmark set group_id='0' where name='".$result[$i]['name']."'");
		}
		$delete_assets = $this->db->query("UPDATE `landmark_group` SET user_id=0 where id in($ids)");
		
		/*
		$query = $this->db->query("SELECT * from user_assets_map where find_in_set($asset_id, assets_ids)", FALSE);
		$rows = $query->result();
		
		foreach ($rows as $row) {
			$assets_ids = $row->assets_ids;
			$assets_ids = str_replace($asset_id, "", $assets_ids);
			$assets_ids = str_replace(",,", ",", $assets_ids);
			$assets_ids = rtrim($assets_ids, ',');
			$assets_ids = ltrim($assets_ids, ',');
			$this->db->query('update user_assets_map set assets_ids = "'.$assets_ids.'" WHERE id='.$row->id);
		}
		*/
		return TRUE;
	}
	
	//flexigrid
	public function get_assets() 
	{
		$user = $this->session->userdata('user_id');
		//Build contents query
		$this->db->select('am.id, am.assets_name, am.device_id, im.icon_path, am.sim_number')->from($this->tbl_assets_type." am");
		$this->db->join($this->icon_master. " im", "im.id = am.icon_id", 'LEFT');
		//$this->db->where('am.user_id', $this->session->userdata('user_id'));
		$this->db->where('find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = '.$user.'))');
		$this->db->order_by("am.id", "DESC");
		
		$this->CI->flexigrid->build_query();
		
		//Get contents
		$return['records'] = $this->db->get();
		
		//Build count query
		$this->db->select('count(id) as record_count')->from($this->tbl_assets_type);
		//$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = '.$user.'))');
		$this->CI->flexigrid->build_query(FALSE);
		$record_count = $this->db->get();
		$row = $record_count->row();
		//Get Record Count
		$return['record_count'] = $row->record_count;
	
		//Return all
		return $return;
		
	}
	
	public function validate() {
		
		$this->form_validation->set_rules('asset_name', 'Asset Name');
				
		return parent::validate();

	}
	
	function save($db_array, $id=NULL, $set_flashdata = TRUE) {

		$success = TRUE;
		
		$user = $this->session->userdata('user_id');
		$id = uri_assoc('id');
		$this->db->insert("landmark_group", $db_array);
		return $success;

	}
	public function prepare_icon() 
	{		
		$this->db->select('*');
		$this->order_by = 'id';
		$query = $this->db->get('icon_master');
		return $query->result();
	}	
	//////////////////////
	public function get_new_locations() 
	{
		//Select table name
		$id = $_POST['id'];
		$device = uri_assoc('id');
		$query = $this->db->select()->from($this->tbl_assets_type)->where('phone_imei',$device)->where('id >',$id)->order_by('id');
		return $query->result();
	}
	public function getdefault_user($landmark_group_name,$id){
				$lquery="select id from landmark_group where landmark_group_name='".$landmark_group_name."'";
				$lq=$this->db->query($lquery);
				$landmark_id=$lq->result_array();
				$current_is=$landmark_id[0]['id'];
				$lanquery="select name from landmark where group_id = '".$current_is."'";
				$lqa=$this->db->query($lanquery);
				$landmark_id=$lqa->result_array();
					for($i=0;$i<=count($landmark_id) - 1;$i++)
					{
								$ldequery="update landmark set group_id = '0' where name = '".$landmark_id[$i]['name']."'";
								$ldefq=$this->db->query($ldequery);
					}	
			
	}
	public function setdefault_user($landmark_group_name,$landmark_name,$id){

		if(empty($id))
		{
			$lquery="select id from landmark_group where landmark_group_name='".$landmark_group_name."'";
			$lq=$this->db->query($lquery);
			$landmark_id=$lq->result_array();
			$current_is=$landmark_id[0]['id'];
			if(count($landmark_name) == 1)
			{
				$lquery="update landmark set group_id = '".$current_is."' where name = '".$landmark_name[0]."'";
				$lq=$this->db->query($lquery);
			}else{
				for($i=0;$i<=count($landmark_name) - 1 ;$i++)
				{
					$lquery="update landmark set group_id = '".$current_is."' where name = '".$landmark_name[$i]."'";
					$lq=$this->db->query($lquery);
				}
			}
			}else{

				$lquery="select id from landmark_group where landmark_group_name='".$landmark_group_name."'";
				$lq=$this->db->query($lquery);
				$landmark_id=$lq->result_array();
				$current_is=$landmark_id[0]['id'];
				$lanquery="select name from landmark where group_id = '".$current_is."'";
				$lqa=$this->db->query($lanquery);
				$landmark_id=$lqa->result_array();
					for($i=0;$i<=count($landmark_id) -1 ;$i++)
					{
								$ldequery="update landmark set group_id = '0' where name = '".$landmark_id[$i]['name']."'";
								$ldefq=$this->db->query($ldequery);
					}	
			
					if(count($landmark_name) == 0)
					{
					}else{
					if(count($landmark_name) == 1)
					{
						$lquery="update landmark set group_id = '".$current_is."' where name = '".$landmark_name[0]."'";
						$lq=$this->db->query($lquery);
					}else{
						for($i=0;$i<=count($landmark_name) - 1 ;$i++)
						{
							$lquery="update landmark set group_id = '".$current_is."' where name = '".$landmark_name[$i]."'";
							$lq=$this->db->query($lquery);
						}
					}	
				}
			}
	}

	public function get_links() 
	{
		//Select table name
		$this->db->select('phone_imei');
		$this->db->distinct();
		$query = $this->db->get($this->tbl_assets_type);
		// echo $this->db->last_query();
		return $query->result();
	}
	function check_landmark_name()
	{
		$name = uri_assoc('nm');
	
		$this->db->where("landmark_group_name",$name);
		$this->db->from('landmark_group');
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