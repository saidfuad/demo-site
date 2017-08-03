<?php 
class Users_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Users_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_users = "tbl_users";
    }

	function AddCopyUsers(){
	
		$primary_user = $_REQUEST['primary_user'];
		$secondary_user = $_REQUEST['secondary_user'];
		
		$SQL = "select id, assets_ids, user_id from user_assets_map where del_date is NULL AND status = 1 and user_id = $primary_user";
		$result = $this->db->query($SQL);
		foreach($result->result_array() as $data){
			$assets_list = $data["assets_ids"];
		}
		
		for($js=0;$js<count($secondary_user);$js++)
		{
			$ids = $secondary_user[$js];
			
			// USER ASSETS MAP
			$data_area = array();
			$delete_tbl_event_master = $this->db->query("UPDATE `user_assets_map` SET assets_ids='".$assets_list."' WHERE user_id = $ids");
			
			//	AREA
			$delete_tbl_event_master = $this->db->query("UPDATE `areas` SET Audit_Status='0' WHERE Audit_Enter_uid = $ids");
			
			$query = $this->db->query("select * from  areas where Audit_Status = 1 and Audit_Enter_uid = $primary_user");
			foreach ($query->result() as $row)
			{
			  $data_area[] = "('".mysql_real_escape_string($row->deviceid)."', '".mysql_real_escape_string($row->polyid)."', '".mysql_real_escape_string($row->polyname)."', '".mysql_real_escape_string($row->color)."', '".mysql_real_escape_string($row->lat)."', '".mysql_real_escape_string($row->lng)."', '".mysql_real_escape_string($row->pointid)."', '".mysql_real_escape_string($row->in_alert)."', '".mysql_real_escape_string($row->out_alert)."', '".mysql_real_escape_string($row->speed_alert)."', '".mysql_real_escape_string($row->speed_value)."', '".mysql_real_escape_string($row->speed_unit)."', '".mysql_real_escape_string($row->addressbook_ids)."', '".mysql_real_escape_string(date("Y-m-d H:i:s"))."', '".mysql_real_escape_string($ids)."', '".mysql_real_escape_string(1)."', '".mysql_real_escape_string($row->Audit_Comment)."', '".mysql_real_escape_string($row->email_alert)."', '".mysql_real_escape_string($row->sms_alert)."', '".mysql_real_escape_string($row->area_type_opt)."')";

			  /*
			   $data_area[] = array( 'deviceid' =>  $row->deviceid,
				   'polyid' 	 =>  $row->polyid,
				   'polyname' =>  $row->polyname,
				   'color' =>  $row->color,
				   'lat' =>  $row->lat,
				   'lng' =>  $row->lng,
				   'pointid' =>  $row->pointid,
				   'in_alert' =>  $row->in_alert,
				   'out_alert' =>  $row->out_alert,
				   'speed_alert' =>  $row->speed_alert,
				   'speed_value' =>  $row->speed_value,
				   'speed_unit' =>  $row->speed_unit,
				   'addressbook_ids'	 =>  $row->addressbook_ids,
				   'Audit_Enter_Dt'	 =>  date("Y-m-d H:i:s"),
				   'Audit_Enter_uid'	 =>  $ids,
				   'Audit_Status'	 =>  1,
				   'Audit_Comment'	 =>  $row->Audit_Comment,
				   'email_alert'	 =>  $row->email_alert,
				   'sms_alert'	 =>  $row->sms_alert,
				   'area_type_opt'	 =>  $row->area_type_opt,
				);
			  */
			}
			if(count($data_area) > 0) {
			  $ins = "INSERT INTO area ( `deviceid`, `polyid`, `polyname`, `color`, `lat`, `lng`, `pointid`, `in_alert`, `out_alert`, `speed_alert`, `speed_value`, `speed_unit`, `addressbook_ids`, `Audit_Enter_Dt`, `Audit_Enter_uid`, `Audit_Status`, `Audit_Comment`, `email_alert`, `sms_alert`, `area_type_opt`) VALUES ".implode(',', $data_area);
			  $this->db->query($ins);
			}
			print('Done');
			exit;

			/*
			if(count($data_area) > 0) {
				$this->db->insert_batch('areas', $data_area);
			}
			//	ZONE
			$data_zone = array();

			$delete_tbl_event_master = $this->db->query("UPDATE `landmark_areas` SET Audit_Status='0' WHERE Audit_Enter_uid = $ids");
			
			$query = $this->db->query("select * from  landmark_areas where Audit_Status = 1 and Audit_Enter_uid = $primary_user");
			foreach ($query->result() as $row)
			{
			   $data_zone[] = array( 'deviceid' =>  $row->deviceid,
				   'polyid' 	 =>  $row->polyid,
				   'polyname' =>  $row->polyname,
				   'color' =>  $row->color,
				   'lat' =>  $row->lat,
				   'lng' =>  $row->lng,
				   'pointid' =>  $row->pointid,
				   'in_alert' =>  $row->in_alert,
				   'out_alert' =>  $row->out_alert,
				   'speed_alert' =>  $row->speed_alert,
				   'speed_value' =>  $row->speed_value,
				   'speed_unit' =>  $row->speed_unit,
				   'addressbook_ids'	 =>  $row->addressbook_ids,
				   'Audit_Enter_Dt'	 =>  date("Y-m-d H:i:s"),
				   'Audit_Enter_uid'	 =>  $ids,
				   'Audit_Status'	 =>  1,
				   'Audit_Comment'	 =>  $row->Audit_Comment,
				   'email_alert'	 =>  $row->email_alert,
				   'sms_alert'	 =>  $row->sms_alert,
				   'area_type_opt'	 =>  $row->area_type_opt,
				);
			}
			
			if(count($data_zone) > 0) {
				$this->db->insert_batch('landmark_areas', $data_zone);
			}

			//Route
			$data_route = array();
			$delete_tbl_event_master = $this->db->query("UPDATE `tbl_routes` SET  status='0' WHERE userid = $ids");
			
			$query = $this->db->query("select * from tbl_routes where status = 1 and userid = $primary_user");
			foreach ($query->result() as $row)
			  {
			    $data_route[] = array( 'routename' =>  $row->routename,
						 'route_color'  =>  $row->route_color,
						 'landmark_ids' =>  $row->landmark_ids,
						 'start_point' =>  $row->start_point,
						 'end_point' =>  $row->end_point,
						 'waypoints' =>  $row->waypoints,
						 'points' =>  $row->points,
						 'deviceid' =>  $row->deviceid,
						 'distance_value' =>  $row->distance_value,
						 'distance_unit' =>  $row->distance_unit,
						 'total_distance' =>  $row->total_distance,
						 'total_time_in_minutes' =>  $row->total_time_in_minutes,
						 'round_trip' =>  $row->round_trip,
						 'userid' =>  $ids,
						 'add_date' =>  date("Y-m-d H:i:s"),
						 'add_uid' =>  $ids,
						 'status' =>  1,
						 'comments' =>  $row->comments,
						 'email_alert' =>  $row->email_alert,
						 'sms_alert' =>  $row->sms_alert,
						    
						 );
			  }
			
			if(count($data_route) > 0) {
				$this->db->insert_batch('tbl_routes', $data_route);
			}

			// LANDMARK
			$data_landmark = array();
			$delete_tbl_event_master = $this->db->query("UPDATE `landmark` SET status='0', del_date='".date("Y-m-d H:i:s")."' WHERE add_uid = $ids");
			
			$query = $this->db->query("select * from  landmark where status = 1 and del_date is null and add_uid = $primary_user");
			foreach ($query->result() as $row)
			{
			   $data_landmark[] = array( 'name' =>  $row->name,
				   'address' 	 =>  $row->address,
				   'radius' =>  $row->radius,
				   'distance_unit' =>  $row->distance_unit,
				   'device_ids' =>  $row->device_ids,
				   'icon_path' =>  $row->icon_path,
				   'lat' =>  $row->lat,
				   'lng' =>  $row->lng,
				   'add_uid'	 =>  $ids,
				   'add_date'	 =>  date("Y-m-d H:i:s"),
				   'status'	 =>  1,
				   'comments'	 =>  $row->comments,
				   'group_id'	 =>  $row->group_id,
				   'email_alert'	 =>  $row->email_alert,
				   'sms_alert'	 =>  $row->sms_alert,
				   'alert_before_landmark'	 =>  $row->alert_before_landmark,
				);
			}


			if(count($data_landmark) > 0) {
				$this->db->insert_batch('landmark', $data_landmark);
			}

			// GROUPS
			$data_groups = array();
			$delete_tbl_event_master = $this->db->query("UPDATE `group_master` SET status='0', del_date='".date("Y-m-d H:i:s")."' WHERE add_uid = $ids");
			
			$query = $this->db->query("select * from group_master where status = 1 and del_date is null and add_uid = $primary_user");
			foreach ($query->result() as $row)
			{
			   $data_groups[] = array(
			   	   'group_name' => $row->group_name,
				   'assets' => $row->assets,
				   'add_uid' => $ids,
				   'add_date' => date("Y-m-d H:i:s"),
				   'status' => 1,
				   'comments' => $row->comments,
				);
				$this->db->insert('group_master', $data_groups); 
			}			

			if(count($data_groups) > 0) {
				$this->db->insert_batch('group_master', $data_groups);
			}
			*/
		}
	}

	function getAllData($cmd){

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
			
		/*$this->db->select('count(user_id) as record_count')->from($this->tbl_users);
		$this->db->where('admin_id', $this->session->userdata('user_id'));
		$record_count = $this->db->get();
		$row = $record_count->row();
		$count = $row->record_count;
		*/
	
		$SQL = "SELECT * FROM tbl_users WHERE del_date is null";
			$SQL .=" AND admin_id = ".$this->session->userdata('user_id');

		if($where != "")
			$SQL .= " AND $where";
		$result = $this->db->query($SQL);
		$count = $result->num_rows();
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
		//$SQL = "SELECT us.user_id, us.username,mup.profile_name as profile_id, us.first_name, us.last_name, us.address, us.city, us.state, us.zip, us.country, us.phone_number, us.fax_number, us.mobile_number, us.email_address,  us.company_name, us.from_date, us.to_date, (select username from tbl_users where user_id=us.admin_id) as admin_id, us.status, mc.name as city,ms.name as country,mst.name as state FROM tbl_users as us left join mst_city as mc on mc.id = us.city left join mst_country as ms on ms.id = us.country left join  mst_user_profile as mup on mup.id = us.profile_id left join mst_state as mst on mst.id = us.state WHERE us.del_date is null";
		$SQL = "SELECT us.user_id, us.username,mup.profile_name as profile_id, us.first_name, us.last_name, us.address, us.city, us.state, us.zip, us.country, us.phone_number, us.fax_number, us.mobile_number, us.email_address,  us.company_name, us.report_view, us.menu_view, us.change_password, us.history, us.allow_user_profile, us.from_date, us.to_date, (select username from tbl_users where user_id=us.admin_id) as admin_id, us.status, mc.name as city,ms.name as country,mst.name as state FROM tbl_users as us left join mst_city as mc on mc.id = us.city left join mst_country as ms on ms.id = us.country left join  mst_user_profile as mup on mup.id = us.profile_id left join mst_state as mst on mst.id = us.state WHERE us.del_date is null";
		$SQL .=" AND us.admin_id = ".$this->session->userdata('user_id');
		if($where != "")
			$SQL .= " AND $where";

		$export_sql = $SQL;
		
		if($cmd=="export")   
		{
			$result = $this->db->query($export_sql);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=Users.xls"); 
			$EXCEL = ""; 
			$fitr="";
			
			//session date & time format 
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			$fitr .="<tr>";
			$fitr.="<th>".$this->lang->line("Id")."</th>";
			$fitr.="<th>".$this->lang->line("First Name")."</th>";
			$fitr.="<th>".$this->lang->line("Last Name")."</th>";
			$fitr.="<th>".$this->lang->line("Username")."</th>";
			$fitr.="<th>".$this->lang->line("Profile")."</th>";
			$fitr.="<th>".$this->lang->line("Address")."</th>";
			$fitr.="<th>".$this->lang->line("City")."</th>";
			$fitr.="<th>".$this->lang->line("State")."</th>";
			$fitr.="<th>".$this->lang->line("Country")."</th>";
			$fitr.="<th>".$this->lang->line("Zip")."</th>";
			$fitr.="<th>".$this->lang->line("Phone No")."</th>";
			$fitr.="<th>".$this->lang->line("Mobile No")."</th>";
			$fitr.="<th>".$this->lang->line("Email")."</th>";
			$fitr.="<th>".$this->lang->line("Company Name")."</th>";
			$fitr.="<th>".$this->lang->line("Valid From")."</th>";
			$fitr.="<th>".$this->lang->line("Valid To")."</th>";
			$fitr.="<th>".$this->lang->line("Status")."</th>";
			$fitr .="</tr>"; 
			//var_dump($result);
			foreach($result->result_array() as $data)
				{
					if($data['from_date'] != ""){
						$data['from_date'] = date("Y-m-d H:i:s", strtotime($data['from_date']));
					}
					if($data['to_date'] != ""){
						$data['to_date'] = date("Y-m-d H:i:s", strtotime($data['to_date']));
					}
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data['user_id']." </td>"; 
					$EXCEL.="<td>".$data['first_name']." </td>"; 
					$EXCEL.="<td>".$data['last_name']."</td>";
					$EXCEL.="<td>".$data['username']."</td>";
					$EXCEL.="<td>".$data['profile_id']."</td>";
					$EXCEL.="<td>".$data['address']."</td>";
					$EXCEL.="<td>".$data['city']."</td>";
					$EXCEL.="<td>".$data['state']."</td>";
					$EXCEL.="<td>".$data['country']."</td>";
					$EXCEL.="<td>".$data['zip']."</td>";
					$EXCEL.="<td>".$data['phone_number']."</td>";
					$EXCEL.="<td>".$data['mobile_number']."</td>";
					$EXCEL.="<td>".$data['email_address']."</td>";
					$EXCEL.="<td>".$data['company_name']."</td>";
					$EXCEL.="<td>".$data['from_date']."</td>";
					$EXCEL.="<td>".$data['to_date']."</td>";
					$EXCEL.="<td>".$data['status']."</td>";
					$EXCEL .="</tr>";
				}
						
			echo "<table border='1'>";
			
			echo "<tr><th colspan='17'>".$this->lang->line("Users")."</th></tr>";
			echo $fitr;
			echo $EXCEL;
			echo "</table>";
			die(); 
		}

		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		$query = $this->db->query($SQL);
		
		$data = array();
		$data['result'] = $query->result();
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;
		return $data;
	}
	public function delete_users() 
	{
		$ids = $_POST["id"];
		$dt = gmdate('Y-m-d H:i:s');
		$tblUsr="UPDATE `tbl_users` SET `status`=0, `del_date`='".$dt."', `del_uid`=".$this->session->userdata('user_id')."  WHERE user_id in(".$ids.")";
		$this->db->query($tblUsr) or die("error");
		$tblAssets="UPDATE user_assets_map SET `status`=0, `del_date`='".$dt."', `del_uid`=".$this->session->userdata('user_id')." WHERE user_id in(".$ids.")";
		$this->db->query($tblAssets) or die("error");
		$tblAssets="UPDATE assests_master SET `status`=0, `del_date`='".$dt."', `del_uid`=".$this->session->userdata('user_id')." WHERE add_uid in(".$ids.")";
		$this->db->query($tblAssets) or die("error");
		return TRUE;
	}
	public function validate() {
		
		$this->form_validation->set_rules('first_name', 'First Name');
		$this->form_validation->set_rules('last_name', 'Last Name');
		return parent::validate();

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
		$db_array['date_format'] = "d.m.Y";
		$db_array['time_format'] = "h:i:s a";
		$db_array['photo '] = "not_available.jpg";
		$this->db->insert("tbl_users", $db_array);
		$insert_id = $this->db->insert_id();
		
		$ua_array['user_id'] = $insert_id;
		$ua_array['add_uid'] = $this->session->userdata('user_id');
		
		//$ua_array['add_date'] = date('Y-m-d H:i:s');
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
	function getState($id)
	{
		if($id!="")
		{
			$SQL = "SELECT id,name FROM mst_state where FK_mst_country_p_id =".$id;
			$query = $this->db->query($SQL);
			return $query->result_array();
		}
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
		{
			return "<option value='' >".$this->lang->line("Select State")."</option>";}else
		{
		$query="select id, name from mst_state where FK_mst_country_p_id='$id' AND  status= '1' AND del_uid is Null AND del_date is Null Order by name";
		$data = $this->db->query($query);

			$opts="<option value='' >".$this->lang->line("Select State")."</option>";

		foreach ($data->result() as $row)
			{
				if($state!="" && $state==$row->id)
					$opts.="<option value='".$row->id."' selected='selected' >".$row->name."</option>";
				else
					$opts.="<option value='".$row->id."' >".$row->name."</option>";
			}
			return $opts;
		}
	}
	public function city(){
		$id=uri_assoc('id');
		$city=uri_assoc('city');
	
		if($id == 0 OR $id == "")
		{
			return "<option value='' >".$this->lang->line("Select City")."</option>";
		}else
		{
		$query="select id, name from mst_city where FK_mst_state_p_id='$id' AND  status= '1' AND del_uid is Null AND del_date is Null Order by name";
		$data = $this->db->query($query);

		$opts="<option value='' >".$this->lang->line("Select City")."</option>";
		
		foreach ($data->result() as $row)
			{
				if($city!="" && $city==$row->id)
					$opts.="<option value='".$row->id."' selected='selected' >".$row->name."</option>";
				else
					$opts.="<option value='".$row->id."' >".$row->name."</option>";
			}
			return $opts;
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
	
}
?>