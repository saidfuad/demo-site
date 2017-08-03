<?php 
class Routes_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Routes_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
    }
	function getAllData(){
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:3; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'id'; 
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
	

		$SQL = "SELECT count(*) as total FROM tbl_routes where status=1 and del_date is null and add_uid = ".$this->session->userdata('user_id');
	
		if($where != "")
			$SQL .= " AND $where";
		
		$result = $this->db->query($SQL);
		$data_arr=$result->result_array();
		$count = $data_arr[0]['total'];
		//$count = $this->db->count_all_results('tbl_users'); 
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
		$query = $this->db->get($this->tbl_users,$limit,$start);
		*/
	
		$SQL = "SELECT tr.id, tr.routename, tr.route_color, (select group_concat(name order by FIELD(id,find_in_set(id, tr.landmark_ids))) from landmark where find_in_set(id, tr.landmark_ids)) as landmark_ids, (SELECT GROUP_CONCAT(`assets_name`) FROM assests_master am WHERE find_in_set(am.current_trip,tr.id) and status='1') as deviceid, concat(tr.distance_value,' ', tr.distance_unit) as distance_value, tr.total_distance, tr.total_time_in_minutes, tr.round_trip, tr.comments, tr.sms_alert, tr.email_alert FROM tbl_routes tr where tr.status=1 and tr.add_uid = ".$this->session->userdata('user_id');
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

	public function delete_routes() 
	{
		$ids = $_POST["id"];
		$dt=gmdate("Y-m-d H:i:s"); 
		$tblUsr="UPDATE `tbl_routes` SET `status`=0, `del_date`='".$dt."', `del_uid`=".$this->session->userdata('user_id')."  WHERE id  in(".$ids.")";
		$this->db->query($tblUsr) or die("error");
		return TRUE;
	}
		
	public function validate() {
		
		$this->form_validation->set_rules('first_name', 'First Name');
		$this->form_validation->set_rules('last_name', 'Last Name');
		return parent::validate();

	}
	function save($formdata, $id) {

		$success = TRUE;
		//$this->db->where('polyid',$db_array['polyid']);
		//$this->db->update("tbl_routes", $db_array);
                
                $this->db->where('id',$id);
		$this->db->update("tbl_routes", $formdata);
       
	       if(isset($formdata['deviceid']))
	        {
		$device=$formdata['deviceid'];
		}else{
			$device="";
		}
		$this->db->query("UPDATE assests_master SET current_trip='null' WHERE current_trip=".$id."");
		if($device!="" && $device!=null)
		{
		$device1=explode(",",$formdata['deviceid']);
			//print_r($device1);
			//$i=0;
			$count1=count($device1);
                        //echo $count1;			
			$i=0;
			for($i=0;$i<$count1;$i++)
			{
			$this->db->query("update assests_master set current_trip = '".$id."' where assests_master.id ='".$device1[$i]."'");
                        }
                }   
	  return $success;

	}
	function getCountries()
	{
		$SQL = "SELECT id, name FROM mst_country";
		$query = $this->db->query($SQL);
		return $query->result_array();
	}
	function getCurrent($id)
	{
		if($id!="")
		{
			$SQL = "SELECT country FROM tbl_users where user_id=".$id;
			$query = $this->db->query($SQL);
			return $query->result_array();
		}
	}
	public function state(){
		$id=uri_assoc('id');
		$state=uri_assoc('state');
		if($id == 0 OR $id == "")
		{echo "<option value='' >Select State</option>";}else
		{
		$query="select id, name from mst_state where FK_mst_country_p_id='$id' AND  status= '1' AND del_uid is Null AND del_date is Null";
		$data = $this->db->query($query);

			echo "<option value='' >Select State</option>";

		foreach ($data->result() as $row)
			{
				if($state!="" && $state==$row->id)
					echo "<option value='".$row->id."' selected='selected' >".$row->name."</option>";
				else
					echo "<option value='".$row->id."' >".$row->name."</option>";
			}
		}
	}
	public function city(){
		$id=uri_assoc('id');
		$city=uri_assoc('city');
	
		if($id == 0 OR $id == "")
		{echo "<option value='' >Select City</option>";}else
		{
		$query="select id, name from mst_city where FK_mst_state_p_id='$id' AND  status= '1' AND del_uid is Null AND del_date is Null";
		$data = $this->db->query($query);

			echo "<option value='' >Select City</option>";

		foreach ($data->result() as $row)
			{
				if($city!="" && $city==$row->id)
					echo "<option value='".$row->id."' selected='selected' >".$row->name."</option>";
				else
					echo "<option value='".$row->id."' >".$row->name."</option>";
			}
		}
	}
	public function get_json()
	{
		$query="select * from mst_country as cn left join mst_state as st on cn.id=st.FK_mst_country_p_id left join mst_city as ct on st.id=ct.FK_mst_state_p_id";
		$data = $this->db->query($query);
		return $data->result();
	}
	public function checkUserDuplicate($user,$id)
	{
		$qry="select * from tbl_users where ";

		if($id!="")
		{
			$qry.=" user_id!=".$id." AND ";
		} 
		$qry.=" username='".$user."' AND status=1 and del_date is null";
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
	public function prepare_assets() 
	{		
		$user = $this->session->userdata('user_id');
		$this->db->select('*');
		$this->db->where('find_in_set(id, (select assets_ids from user_assets_map where user_id = '.$user.'))');
		$this->db->where('status',1);
		$this->db->where('del_date',null);
		$this->order_by = 'id';
		$query = $this->db->get('assests_master');
		return $query->result();
	}
	public function getAddressBookGroupList()
	{
		$user = $this->session->userdata('user_id');
		$this->db->select('id, group_name');
		$this->db->where('add_uid',$user);
		$this->db->where('status',1);
		$this->db->where('del_date',null);
		$this->order_by = 'id';
		$query = $this->db->get('addressbook_group');
		return $query->result();
	}
	public function prepare_LandmarkGroups_1()
	{
		$user = $this->session->userdata('user_id');
		$this->db->select('id, landmark_group_name');
		$this->db->where('user_id',$user);
		$this->order_by = 'id';
		$query = $this->db->get('landmark_group');
		return $query->result();
	}
	
}
?>