<?php 
class Landmarks_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Landmarks_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
    }
	function getAllData($cmd){
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
	
		$SQL = "SELECT count(*) as total FROM landmark as lm where lm.status=1 and del_date is null and lm.add_uid = ".$this->session->userdata('user_id');
	
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
		
	
		$SQL = "SELECT lm.id, lm.name, lm.address, concat(lm.radius,' ',lm.distance_unit) as radius, lm.device_ids, (SELECT GROUP_CONCAT(`assets_name`) FROM assests_master WHERE find_in_set(id,lm.device_ids) and status='1') as `assets`, lm.icon_path, lm.addressbook_ids, (SELECT GROUP_CONCAT(`name`) FROM addressbook WHERE find_in_set(id,lm.addressbook_ids)) as address_book_nm, lm.comments, gm.landmark_group_name, lm.sms_alert, lm.email_alert, lm.alert_before_landmark FROM landmark lm LEFT JOIN landmark_group gm on lm.group_id=gm.id where lm.status=1 and del_date is null and lm.add_uid = ".$this->session->userdata('user_id');
		if($where != "")
			$SQL .= " AND $where";
		$export_sql = $SQL;
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		$query = $this->db->query($SQL);
		
		if($cmd=="export")   
		{
			$result = $this->db->query($export_sql);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=Landmarks.xls"); 
			$EXCEL = ""; 
			$fitr="";
			
			//session date & time format 
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			$fitr .="<tr>"; 
			$fitr.="<th>Landmark Name</th>";
			$fitr.="<th>Address</th>";
			$fitr.="<th>Radius</th>";
			$fitr.="<th>Group</th>";
			$fitr.="<th>Sms Alert</th>";
			$fitr.="<th>Email Alert</th>";
			$fitr .="</tr>"; 
			//var_dump($result);
			foreach($result->result_array() as $data)
				{
					
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data['name']." </td>"; 
					$EXCEL.="<td>".$data['address']."</td>";
					$EXCEL.="<td>".$data['radius']."</td>";
					$EXCEL.="<td>".$data['landmark_group_name']."</td>";
					if($data['sms_alert'] == 0)
						$data['sms_alert'] = 'No';
					else
						$data['sms_alert'] = 'Yes';
					$EXCEL.="<td>".$data['sms_alert']."</td>";
					
					if($data['email_alert'] == 0)
						$data['email_alert'] = 'No';
					else
						$data['email_alert'] = 'Yes';
					$EXCEL.="<td>".$data['email_alert']."</td>";
					$EXCEL .="</tr>";
					$device_name = $data['assets_id'];
				}
			if($this->session->userdata('id')==1)
				$count=3;
			else
				$count=2;
			
			echo "<table border='1'>";
			
			echo "<tr><th colspan='6'>Landmarks</th></tr>";
			echo $fitr;
			echo $EXCEL;
			echo "</table>";
			die(); 
		}
		
		$data = array();
		$data['result'] = $query->result();
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;
		return $data;
	}

	public function delete_landmarks() 
	{
		$ids = $_POST["id"];
		$dt=gmdate("Y-m-d H:i:s");
		$tblUsr="UPDATE `landmark` SET `status`=0, `del_date`='".$dt."', `del_uid`=".$this->session->userdata('user_id')." WHERE id in(".$ids.")";
		$this->db->query($tblUsr) or die("error");
		return TRUE;
	}
		
	public function validate() {
		
		$this->form_validation->set_rules('first_name', 'First Name');
		$this->form_validation->set_rules('last_name', 'Last Name');
		return parent::validate();

	}
	public function menu_entery_user($user_id,$nm){
		$query=$this->db->query("select user_id from tbl_users where username = '".$nm."'");
		$result=$query->result_array();
		$newuser_id=$result[0]['user_id'];
		$menu_query=$this->db->query("select * from app_menu_master where user_id = $user_id and del_date is null");
		$i=0;
		$colget=$this->db->query("SHOW COLUMNS FROM app_menu_master");
		$colresult=$colget->result_array();
		$allcolumn="";
		foreach($colresult as $row)
		{
			if($colget->num_rows() - 1== $i)
			{
				$allcolumn .=$colresult[$i]['Field'];
			}else{
				$allcolumn .=$colresult[$i]['Field'].",";
			}
			$i++;
			
		}
		
		foreach($menu_query->result_array() as $row)
		{			
			$row['id']."<br>";
			$menu_id=$row['menu_id'];
			$priority=$row['priority'];
			$where_slow=$row['where_to_show'];
			$suser_id=$row['user_id'];
			$admin_id=$row['add_uid'];
			$add_date=$row['add_date'];
			$status=$row['status'];
			$comments=$row['Comments'];	
			$inserquery=$this->db->query("insert into app_menu_master(".$allcolumn.") values('',$menu_id,$priority,'$where_slow',$newuser_id,$user_id,'$add_date',null,null,$status,'$comments')");
		}

		
	}	
	function save($db_array, $id=NULL, $set_flashdata = TRUE) {
		$success = TRUE;
		if($this->session->userdata('usertype_id') == 1){
			$db_array['usertype_id'] = 2;
			$db_array['global_admin'] = 1;
		}else{
			$db_array['usertype_id'] = 3;
		}
		$db_array['date_format'] = $this->session->userdata('date_format');
		$db_array['time_format'] = $this->session->userdata('time_format');
		$db_array['date_format'] = "d-m-y";
		$db_array['time_format'] = "h:i:s a";
		$db_array['photo '] = "not_available.jpg";
		$this->db->insert("tbl_users", $db_array);
		$insert_id = $this->db->insert_id();
		
		$ua_array['user_id'] = $insert_id;
		$ua_array['add_uid'] = $this->session->userdata('user_id');
		
		$ua_array['add_date'] = gmdate('Y-m-d H:i:s');
		$settings['refresh_time'] = 10;
		$settings['add_uid'] = $insert_id;
		
		$dashboard1['report_ids'] = 1;
		$dashboard1['rpt_color'] = 'color-orange';
		$dashboard1['rpt_status'] = 'max';
		$dashboard1['userid'] = $insert_id;
		
		$dashboard2['report_ids'] = 2;
		$dashboard2['rpt_color'] = 'color-green';
		$dashboard2['rpt_status'] = 'max';
		$dashboard2['userid'] = $insert_id;
		
		$this->db->insert("user_assets_map", $ua_array);
		$this->db->insert("tbl_user_settings", $settings);
		$this->db->insert("tbl_dashboard_mst", $dashboard1);
		$this->db->insert("tbl_dashboard_mst", $dashboard2);
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
	public function getLandmarkList(){
		$user = $this->session->userdata('user_id');
		$this->db->select('id, name,address');
		//$this->db->where('user_id',$user);
		$this->db->where('add_uid',$user);
		$this->db->where('del_uid',NULL);
		$this->db->where('del_date',NULL);
		$this->order_by = 'id';
		$query = $this->db->get('landmark');
		return $query->result();
	}
	public function updateLandmarkRadius($landmarks,$radius,$distance_unit){
		if(count($landmarks)>0 && $radius!="" and $distance_unit!=""){
			$ids=implode(",",$landmarks);
			$user = $this->session->userdata('user_id');
			$qry="Update landmark set radius=$radius,  distance_unit='$distance_unit' where id in($ids)";
			$rarr=$this->db->query($qry);
			return true;
		}else{
			return false;
		}
	}
	public function getIconPaths(){
		$user=$this->session->userdata("user_id");
		$qry="select image_path from landmark_images where status=1 and del_date is null and (add_uid=1 or add_uid=$user)";
		$rarr=$this->db->query($qry);
		return $rarr->result();
	}	
}
?>