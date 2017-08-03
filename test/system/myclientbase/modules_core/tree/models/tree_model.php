<?php 
class Tree_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Tree_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "tbl_track";
		$this->tbl_assets = "assests_master";
		$this->icon_master = "icon_master";		
    }
	function closechat() {
		
		$openChatBoxes = $this->session->userdata('openChatBoxes');
		if (in_array($chat['from'],$openChatBoxes)) {
			$key = array_search($_POST['chatbox'],$openChatBoxes['from']);
			unset($openChatBoxes['from'][$key]);
			unset($openChatBoxes[$_POST['chatbox']]);
			$session_data=array("openChatBoxes"=>$openChatBoxes);
			$this->session->set_userdata($session_data);
		}
		return true;
	}
	function sendChat() {
		$from = $this->session->userdata('user_id');
		$to = $_POST['to'];
		$message = $_POST['message'];
		$openChatBoxes = $this->session->userdata('openChatBoxes');
		if (!in_array($chat['from'],$openChatBoxes)) {
			$openChatBoxes['from'][]=$to;
			$openChatBoxes[$to]=date('Y-m-d H:i:s');
			$session_data=array("openChatBoxes"=>$openChatBoxes);
			$this->session->set_userdata($session_data);
		}
		$messagesan = $this->sanitize($message);
		$data =array(
			"from"=>$from,
			"to"=>$to,
			"message"=>$message,
			"sent"=>date('Y-m-d H:i:s'),
			"recd"=>0
		);
		$this->db->insert('chat',$data);
		return true;
	}
	function chatHeartbeat() {
		$sql = "select user_id,first_name,last_name from tbl_users where del_date is null and status=1";
		$res=$this->db->query($sql);
		$users=array();
		foreach($res->result_Array() as $chat){
			$users[$chat['user_id']]=$chat['first_name']." ".$chat['last_name'];
		}
		$sql = "select * from chat where (chat.to = '".$this->session->userdata('user_id')."' AND recd = 0) order by id ASC";
		$res=$this->db->query($sql);
		$chatBoxes = array();
		$items=array();
		$openChatBoxes = $this->session->userdata('openChatBoxes');
		/* history & Current Data */
		foreach($res->result_Array() as $chat){
			$sql = "update chat set recd = 1 where id='".$chat['id']."' and recd = 0";
			$this->db->query($sql);
			if (!in_array($chat['from'],$openChatBoxes)) {
				$openChatBoxes['from'][]=$chat['from'];
				$openChatBoxes[$chat['from']]=gmdate('Y-m-d H:i:s');
				$session_data=array("openChatBoxes"=>$openChatBoxes);
				$this->session->set_userdata($session_data);
			}
			$items[] = array('s'=>$chat['s_data'],'name'=>$users[$chat['from']],'f'=>$chat['from'],"m"=>$chat['message']);
		}
		$data = array(
			"chat_status"=>gmdate("Y-m-d H:i:s")
		);
		$user =array();
		$this->db->where("user_id",$this->session->userdata('user_id'));
		$this->db->update("tbl_users",$data);
		if($this->session->userdata('usertype_id')==1){
			$this->db->select("user_id,first_name,last_name,chat_status");
			$this->db->where("user_id !=",$this->session->userdata('user_id'));
			$this->db->order_by("chat_status", "desc"); 
			$res = $this->db->get("tbl_users");
		}else{
			$res = $this->db->query("SELECT tu.user_id,tu.first_name,tu.last_name,tu.chat_status FROM `tbl_users` tu where tu.del_date is null and tu.status=1 and tu.user_id!=".$this->session->userdata('user_id')." and (tu.admin_id=".$this->session->userdata('user_id')." or tu.usertype_id=1 or user_id =".$this->session->userdata('admin_id').") order by tu.chat_status desc");
		}
		foreach($res->result_Array() as $row){
			$now = strtotime(gmdate('Y-m-d H:i:s'))-strtotime($row['chat_status']);
			$chat_status=0;
			if($now<50) $chat_status=1;
			$user[]=array("data"=>$row['user_id'],"cs"=>$chat_status,"data1"=>" ".$row['first_name']." ".$row['last_name']);
		}
		$data1 = array("items"=>$items,"user"=>$user);
		return json_encode($data1);
	}
	function sanitize($text) {
		$text = htmlspecialchars($text, ENT_QUOTES);
		$text = str_replace("\n\r","\n",$text);
		$text = str_replace("\r\n","\n",$text);
		$text = str_replace("\n","<br>",$text);
		return $text;
	}
	function startChatSession() {
		$data = array ( "chat_status"=>1);
		$this->db->where("user_id",$this->session->userdata('user_id'));
		$this->db->update("tbl_users",$data);
		$data= array("username" => $this->session->userdata('user_id'),
			"items" => '',
			"display_name" => $this->session->userdata('first_name')." ".$this->session->userdata('last_name')
		);
		return json_encode($data);
	}
	public function delete_tbl_track_data(){
		$sdate=gmdate("Y-m-d");
		$edate=gmdate("Y-m-d");
		$qry="SELECT * FROM sys_information WHERE date(last_login_time) between '$sdate 00:00:00' and '$edate 23:59:59' and user_id =". $this->session->userdata('user_id');
		$rs=$this->db->query($qry);
		if(count($rs->result())==0){
			$qry="SELECT data_value from tbl_settings where data_key='all_point_setting'";
			$rs=$this->db->query($qry);
			$rs1=$rs->result_array();
			if($rs1[0]['data_value']!=""){
				$dt=gmdate("Y-m-d H:i:s",strtotime(gmdate("Y-m-d H:i:s")." -".$rs1[0]['data_value']." days"));
			}else{
				$dt=gmdate("Y-m-d H:i:s",strtotime(gmdate("Y-m-d H:i:s")." -30 days"));
			}
			$query = "DELETE FROM tbl_track_log where add_date < '$dt'";
			$this->db->query($query);
		}
	}
	public function get_main_menu() 
	{
		$menu = array();
			$user_id = $this->session->userdata('user_id');
			$query=$this->db->query("select tm.link,tm.Text from top_menu_master tmm left join top_main_menu_master tm on tm.id=tmm.menu_id where tmm.user_id= $user_id and tmm.del_date is null and tmm.status=1 ");
			foreach ($query->result() as $row)
			{
				$menu[] =  "<li><a href='".$row->link."' target='blank'>".$this->lang->line($row->Text)."</a></li>";
			}
			return $menu;
	}
	public function set_language($data) 
	{
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->update('tbl_users', $data); 
	}
	public function popup_request($data) 
	{
		$data['add_date']=gmdate('Y-m-d H:i:s');
		$data['add_uid']=$this->session->userdata('user_id');
		$this->db->insert("tooltip_msgs", $data);
		
	}
	public function get_stop_report() 
	{
		$this->table_name = "tbl_stop_report";
		$date_format = $this->session->userdata('date_format');  
		$time_format = $this->session->userdata('time_format'); 
		$device = $_REQUEST['device'];
		$data = "<table border=1 width=96% >";
		$data .= "<tr><th width='70px'>Assets Name</th><th  width='100px'>Stop Time</th><th width='100px'>Start Time</th><th>Location </th><th> Duration</th></tr>";
				
		$SQL = "SELECT tm.id, am.device_id, CONVERT_TZ(tm.ignition_off,'+00:00','".$this->session->userdata('timezone')."') as ignition_off, CONVERT_TZ(tm.ignition_on,'+00:00','".$this->session->userdata('timezone')."') as ignition_on, tm.duration, tm.address, am.assets_name FROM ".$this->table_name." tm left join assests_master am on tm.device_id=am.id WHERE CONVERT_TZ(tm.ignition_off,'+00:00','".$this->session->userdata('timezone')."') and ignition_on is not null";
		if($device){	//search by device
			$SQL .= " AND find_in_set(tm.device_id,'$device')";
		}
		$SQL .= " Order by tm.id DESC LIMIT 0, 10";
		$result = $this->db->query($SQL);
		$count = $result->num_rows();
		if($count>0){
			foreach($result->result_array() as $row)
			{
				$ignition_off = $row['ignition_off'];
				$ignition_on = $row['ignition_on'];
				$data .="<tr align='center'>";
				$data.="<td>".$row['assets_name']."(".$row['device_id'].")</td>"; 
				$data.="<td>&nbsp;".date("$date_format $time_format", strtotime($ignition_off))."</td>"; 
				$data.="<td>&nbsp;".date("$date_format $time_format", strtotime($ignition_on))."</td>"; 
				$data.="<td>".$row['address']."</td>";
				$data.="<td>".$row['duration']."</td>";
				$data .="</tr>";
			}
			$data .= "</table>";
		}
		else{
			$data = "<center>".$this->lang->line('No_Data_Found')."</center>";
		}
		return $data;
		
	}
	public function get_area_in_out() 
	{
		$this->table_name = "area_inout_log";
		$this->tbl_assets = "assests_master";
		$this->tbl_area = "areas";
		$user = $this->session->userdata('user_id');
		$date_format = $this->session->userdata('date_format');  
		$time_format = $this->session->userdata('time_format'); 
		$device = $_REQUEST['device'];
		$data = "<table border=1 width=96% >";
		$data .= "<tr><th>Assets</th><th>Area</th><th>Date</th><th>Status</th></tr>";
				
		$SQL = "SELECT distinct(tm.id), CONVERT_TZ(tm.date_time,'+00:00','".$this->session->userdata('timezone')."') as date, tm.inout_status as status, am.assets_name as device, ta.polyname as area FROM ".$this->table_name." as tm left join ".$this->tbl_assets." am on am.id = tm.device_id left join ".$this->tbl_area." as ta on tm.area_id=ta.polyid WHERE am.id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";
		if($device){	//search by device
			$SQL .= " AND find_in_set(tm.device_id,'$device')";
		}
		$SQL .= " Order by tm.date_time DESC LIMIT 0, 10";
		$result = $this->db->query($SQL);
		$count = $result->num_rows();
		if($count>0){
			foreach($result->result_array() as $row)
			{
				$Date = $row['date'];
				$data .="<tr align='center'>";
				$data.="<td>".$row['device']."</td>"; 
				$data.="<td>".$row['area']."</td>";
				$data.="<td>&nbsp;".date("$date_format $time_format", strtotime($Date))."</td>"; 
				$data.="<td>".$row['status']."</td>";
				if($this->session->userdata('id')==1)
				{
					$data.="<td>".$row['Owner']."</td>";
				}
				$data .="</tr>";
				$device_name = $row['device'];
			}
			$data .= "</table>";
		}
		else{
			$data = "<center>".$this->lang->line('No_Data_Found')."</center>";
		}
		return $data;
	}
	public function get_landmark_report() 
	{
		$this->tbl_assets_type = "landmark_log";
		$this->icon_master = "icon_master";
		$user = $this->session->userdata('user_id');
		$date_format = $this->session->userdata('date_format');  
		$time_format = $this->session->userdata('time_format'); 
		$device = $_REQUEST['device'];
		$data = "<table border=1 width=96% >";
		$data .= "<tr><th>Assests Name(Device)</th><th>Landmark Name</th><th>Date Time</th><th>Distance</th></tr>";
				
		$SQL = "SELECT lg.id, lg.device_id, lg.landmark_id, CONVERT_TZ(lg.date_time,'+00:00','".$this->session->userdata('timezone')."') as date_time, lg.lat, lg.lng, lg.distance, lg.in_out,concat(am.assets_name, concat('(',am.device_id,')')) as device_name, lm.name as landmark_name from landmark_log as lg LEFT JOIN landmark as lm ON lg.landmark_id = lm.id LEFT JOIN assests_master as am ON am.id = lg.device_id where lg.device_id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";
		
		if($device){	//search by device
			$SQL .= " AND find_in_set(am.id,'$device')";
		}
		$SQL .= " Order by lg.id DESC LIMIT 0, 10";
		
		$result = $this->db->query($SQL);
		$count = $result->num_rows();
		if($count>0){
			foreach($result->result_array() as $row)
				{
					$date_time = $row['date_time']; 
					$data .="<tr align='center'>";
					$data.="<td>".$row['device_name']."</td>"; 
					$data.="<td>".$row['landmark_name']."</td>";
					//$data.="<td>".$row['date_time']."</td>";
					$data.="<td>&nbsp;".date("$date_format $time_format", strtotime($date_time))."</td>";
					$data.="<td>".$row['distance']."</td>";
					$data .="</tr>"; 
					
				}
			$data .= "</table>";
		}
		else{
			$data = "<center>".$this->lang->line('No_Data_Found')."</center>";
		}
		return $data;
	}
	public function get_distance_wise() 
	{
		$user = $this->session->userdata('user_id');
		$date_format = $this->session->userdata('date_format');  
		$time_format = $this->session->userdata('time_format'); 
		$device = $_REQUEST['device'];
		$data = "<table border=1 width=96% >";
		$data .= "<tr><th>Date</th><th>Vehicle</th><th>From</th><th>To</th><th>Distance(KM)</th></tr>";
				
		$SQL = "SELECT dm.id, am.assets_name, am.id as aId, CONVERT_TZ(dm.date_time,'+00:00','".$this->session->userdata('timezone')."') as date_time, dm.distance_from_last as distance, lm1.name as to_location, lm2.name as from_location from landmark_log dm left join assests_master am on am.id=dm.device_id left join landmark lm1 on lm1.id=dm.landmark_id left join landmark lm2 on lm2.id=dm.last_landmark_id WHERE am.id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";
		
		if($device){	//search by device
			$SQL .= " AND find_in_set(dm.device_id,'$device')";
		}
		$SQL .= " Order by dm.id DESC LIMIT 0, 10";
		
		$result = $this->db->query($SQL);
		$count = $result->num_rows();
		if($count>0){
			foreach($result->result_array() as $row)
				{
					$date_time = $row['date_time'];
					$data .="<tr align='center'>";
					$data.="<td> &nbsp;".date($date_format." ".$time_format, strtotime($date_time))." </td>"; 
					$data.="<td>".$row['assets_name']."</td>"; 
					$data.="<td>".$row['from_location']."</td>"; 
					$data.="<td>".$row['to_location']."</td>"; 
					$data.="<td>".$row['distance']."</td>";
					$data .="</tr>";
					
				}
			$data .= "</table>";
		}
		else{
			$data = "<center>".$this->lang->line('No_Data_Found')."</center>";
		}
		return $data;
	}
	public function get_city($data) 
	{
		$options ="<option value=''>'".$this->lang->line("Please Select")."'</options>";
		$query = $this->db->query("select id, name from mst_city where FK_mst_state_p_id=".$data['id']." and  del_date is null and status=1");
		foreach($query->result() as $row)
		{
			$options .="<option value='".$row->id."'>".$row->name ."</options>";
		}
		//echo $options;
		return $options;
	}
	public function get_state($data) 
	{
		$options ="<option value=''>'".$this->lang->line("Please Select")."'</options>";
		$query = $this->db->query("select id, name from mst_state where FK_mst_country_p_id=".$data['id']." and  del_date is null and status=1");
		foreach($query->result() as $row)
		{
			$options .="<option value='".$row->id."'>".$row->name ."</options>";
		}
		//echo $options;
		return $options;
	}
	public function alert_master() 
	{
		
		$user = $this->session->userdata('user_id');
		$query = $this->db->query("select id, alert_header, alert_msg, alert_link, alert_type from alert_master where del_date is null and user_id=$user");
		$data=array();
		$i=0;
		$result = false;
		foreach($query->result() as $row)
		{
			$data[$i]['header']=$row->alert_header;
			$data[$i]['msg']=$row->alert_msg;
			$row->alert_link = str_replace("_base_url_",base_url(),$row->alert_link);
			$data[$i]['link']=$row->alert_link;
			$data[$i]['type']=$row->alert_type;
			$this->db->query("update alert_master set del_date= '".gmdate('Y-m-d H:i:s')."' where id = ".$row->id);
			$i++;
			$result=true;
		}
		$res = array();
		$res['alert'] = $data;
		$res['count'] = count($data);
		$res['result'] = $result;
		return $res;
	}
	public function get_all_country() 
	{
		$options ="<option value=''>'".$this->lang->line("Please Select")."'</options>";
		$query = $this->db->query("select id, name from mst_country where del_date is null and status=1");
		foreach($query->result() as $row)
		{
			$options .="<option value='".$row->id."'>".$row->name ."</options>";
		}
		//echo $options;
		return $options;
	}
	public function get_devices($user) 
	{
		$this->db->where('status',1);
		$this->db->where('del_date',null);
		if($this->session->userdata('user_id')!=1){
		$query = $this->db->query("select id, assets_name, device_id, assets_friendly_nm from assests_master where status=1 AND del_date is null AND find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)) order by assets_name asc");
		}else{
			$query = $this->db->query("select id, assets_name, device_id, assets_friendly_nm from assests_master where status=1 AND del_date is null order by assets_name asc ");
		
		}
		return $query->result();
	}
	
	public function get_owners(){
		
		$query = "SELECT id, owner FROM `assests_owner_master` WHERE status = '1' AND del_date is Null ORDER BY owner";
		$query = $this->db->query($query);
		return $query->result();
				
	}
	
	public function get_divisions(){

		$query = "SELECT id, division FROM `assests_division_master` WHERE status= '1' AND del_date IS NULL ORDER BY division";
		$query = $this->db->query($query);
		return $query->result();
		
	}
	
	public function getAssetsStatus($user, $group)
	{
		
		$qry="SELECT ";
		//running
		if($this->session->userdata('usertype_id') == 1 && $user == 1){
			$qry.=" (select count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $group AND (TIME_TO_SEC(TIMEDIFF( NOW( ) , CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."'))) < ".$this->session->userdata('network_timeout')." and lm.speed > 0)) as Running,";
			
			$qry.=" (select count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $group AND (TIME_TO_SEC(TIMEDIFF( NOW( ) , CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."'))) < ".$this->session->userdata('network_timeout')." and lm.speed = 0) ) as Parked,";
			
			$qry.=" (select count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $group AND (TIME_TO_SEC(TIMEDIFF( NOW( ) , CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."'))) BETWEEN ".$this->session->userdata('network_timeout')." AND ".($this->session->userdata('network_timeout')+36000).") ) as out_of_network,";
			
			$qry.="(select count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $group AND ((TIME_TO_SEC(TIMEDIFF( NOW( ) , CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."')))) > ".($this->session->userdata('network_timeout')+36000)." or (lm.add_date is null))) as device_fault,";
			
			$qry.="(SELECT count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $group) as total";
		}else{
			$qry.=" (select count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $group AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = ".$user." )) AND (TIME_TO_SEC(TIMEDIFF( NOW( ) , CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."'))) < ".$this->session->userdata('network_timeout')." and lm.speed > 0)) as Running,";
			
			$qry.=" (select count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $group AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = ".$user." )) AND (TIME_TO_SEC(TIMEDIFF( NOW( ) , CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."'))) < ".$this->session->userdata('network_timeout')." and lm.speed = 0) ) as Parked,";
			
			$qry.=" (select count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $group AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = ".$user." )) AND (TIME_TO_SEC(TIMEDIFF( NOW( ) , CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."'))) BETWEEN ".$this->session->userdata('network_timeout')." AND ".($this->session->userdata('network_timeout')+36000).") ) as out_of_network,";
			
			$qry.="(select count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $group AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = ".$user." )) AND ((TIME_TO_SEC(TIMEDIFF( NOW( ) , CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."')))) > ".($this->session->userdata('network_timeout') + 36000)." or (lm.add_date is null))) as device_fault,";
			
			$qry.="(SELECT count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $group AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = ".$user."))) as total";
		}
		
		$query = $this->db->query($qry);
		return array($query->result_array());
	}
	
	public function treeAssetsStatus($assets)
	{
		
		$qry="SELECT ";
		//running
		$qry.=" (select count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.id IN ($assets) AND am.del_date is null AND (TIME_TO_SEC(TIMEDIFF( NOW(), CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."'))) < ".$this->session->userdata('network_timeout')." and lm.speed > 0)) as Running,";
		
		$qry.=" (select count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.id IN ($assets) AND am.del_date is null AND (TIME_TO_SEC(TIMEDIFF( NOW( ), CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."'))) < ".$this->session->userdata('network_timeout')." and lm.speed = 0) ) as Parked,";
		
		$qry.=" (select count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.id IN ($assets) AND am.del_date is null  AND (TIME_TO_SEC(TIMEDIFF( NOW( ) , CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."'))) BETWEEN ".$this->session->userdata('network_timeout')." AND ".($this->session->userdata('network_timeout')+36000).") ) as out_of_network,";
		
		$qry.="(select count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.id IN ($assets) AND am.del_date is null  AND ((TIME_TO_SEC(TIMEDIFF( NOW( ) , CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."')))) > ".($this->session->userdata('network_timeout')+36000)." or (lm.add_date is null))) as device_fault,";
		
		$qry.="(SELECT count(am.id) from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.id IN ($assets) AND am.del_date is null ) as total";
		
		$query = $this->db->query($qry);
		return array($query->result_array());
	}

	public function last_location($user) 
	{
		$page = $_POST['page'];
		$limit = $_POST['limit'];
		$reports = $_POST['report'];
		$assets_ids = $_POST['assets_ids'];
		
		$stsWhr = "";
		$group = "";
		
		foreach($reports as $report) {
			$rptsub = substr($report, 0, 2);
			
			if($rptsub == "g-"){
				$group = str_replace($rptsub, "", $report);
			}
			
			if($rptsub == "u-"){
				$user = str_replace($rptsub, "", $report);
			}
			
			if($rptsub == "a-"){
				$us_ar = str_replace($rptsub, "", $report);
			}
			
			if($rptsub == "l-"){
				$us_ln = str_replace($rptsub, "", $report);
			}
			
			if($rptsub == "o-"){
				$us_ow = str_replace($rptsub, "", $report);
			}
			
			if($rptsub == "d-"){
				$us_dv = str_replace($rptsub, "", $report);
			}
		}
		
		if($us_ar != ""){
			$this->db->select("polyname", FALSE);
			$this->db->where('polyid', $us_ar);
			$this->db->limit(1);
			$query = $this->db->get('areas');			
			$rows = $query->result();
			$us_area = '';
			foreach ($rows as $key => $row) {
				$us_area = $row->polyname;
			}

			if($us_area!="")
				$gsub .= " AND lm.current_area = '".addslashes($us_area)."'";
		}
		
		if($us_ln != ""){
			$this->db->select("name", FALSE);
			$this->db->where('id', $us_ln);
			$this->db->limit(1);
			$query = $this->db->get('landmark');			
			$rows = $query->result();
			$us_land = '';
			foreach ($rows as $row) {
				$us_land = $row->name;
			}
			if($us_land!="")
				$gsub .= " AND lm.current_landmark = '".addslashes($us_land)."'";
		}
		
		if($group != ""){
			$gsub .= " AND am.assets_group_id = $group";
			/*
			$this->db->select("assets", FALSE);
			$this->db->where('id', $group);
			$this->db->limit(1);
			$query = $this->db->get('group_master');			
			$rows = $query->result();
			foreach ($rows as $row) {
				$assets = $row->assets;
			}
			if($assets!="")
				$gsub .= " AND am.id in($assets)";
			else
				$gsub .= " AND am.id in(-1)";
			*/
		}
		
		if(trim($us_ow) != '') {
			$gsub .= " AND am.assets_owner = '".intval($us_ow)."'";
		}
		
		if(trim($us_dv) != '') {
			$gsub .= " AND am.assets_division = '".intval($us_dv)."'";
		}
		
		if($user != "") {
			if($this->session->userdata('usertype_id') != 1 || $user != $this->session->userdata('user_id'))
				$gsub .= " AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = $user))";
			else {
				//$sub = "am.id IN(SELECT assets_ids FROM user_assets_map WHERE 1)";
				$gsub .= " AND 1=1";
			}
		}
		$srch = "";
		if(isset($_POST['txt']) && $_POST['txt'] != ""){
			$txt = $_POST['txt'];
			$srch = " AND am.assets_name LIKE ('%".$txt."%')";
		}
		
		$qryFinal ="SELECT count(am.id) as total from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $gsub $srch $stsWhr";
		
		$query = $this->db->query($qryFinal, FALSE);
		$data_arr=$query->result_array();
		
		$totaldata = $data_arr[0]['total'];		
		if($limit == "all"){
			$limit = $totaldata;
			$lmt = 'all';
		}else{
			$lmt = $limit;
		}
		if( $totaldata > 0 ) {
			$total_pages = ceil($totaldata/$limit);	
			if ($page > $total_pages) $page=$total_pages;
			$start = $limit*$page - $limit;	
		} else {
			$total_pages = 0;
			$start = 0;
		}
		
		$Fqry1="SELECT (select sum(fuel_used) from distance_master where assets_id = am.id) as fuel_used, lm.fuel_in, lm.fuel_out, trip.routename, um.username, um.first_name, am.id as assets_id, am.assets_category_id, ad.division as assets_division, ao.owner as assets_owner, am.fuel_in_out_sensor, am.fuel_in_per_lit, am.fuel_in_company_name, am.fuel_in_product_code, am.fuel_out_per_lit, am.fuel_out_company_name, am.fuel_out_product_code, am.xyz_sensor, am.battery_size, lm.battery_status, am.device_id, am.assets_friendly_nm, am.max_fuel_liters, am.assets_name, am.driver_name, am.assets_image_path, am.driver_mobile, am.sim_number, am.driver_image, am.km_reading, am.eng_runtime, CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, TIME_TO_SEC(TIMEDIFF( NOW( ), CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."'))) as beforeTime, lm.address, lm.runtime, lm.lati, lm.longi, lm.speed, lm.old_speed, lm.ignition, im.icon_path, lm.current_area, lm.current_zone, lm.current_landmark, lm.cross_speed, lm.fuel_percent, lm.fuel_liter, CONVERT_TZ(lm.fuel_time,'+00:00','".$this->session->userdata('timezone')."') as fuel_time, lm.temperature, lm.reason_text, lm.reason, lm.command_key, lm.command_key_value, lm.msg_key, lm.sat_mode, lm.gps_fixed, lm.gsm_register, lm.gsm_strength, lm.gprs_register, lm.server_avail, lm.in_batt, lm.ext_batt_volt, lm.captured_image FROM assests_master am left join tbl_last_point lm on lm.device_id = am.device_id LEFT JOIN icon_master im ON im.id = am.icon_id LEFT JOIN tbl_users um on um.user_id = am.add_uid LEFT JOIN assests_owner_master ao ON ao.id = am.assets_owner LEFT JOIN assests_division_master ad ON ad.id = am.assets_division LEFT JOIN tbl_routes trip on trip.id = am.current_trip where am.status=1 AND am.del_date IS NULL ".$gsub." ".$usub." ".$srch." ".$stsWhr." ORDER BY am.assets_name LIMIT ".$start.", ".$limit;
		// echo $Fqry1;
		$query = $this->db->query($Fqry1, FALSE);
		
		return array($query->result(), $total_pages, $page, $totaldata, $lmt);
	}
	
	public function treeLastLocation($assets)
	{
		
		$page = $_REQUEST['page'];
		$limit = ($_REQUEST['limit']) ? $_REQUEST['limit'] : $_REQUEST['rows'];
		$reports = $_REQUEST['report'];
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'am.assets_name'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';
		
		$stsWhr = " AND am.id IN ($assets)";
		
		$qryFinal ="SELECT count(am.id) as total from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $stsWhr";
		
		$query = $this->db->query($qryFinal, FALSE);
		$data_arr=$query->result_array();
		
		$totaldata = $data_arr[0]['total'];		
		if($limit == "all"){
			$limit = $totaldata;
		}
		
		$lmt = $limit;
		
		if( $totaldata > 0 ) {
			$total_pages = ceil($totaldata/$limit);	
			if ($page > $total_pages) $page=$total_pages;
			$start = $limit*$page - $limit;	
		} else {
			$total_pages = 0;
			$start = 0;
		}
		
		$data_arr=$query->result_array();
		
		$Fqry1 = "SELECT trip.routename, um.username, um.first_name, am.id as assets_id, am.assets_category_id, ad.division as assets_division, ao.owner as assets_owner, am.fuel_in_out_sensor, am.fuel_in_per_lit, am.fuel_in_company_name, am.fuel_in_product_code, am.fuel_out_per_lit, am.fuel_out_company_name, am.fuel_out_product_code, am.xyz_sensor, am.battery_size, lm.battery_status, lm.alarm_type, am.device_id, am.assets_friendly_nm, am.max_fuel_liters, am.assets_name, am.driver_name, am.assets_image_path, am.driver_mobile, am.sim_number, am.driver_image, am.km_reading, am.eng_runtime, CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, TIME_TO_SEC(TIMEDIFF( NOW( ), CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."'))) as beforeTime, lm.address, lm.runtime, lm.lati, lm.longi, lm.speed, lm.old_speed, lm.ignition, im.icon_path, lm.current_area, lm.current_zone, lm.current_landmark, lm.cross_speed, lm.fuel_percent, lm.fuel_liter, CONVERT_TZ(lm.fuel_time,'+00:00','".$this->session->userdata('timezone')."') as fuel_time, lm.temperature, lm.reason_text, lm.reason, lm.command_key, lm.command_key_value, lm.msg_key, lm.sat_mode, lm.gps_fixed, lm.gsm_register, lm.gsm_strength, lm.gprs_register, lm.server_avail, lm.in_batt, lm.ext_batt_volt, lm.captured_image FROM assests_master am left join tbl_last_point lm on lm.device_id = am.device_id LEFT JOIN icon_master im ON im.id = am.icon_id LEFT JOIN tbl_users um on um.user_id = am.add_uid LEFT JOIN assests_owner_master ao ON ao.id = am.assets_owner LEFT JOIN assests_division_master ad ON ad.id = am.assets_division LEFT JOIN tbl_routes trip on trip.id = am.current_trip where am.status=1 AND am.del_date IS NULL ".$stsWhr;

		$Fqry1 .= " ORDER BY $sidx $sord LIMIT $start, $limit";

		$query = $this->db->query($Fqry1, FALSE);
		
		return array($query->result(), $total_pages, $page, $totaldata, $lmt);
	}
	
	public function all_location($user) 
	{
		$stsWhr = "";
		$group = "";
		$gsub = '';
		$srch = "";
		
		if($user != "") {
			if($this->session->userdata('usertype_id') != 1 || $user != $this->session->userdata('user_id'))
				$gsub .= " AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = $user))";
			else {
				//$sub = "am.id IN(SELECT assets_ids FROM user_assets_map WHERE 1)";
				$gsub .= " AND 1=1";
			}
		}
		
		$Fqry1="SELECT um.username, um.first_name, am.id as assets_id, am.assets_division as divison, am.assets_owner as own, am.assets_category_id, ad.division as assets_division, ao.owner as assets_owner, am.fuel_in_out_sensor, am.fuel_in_per_lit, am.fuel_in_company_name, am.fuel_in_product_code, am.fuel_out_per_lit, am.fuel_out_company_name, am.fuel_out_product_code, am.xyz_sensor, am.battery_size, lm.battery_status, lm.alarm_type, am.device_id, am.assets_friendly_nm, am.max_fuel_liters, am.assets_name, am.driver_name, am.assets_image_path, am.driver_mobile, am.sim_number, am.driver_image, am.km_reading, am.eng_runtime, CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, TIME_TO_SEC(TIMEDIFF( NOW( ), CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."'))) as beforeTime, lm.address, lm.runtime, lm.lati, lm.longi, lm.speed, lm.old_speed, lm.ignition, im.icon_path, lm.current_area, lm.current_zone, lm.zone_id, lm.area_id, lm.current_landmark, lm.landmark_id, lm.cross_speed, lm.fuel_percent, lm.fuel_liter, CONVERT_TZ(lm.fuel_time,'+00:00','".$this->session->userdata('timezone')."') as fuel_time, lm.temperature, lm.reason_text, lm.reason, lm.command_key, lm.command_key_value, lm.msg_key, lm.sat_mode, lm.gps_fixed, lm.gsm_register, lm.gsm_strength, lm.gprs_register, lm.server_avail, lm.in_batt, lm.ext_batt_volt, lm.captured_image FROM assests_master am left join tbl_last_point lm on lm.device_id = am.device_id LEFT JOIN icon_master im ON im.id = am.icon_id LEFT JOIN tbl_users um on um.user_id = am.add_uid LEFT JOIN assests_owner_master ao ON ao.id = am.assets_owner LEFT JOIN assests_division_master ad ON ad.id = am.assets_division LEFT JOIN tbl_routes trip on trip.id = am.current_trip where am.status=1 AND am.del_date IS NULL $gsub ORDER BY am.assets_name";
		// echo $Fqry1;
		$query = $this->db->query($Fqry1, FALSE);
		
		return $query->result();
	}


	public function stop_duration($assets) 
	{
		$page = $_POST['page'];
		$limit = ($_REQUEST['limit']) ? $_REQUEST['limit'] : $_REQUEST['rows'];
		$reports = $_REQUEST['report'];
		
		$stsWhr = " AND am.id IN ($assets)";
		
		// $Fqry1="SELECT sp.device_id, MAX( sp.ignition_off ) stop_from FROM tbl_stop_report sp LEFT JOIN assests_master am ON am.id = sp.device_id LEFT JOIN tbl_last_point lm ON lm.device_id = am.device_id WHERE lm.speed = 0 ".$stsWhr." GROUP BY sp.device_id ORDER BY am.assets_name ";
		
		$Fqry1="SELECT sp.device_id, MAX( sp.ignition_off ) as stop_from FROM tbl_stop_report sp LEFT JOIN assests_master am ON am.id = sp.device_id LEFT JOIN tbl_last_point lm ON lm.device_id = am.device_id WHERE lm.speed = 0 ".$stsWhr." GROUP BY sp.device_id ORDER BY am.assets_name ";
		
		$query = $this->db->query($Fqry1, FALSE);
		
		return $query->result();
	}
	
	public function imageViewer($pg){
		$page = $pg;
		//$limit = 8;
		$limit = uri_assoc('limit');;
		$id=uri_assoc('id');
		$qryFinal ="SELECT count(id) as total from tbl_track where assets_id=$id";
		
		$query = $this->db->query($qryFinal, FALSE);
		$data_arr=$query->result_array();
		
		$totaldata = $data_arr[0]['total'];
	
		if($limit == "all"){
			$limit = $totaldata;
			$lmt = 'all';
		}else{
			$lmt = $limit;
		}
		if( $totaldata > 0 ) {
			$total_pages = ceil($totaldata/$limit);	
			if ($page > $total_pages) $page=$total_pages;
			$start = $limit*$page - $limit;	
		} else {
			$total_pages = 0;
			$start = 0;
		}
		
		$Fqry1="SELECT CONVERT_TZ(add_date,'+05:30','".$this->session->userdata('timezone')."') as add_date, captured_image FROM tbl_track where assets_id=$id ORDER BY id DESC LIMIT ".$start.", ".$limit;
		$query = $this->db->query($Fqry1, FALSE);
		
		return array($query->result(), $total_pages, $page, $totaldata, $lmt);
	}
	public function get_name($device_id)
	{
				
		$query = $this->db->query("SELECT assets_name from assests_master where device_id = $device_id order by device_id desc limit 1", FALSE);

		return $query->row_array();
	}
	public function tag_setting($user) 
	{
		$query = $this->db->query("SELECT location_with_tag from tbl_users where user_id = $user", FALSE);

		$row = $query->row();
		
		return $row->location_with_tag;
	}
	public function getToolTips($id,$user)
	{
		$page = $_POST['page'];
		$limit = $_POST['limit'];
		$report = $_POST['report'];
	//	$report = 'running';
		
		$rptsub = substr($report, 0, 2);
		
		$stsWhr = "";
		$group = "";
		if($rptsub == "g-"){
			$group = str_replace($rptsub, "", $report);
		}
		else if($rptsub == "u-"){
			$user = str_replace($rptsub, "", $report);
		}
		else{
			switch ($report) { case "running":
					$stsWhr = " AND (TIME_TO_SEC(TIMEDIFF(CONVERT_TZ( NOW( ) , '+00:00', '".$this->session->userdata('diff_from_gmt')."' ) , lm.add_date)) < ".$this->session->userdata('network_timeout')." and lm.speed > 0)";	break;
				case "parked":
					$stsWhr = " AND TIME_TO_SEC(TIMEDIFF(CONVERT_TZ( NOW( ) , '+00:00', '".$this->session->userdata('diff_from_gmt')."' ) , lm.add_date)) < ".$this->session->userdata('network_timeout')." and lm.speed = 0 and lm.ignition = 0"; break;
				case "idle":
					$stsWhr = " AND TIME_TO_SEC(TIMEDIFF(CONVERT_TZ( NOW( ) , '+00:00', '".$this->session->userdata('diff_from_gmt')."' ) , lm.add_date)) < ".$this->session->userdata('network_timeout')." and lm.speed = 0 and lm.ignition = 1"; break;
				case "out_of_network":
					$stsWhr = " AND TIME_TO_SEC(TIMEDIFF(CONVERT_TZ( NOW( ) , '+00:00', '".$this->session->userdata('diff_from_gmt')."' ) , lm.add_date)) BETWEEN ".$this->session->userdata('network_timeout')." AND ".($this->session->userdata('network_timeout')+36000)."";	break;
				case "device_fault":
					$stsWhr = " AND (TIME_TO_SEC(TIMEDIFF(CONVERT_TZ( NOW( ) , '+00:00', '".$this->session->userdata('diff_from_gmt')."' ) , lm.add_date)) > ".($this->session->userdata('network_timeout')+36000)." or (lm.add_date is null))"; break;
			}
		}
		/*elseif($report == "running"){
			$stsWhr = " AND (TIME_TO_SEC(TIMEDIFF(CONVERT_TZ( NOW( ) , '+00:00', '+05:30' ) , lm.add_date)) < ".$this->session->userdata('network_timeout')." and lm.speed > 0)";
			
		}
		elseif($report == "parked"){
			$stsWhr = " AND TIME_TO_SEC(TIMEDIFF(CONVERT_TZ( NOW( ) , '+00:00', '+05:30' ) , lm.add_date)) < ".$this->session->userdata('network_timeout')." and lm.speed = 0 and lm.ignition = 0";
		}
		elseif($report == "idle"){
			$stsWhr = " AND TIME_TO_SEC(TIMEDIFF(CONVERT_TZ( NOW( ) , '+00:00', '+05:30' ) , lm.add_date)) < ".$this->session->userdata('network_timeout')." and lm.speed = 0 and lm.ignition = 1";
		}
		elseif($report == "out_of_network"){
			$stsWhr = " AND TIME_TO_SEC(TIMEDIFF(CONVERT_TZ( NOW( ) , '+00:00', '+05:30' ) , lm.add_date)) BETWEEN ".$this->session->userdata('network_timeout')." AND ".($this->session->userdata('network_timeout')+36000)."";
		}
		elseif($report == "device_fault"){
			$stsWhr = " AND (TIME_TO_SEC(TIMEDIFF(CONVERT_TZ( NOW( ) , '+00:00', '+05:30' ) , lm.add_date)) > ".($this->session->userdata('network_timeout')+36000)." or (lm.add_date is null))";
		}*/
		if($group != ""){
			$this->db->select("assets", FALSE);
			$this->db->where('id', $group);
			$query = $this->db->get('group_master');			
			$rows = $query->result();
			foreach ($rows as $row) {
				$assets = $row->assets;
			}
			$sub = "am.id in($assets)";
		}
		else{
			$sub = "find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = $user))";
		}
		
		$srch = "";
		if(isset($_POST['txt']) && $_POST['txt'] != ""){
			$txt = $_POST['txt'];
			$srch = " AND am.assets_name LIKE ('%".$txt."%')";
		}
		
		//$this->db->select('count(id) as record_count');
		
		//$query = $this->db->query("SELECT tr.id FROM (SELECT dm.device_id, MAX( dm.id ) AS max_id, am.assets_name FROM tbl_track dm LEFT JOIN assests_master am ON am.device_id = dm.device_id WHERE $sub GROUP BY dm.device_id ) AS x INNER JOIN tbl_track AS tr ON tr.device_id = x.device_id AND tr.id = x.max_id $srch $speedWhr order by tr.add_date desc", FALSE);
		$qryFinal ="SELECT count(*) as total from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null AND $sub $srch $stsWhr";
		
		$query = $this->db->query($qryFinal, FALSE);
		$data_arr=$query->result_array();
		
		$totaldata = $data_arr[0]['total'];
		/*$qryFinal ="SELECT am.id from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null AND $sub $srch $stsWhr";
		
		$query = $this->db->query($qryFinal, FALSE);
		
		$totaldata = $query->num_rows();*/
		if($limit == "all"){
			$limit = $totaldata;
			$lmt = 'all';
		}else{
			$lmt = $limit;
		}
		if( $totaldata > 0 ) {
			$total_pages = ceil($totaldata/$limit);	
			if ($page > $total_pages) $page=$total_pages;
		} else {
			$total_pages = 0;
			$start = 0;
		}
		$start = $limit*$page - $limit;		
		$Fqry1="SELECT am.id as assets_id, am.assets_name, am.assets_friendly_nm, am.max_fuel_liters, am.driver_name, am.assets_image_path, am.driver_image, am.device_id, lm.add_date, TIME_TO_SEC(TIMEDIFF(CONVERT_TZ( NOW( ) , '+00:00', '".$this->session->userdata('diff_from_gmt')."' ) , lm.add_date)) as beforeTime, lm.address, lm.lati, lm.longi, lm.speed, lm.old_speed, lm.ignition, im.icon_path, lm.current_area, lm.current_zone, lm.current_landmark, lm.cross_speed, lm.fuel_percent, lm.fuel_liter, lm.fuel_time, lm.temperature, lm.captured_image  from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id LEFT JOIN icon_master im ON im.id = am.icon_id where am.status=1 AND am.del_date is null And ".$sub." ".$srch." ".$stsWhr." order by am.assets_name LIMIT ".$start.", ".$limit;
		//die($Fqry1);
		$query = $this->db->query($Fqry1, FALSE);
		
		//lm.add_date desc
		
		/*$query = $this->db->query("SELECT tr.id, tr.device_id, tr.add_date, TIME_TO_SEC(TIMEDIFF(CONVERT_TZ( NOW( ) , '+00:00', '+05:30' ) , tr.add_date)) as beforeTime, tr.address, tr.lati, tr.longi, tr.speed, tr.ignition, (

		SELECT assets_name
		FROM assests_master
		WHERE device_id = tr.device_id
		LIMIT 1
		) AS assets_name, (
		
		SELECT driver_name
		FROM assests_master
		WHERE device_id = tr.device_id
		LIMIT 1
		) AS driver_name, (
		
		SELECT driver_image
		FROM assests_master
		WHERE device_id = tr.device_id
		LIMIT 1
		) AS driver_image, (
		
		SELECT id
		FROM assests_master
		WHERE device_id = tr.device_id
		LIMIT 1
		) AS assets_id, (
		
		SELECT sim_number
		FROM assests_master
		WHERE device_id = tr.device_id
		LIMIT 1
		) AS sim_number, (

		SELECT im.icon_path
		FROM assests_master am
		LEFT JOIN icon_master im ON im.id = am.icon_id
		WHERE am.device_id = tr.device_id
		LIMIT 1
		) AS icon_path
		FROM (

		SELECT dm.device_id, MAX( dm.id ) AS max_id, am.assets_name
		FROM tbl_track dm
		LEFT JOIN assests_master am ON am.device_id = dm.device_id
		WHERE $sub
		GROUP BY dm.device_id
		) AS x
		INNER JOIN tbl_track AS tr ON tr.device_id = x.device_id
		AND tr.id = x.max_id $srch $speedWhr order by tr.add_date desc LIMIT $start, $limit", FALSE);
		*/
		//echo $this->db->last_query();
		//exit;		
		return array($query->result(), $total_pages, $page, $totaldata, $lmt);
	}
	public function get_landmark($user)
	{
		$sql = "SELECT lm.*, (select group_concat(concat(assets_name,'(',device_id,')')) assets from assests_master where find_in_set(device_id, lm.device_ids)) as assets FROM `landmark` lm where lm.add_uid = $user and lm.del_date is null and lm.status = 1";
		$query = $this->db->query($sql, FALSE);
		return $query->result();
	}
	public function edit_route()
	{
		$query = $this->db->query("SELECT * FROM `tbl_routes` where id = ".$_POST['id'], FALSE);
		return $query->row();
	}
	public function edit_landmark()
	{
		$query = $this->db->query("SELECT * FROM `landmark` lm where id = ".$_POST['id'], FALSE);
		return $query->row();
	}
	public function edit_area()
	{
		$query = $this->db->query("SELECT * FROM `areas` lm where polyid = ".$_POST['id'], FALSE);
		return $query->row();
	}
	public function removeLandmark(){
		$id = $_POST['id'];
		//$this->db->delete('landmark', array('id' => $id)); 
		$res = $this->db->query("update landmark set del_date = '".gmdate('Y-m-d H:i:s')."', status = 0 where id =$id");
		
		//********send command to remove landmark in device******//
		$file_path = '../telnet/cmd.txt';
		
		//remove geo from device
		$query = $this->db->query("select (select device_id from assests_master where id = assets_landmark.assets_id) as device_id, assets_id, landmark_index from assets_landmark where landmark_id = '".$_POST['id']."' and assets_id not in(".$_POST['device'].")");
		$rows = $query->result();
		$command = '';	
		
		foreach ($rows as $row) {
			$device_id = $row->device_id;
			$assets_id = $row->assets_id;
			$landmark_index = $row->landmark_index;
			$command .= "send $device_id geo=,,,,\n";
		}
		if($command != ""){
			$file_handle = fopen($file_path, "w");
			fwrite($file_handle, $command);
			fclose($file_handle);
		}
		$query = $this->db->query("update assets_landmark set landmark_id = 0 where landmark_id = '$id')");
		//**************//
		return;
	}
	public function get_todays_points($device){
		$query = $this->db->query("select lati, longi from ".$this->table_name." where date(add_date) = '" . date('Y-m-d') . "' and device_id = $device");
		return $query->result();
	}
	
	public function get_group($user)
	{
		if($user != 1){
			$sql = "SELECT gm.id, gm.group_name, GROUP_CONCAT( am.id ) as assets FROM assests_master am LEFT JOIN group_master gm ON gm.id = assets_group_id, user_assets_map um WHERE find_in_set( assets_group_id, um.group_id ) AND um.user_id = $user GROUP BY assets_group_id ORDER BY gm.group_name";
		}else{
			$sql = "SELECT gm.id, gm.group_name, GROUP_CONCAT( am.id ) as assets FROM assests_master am LEFT JOIN group_master gm ON gm.id = assets_group_id, user_assets_map um WHERE find_in_set( assets_group_id, um.group_id ) GROUP BY assets_group_id ORDER BY gm.group_name";
		}
		
		// "select id, group_name, assets from group_master where status=1 AND del_date is null AND add_uid = $user ORDER BY group_name"
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function get_areas($user) 
	{
		if(count($user) > 0) {
			
			$user_ids = implode(',', $user);
			$q = "SELECT polyid, polyname FROM `areas` WHERE Audit_Status = 1 AND Audit_Del_Dt is null AND Audit_Enter_uid IN($user_ids) group by polyid ORDER BY polyname";
			$query = $this->db->query($q);
			return $query->result();
		}
		return false;
	}
	
	public function get_zones($user) 
	{
		if(count($user) > 0) {
			
			$user_ids = implode(',', $user);
			$q = "SELECT polyid, polyname FROM `landmark_areas` WHERE Audit_Status = 1 AND Audit_Del_Dt is null AND Audit_Enter_uid IN($user_ids) group by polyid ORDER BY polyname";
			$query = $this->db->query($q);
			return $query->result();
		}
		return false;
	}
	
	public function get_landmarks($user) 
	{
		if(count($user) > 0) {
			
			$user_ids = implode(',', $user);
			$q = "SELECT id, name FROM landmark WHERE status=1 AND del_date is null AND add_uid IN($user_ids) ORDER BY name";
			$query = $this->db->query($q);
			return $query->result();
		}
		return false;
	}
		
	public function get_subuser($user)
	{
		$today=strtolower(date("l"));
		$query = $this->db->query("select user_id, username, first_name, last_name from tbl_users where status=1 And del_date is null And (find_in_set('$today',display_day) or display_day='all') and admin_id = $user ORDER BY username");
		return $query->result();	
	}
	public function get_subuser_detail($uid) 
	{
		$uid=str_replace('u-', "", $uid);
		$query = $this->db->query("select user_id, username, first_name, last_name, mobile_number, email_address, from_date, to_date, email_alert, sms_alert from tbl_users where user_id=".$uid. " ORDER BY username");
		return $query->result_array();
		
	}
	public function get_group_nm($uid) 
	{
		$uid=str_replace('g-', "", $uid);
		$query = $this->db->query("select id, group_name from group_master where id=".$uid);
		return $query->result_array();
		
	}
	public function get_subuser_assets_detail($uid) 
	{
		$uid=str_replace('u-', "", $uid);
		$query = $this->db->query("select assets_ids from user_assets_map where user_id=".$uid);
		return $query->result_array();
		
	}
	public function get_group_assets_detail($uid) 
	{
		$uid=str_replace('g-', "", $uid);
		$query = $this->db->query("select assets from group_master where id=".$uid);
		return $query->result_array();
		
	}

	public function device_map($user) 
	{
		$device_ids = uri_assoc('id');
		
		$sub = "am.device_id in(SELECT device_id FROM assests_master where id in($device_ids))";
		
		$query = $this->db->query("SELECT lm.id, route.routename, am.current_trip, lndm.lat as lat_n, lndm.lng as lng_n, lndm.name as landmark_n, am.id as assets_id, am.assets_category_id, am.assets_friendly_nm, am.assets_name, am.sim_number, am.driver_name, am.driver_mobile, am.driver_image, am.assets_image_path, am.device_id, CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date , TIME_TO_SEC(TIMEDIFF( NOW( ), CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."'))) as beforeTime, lm.address, lm.lati, lm.longi, lm.speed, lm.ignition, im.icon_path, lm.fuel_percent, lm.fuel_liter, lm.fuel_time, lm.temperature, lm.angle_dir from assests_master am left join tbl_routes route on route.id = am.current_trip left join landmark lndm on lndm.id = am.next_trip_landmark left join tbl_last_point lm on lm.device_id = am.device_id LEFT JOIN icon_master im ON im.id = am.icon_id where $sub");
		
		return $query->result();
	}
	public function save_dist($user) {
		
		$distanceVehicle = $_POST['distanceVehicle'];
		$fromVehicleId 	= $_POST['fromVehicleId'];
		$toVehicleId 	= $_POST['toVehicleId'];
		$vDEndLatLong 	= $_POST['vDEndLatLong'];
		$vDStartLatLong = $_POST['vDStartLatLong'];
		
		for($i=0; $i<count($fromVehicleId); $i++){
			$asset_id1 		= $fromVehicleId[$i];
			$asset_id2 		= $toVehicleId[$i];
			$asset1_lat_lng = str_replace(":", ",", $vDStartLatLong[$i]);
			$asset2_lat_lng = str_replace(":", ",", $vDEndLatLong[$i]);
			$distance 		= $distanceVehicle[$i];
			
			$datetime = "'".date("Y-m-d H:i:s")."'";
			
			$data = array('add_uid'=>$this->session->userdata('user_id'), 'asset_id1' => $asset_id1, 'asset_id2'=>$asset_id2, 'asset1_lat_lng'=>$asset1_lat_lng, 'asset2_lat_lng' => $asset2_lat_lng, 'distance' => $distance, 'add_date' => gmdate('Y-m-d H:i:s'));
			$query = $this->db->insert_string('tbl_distance_inspection', $data);
			$this->db->query($query);
		}	
		return;
	}
	public function getCoord($user) 
	{
		$device_ids = uri_assoc('id');
		
		$qry="SELECT am.id, lm.lati, lm.longi,am.assets_name, am.assets_image_path from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.id in ($device_ids)";
		$query = $this->db->query($qry);
		
		return $query->result_array();
	}
	public function getAllCoord($user) 
	{
		
		$qry="select am.id, am.device_id, am.assets_name, lm.lati, lm.longi from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = ".$user." ))";
		//die($qry);
		$query = $this->db->query($qry);
		
		return $query->result_array();
	}
	function updateUser() {
		$success = 'true';
		$old_assets_ids = "";
		$assets_ids = explode(",",$_POST['assets_ids']);
		$add_to_user = $_POST['add_to_user'];
		$add_to_user = str_replace('u-', "", $add_to_user);
		/*$res = $this->db->query("select * from user_assets_map where user_id =$add_to_user");
s		foreach ($res->result() as $row)
		{
			$old_assets_ids = $row ->assets_ids;
		}*/
		//$tot = implode(",",array_unique(array_merge ( $assets_ids,explode(",",$old_assets_ids))));
		$tot = implode(",",$assets_ids);
		//die($tot);
		$res = $this->db->query("update user_assets_map set assets_ids='$tot' where user_id =$add_to_user");
		$msg = "User's Assets Updated Successfully";
		
		$data = array();
		$data['result'] = $success;
		$data['insert_id'] = "Null";
		$data['msg'] = $msg;
		return ($data);
	}
	function addUser() {
	
		$success = 'true';
		$qry="select * from tbl_users where username='".$_POST['username']."' AND status=1 and del_date is null";
		$rarr=$this->db->query($qry);
		if($rarr->num_rows()<1)
		{
		$user = $this->session->userdata('user_id');
		$assets_ids = $_POST['assets_ids'];
		unset($_POST['add_to_user']);
		unset($_POST['assets_ids']);
		unset($_POST['u_id']);
		unset($_POST['cmb_assets']);
		$_POST['from_date'] = date('Y-m-d H:i:s', strtotime($_POST['from_date']));
		$_POST['to_date'] = date('Y-m-d H:i:s', strtotime($_POST['to_date']));
		$_POST['admin_id'] = $user;
		$_POST['usertype_id'] = 3;
		$_POST['add_date'] = gmdate('Y-m-d H:i:s');
		$_POST['password'] = md5($_POST['password']);
		$_POST['sms_alert'] = isset($_POST['sms_alert'])?$_POST['sms_alert']:0;
		$_POST['email_alert'] = isset($_POST['email_alert'])?$_POST['email_alert']:0;
		//die(print_r($_POST));
		$this->db->insert("tbl_users", $_POST);
		$insert_id = $this->db->insert_id();
		
		$menu_query=$this->db->query("select * from app_menu_master where user_id = $user and del_date is null");
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
			$inserquery=$this->db->query("insert into app_menu_master(".$allcolumn.") values('',$menu_id,$priority,'$where_slow',$insert_id,$user,'$add_date',null,null,$status,'$comments')");
		} 
		
		$this->db->query('insert into user_assets_map (user_id, assets_ids, add_date, add_uid, status) values("'.$insert_id.'", "'.$assets_ids.'", "'.gmdate('Y-m-d H:i:s').'", '.$user.', 1)');
		
		$msg = "User Created Successfully";
		$data = array();
		$data['result'] = $success;
		$data['insert_id'] = $insert_id;
		$data['msg'] = $msg;
		return $data;
		}
		else
		{
			$success=false;
			$msg = "This Username Already Taken, Please Choose Unique Username.";
			$data = array();
			$data['result'] = $success;
			$data['insert_id'] = -1;
			$data['msg'] = $msg;
			return $data;
		}

	}
	function editUser() {
	
		$success = 'true';
		$user = $this->session->userdata('user_id');
		$qry="select * from tbl_users where username='".$_POST['username']."' AND status=1 and del_date is null AND user_id!=".$_POST['u_id'];
		$rarr=$this->db->query($qry);
		if($rarr->num_rows()<1)
		{
		$usr_id=$_POST['u_id'];
		$assets_ids = $_POST['assets_ids'];
		unset($_POST['add_to_user']);
		unset($_POST['assets_ids']);
		unset($_POST['u_id']);
		unset($_POST['cmb_assets']);
		$pass="";
		if(isset($_POST['password']) && $_POST['password']!="")
		$pass.=' password=\''.md5($_POST["password"]).'\', ';
		$from_date= date('Y-m-d H:i:s', strtotime($_POST['from_date']));
		$to_date = date('Y-m-d H:i:s', strtotime($_POST['to_date']));
		$sms_alert = isset($_POST['sms_alert'])?$_POST['sms_alert']:0;
		$email_alert = isset($_POST['email_alert'])?$_POST['email_alert']:0;
		$email_address = $_POST['email_address'];
		$mobile_number = $_POST['mobile_number'];
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$username = $_POST['username'];
		
		$query_update='UPDATE  tbl_users set username=\''.$username.'\', '.$pass.' from_date=\''.$from_date.'\', to_date=\''.$to_date.'\', sms_alert='.$sms_alert.', email_alert='.$email_alert.', first_name=\''.$first_name.'\', last_name=\''.$last_name.'\', mobile_number=\''.$mobile_number.'\', email_address=\''.$email_address.'\' where user_id='.$usr_id;
		//die($query_update);
		$this->db->query($query_update);
		/*
		$menu_query=$this->db->query("select * from app_menu_master where user_id = $user and del_date is null");
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
			$inserquery=$this->db->query("insert into app_menu_master(".$allcolumn.") values('',$menu_id,$priority,'$where_slow',$insert_id,$user,'$add_date',null,null,$status,'$comments')");
		} 
		
		$this->db->query('insert into user_assets_map (user_id, assets_ids, add_date, add_uid, status) values("'.$insert_id.'", "'.$assets_ids.'", "'.date('Y-m-d H:i:s').'", '.$user.', 1)');
		*/
		$msg = "User Updated Successfully";
		
		$data = array();
		$data['result'] = $success;
		$data['insert_id'] = $usr_id;
		$data['msg'] = $msg;
		return $data;
		}
		else
		{
			$success=false;
			$msg = "This Username Already Taken, Please Choose Unique Username.";
			$data = array();
			$data['result'] = $success;
			$data['insert_id'] = -1;
			$data['msg'] = $msg;
			return $data;
		}

	}
	function addLandmark() {

		$success = 'true';
		/*$group_name = $_POST['group_nm'];
		$groupid="select id from landmark_group where landmark_group_name = '".$group_name."'";
		$groupidresult=mysql_query($groupid);
		$row=mysql_fetch_array($groupidresult);*/
		$group_id=$_POST['group_nm'];
		//die($group_id);
		if(!empty($group_id))
		{
			$pData['group_id'] = $group_id;
		}
		$user = $this->session->userdata('user_id');
		$_POST['name'] = trim(str_replace(array("\n",'\n\r'," "), "", $_POST['name']));
		$pData['name'] = $_POST['name'];
		$pData['address'] = trim(str_replace(array("\n",'\n\r'), " ", $_POST['address']));
		$pData['radius'] = $_POST['radius'];
		$pData['distance_unit'] = $_POST['distance_unit'];
		$pData['device_ids'] = $_POST['device'];
		$pData['icon_path'] = $_POST['icon'];
		$pData['lat'] = round($_POST['lat'], 6);
		$pData['lng'] = round($_POST['lng'], 6);
		$pData['sms_alert'] = ($_POST['sms_alert'] == 'true') ? 1 : 0;
		$pData['email_alert'] = ($_POST['email_alert'] == 'true') ? 1 : 0;
		$pData['alert_before_landmark'] = $_POST['alert_before_landmark'];
		$pData['add_date'] = gmdate('Y-m-d H:i:s');
		$pData['add_uid'] = $user;
		$pData['status'] = 1;
		$addressbook_ids = $_POST['addressbook_ids'];
		$pData['comments'] = trim(str_replace(array("\n",'\n\r'), " ", $_POST['comments']));
		
		/*if($addressbook_ids != "null" || $addressbook_ids != "null"){
			echo "Not null";
		}
		else
		{
			echo "null";
		}
		die();*/
		if($addressbook_ids != "" && $addressbook_ids != "null"){
			$pData['addressbook_ids'] = implode(",", $addressbook_ids);
		}
		if($_POST['id'] != ""){
			$this->db->where('id',$_POST['id']);
			$this->db->update('landmark',$pData);
			$insert_id = $_POST['id'];
			
		}else
		{
			$this->db->insert("landmark", $pData);
			$insert_id = $this->db->insert_id();
		}
		$msg = "Landmark Created Successfully";
		
		//********send command to store landmark in device******//
		$file_path = '../telnet/cmd.txt';
		if($_POST['id'] != ""){		//if edit
			//remove geo from device
			$query = $this->db->query("select (select device_id from assests_master where id = assets_landmark.assets_id) as device_id, assets_id, landmark_index from assets_landmark where landmark_id = '".$_POST['id']."' and assets_id not in(".$_POST['device'].")");
			$rows = $query->result();
			$command = '';	
			
			foreach ($rows as $row) {
				$device_id = $row->device_id;
				$assets_id = $row->assets_id;
				$landmark_index = $row->landmark_index;
				$command .= "send $device_id geo=,,,,\n";
				
				$query = $this->db->query("update assets_landmark set landmark_id = 0 where assets_id = '$assets_id' and landmark_index = '$landmark_index')");
			}
			if($command != ""){
				$file_handle = fopen($file_path, "w");
				fwrite($file_handle, $command);
				fclose($file_handle);
			}
			//store geo to device
			$command = '';
			$query = $this->db->query("select (select landmark_index from assets_landmark where assets_id = assests_master.id and landmark_id = 0 limit 1) as empty_index, (select landmark_index from assets_landmark where assets_id = assests_master.id and landmark_id = '".$_POST['id']."') as landmark_index, (select max(landmark_index) + 1 from assets_landmark where assets_id = assests_master.id) as next_landmark_index, device_id from assests_master where id in(".$_POST['device'].")");
			$rows = $query->result();
			$command = '';		
			foreach ($rows as $row) {
				$device_id = $row->device_id;
				$landmark_index = $row->landmark_index;
				$empty_index = $row->empty_index;
				$next_landmark_index = $row->next_landmark_index;
				if($landmark_index != ''){
					$landmark_index = $next_landmark_index;
				}
				if($landmark_index == ''){
					$landmark_index = 1;
				}
				if($landmark_index > 100){
					$landmark_index = $empty_index;
				}
				if($landmark_index != ""){
					if($_POST['distance_unit'] == 'KM'){
						$radius = $_POST['radius'] * 1000;
					}else if($_POST['distance_unit'] == 'Mile'){
						$radius = $_POST['radius'] * 1000 * 1.609344;
					}else{
						$radius = $_POST['radius'];
					}
					$command .= "send $device_id geo=$landmark_index,".$_POST['lat'].",".$_POST['lng'].",".$_POST['name'].",$radius\n";
				}
			}		
		}else{
			$query = $this->db->query("select (select landmark_index from assets_landmark where assets_id = assests_master.id and landmark_id = 0 limit 1) as empty_index, (select max(landmark_index) + 1 from assets_landmark where assets_id = assests_master.id) as landmark_index, device_id from assests_master where id in(".$_POST['device'].")");
			$rows = $query->result();
			$command = '';		
			foreach ($rows as $row) {
				$device_id = $row->device_id;
				$landmark_index = $row->landmark_index;
				$empty_index = $row->empty_index;
				if($landmark_index == ''){
					$landmark_index = 1;
				}
				if($landmark_index > 100){
					$landmark_index = $empty_index;
				}
				if($landmark_index != ""){
					if($_POST['distance_unit'] == 'KM'){
						$radius = $_POST['radius'] * 1000;
					}else if($_POST['distance_unit'] == 'Mile'){
						$radius = $_POST['radius'] * 1000 * 1.609344;
					}else{
						$radius = $_POST['radius'];
					}
					$command .= "send $device_id geo=$landmark_index,".$_POST['lat'].",".$_POST['lng'].",".$_POST['name'].",$radius\n";
				}
			}		
		}		
		
		if($command != ""){
			$file_handle = fopen($file_path, "w");
			fwrite($file_handle, $command);
			fclose($file_handle);
		}
		//**************//
		$data = array();
		$data['result'] = $success;
		$data['insert_id'] = $insert_id;
		$data['msg'] = $msg;
		return $data;

	}
	function getLandmarkGroups(){
		$user_id = $this->session->userdata('user_id');
		$SQL="select id,landmark_group_name from landmark_group where user_id = $user_id";
		$rarr=$this->db->query($SQL);
		return $rarr->result();
	}
	function updateArea() { 

		$success = 'true';
		
		$pData['polyname'] = $_POST['name'];
		$pData['color'] = $_POST['color'];
		
		$pData['deviceid'] = $_POST['device'];
		$pData['sms_alert'] = ($_POST['sms_alert'] == 'true') ? 1 : 0;
		$pData['email_alert'] = ($_POST['email_alert'] == 'true') ? 1 : 0;
		$pData['in_alert'] = ($_POST['in_alert'] == 'true') ? 1 : 0;
		$pData['out_alert'] = ($_POST['out_alert'] == 'true') ? 1 : 0;	
		$addressbook_ids = $_POST['addressbook_ids'];
		$pData['area_type_opt'] = $_POST['area_type_opt'];
		if($addressbook_ids != ""){
			$pData['addressbook_ids'] = implode(",", $addressbook_ids);
		}else{
			$pData['addressbook_ids'] = '';
		}
		$this->db->where('polyid',$_POST['polyid']);
		$this->db->update('areas',$pData);
		$insert_id = $_POST['polyid'];
		
		$msg = "Area Updated Successfully";
		
		$data = array();
		$data['result'] = $success;
		$data['insert_id'] = $_POST['polyid'];
		$data['msg'] = $msg;
		return $data;

	}
	function addToGroup() {

		$success = 'true';
		$user = $this->session->userdata('user_id');
		$assets_ids = uri_assoc('assets');
		$group = uri_assoc('group');
		$cmd = uri_assoc('cmd');
		$group = str_replace("g-","",$group);
		$newgp = uri_assoc('newgp');
		$insert_id="";
		$msg="";
		if($cmd == "new"){
			$qry="select * from group_master where  group_name='".$newgp."' AND status=1 and del_date is null AND add_uid=".$user;
			$rarr=$this->db->query($qry);
			if($rarr->num_rows()<1)
			{
				$gArr['group_name'] = $newgp;
				//$gArr['assets'] = $assets_ids;
				$gArr['add_uid'] = $user;
				$gArr['add_date'] = gmdate('Y-m-d H:i:s');
				$gArr['status'] = 1;
				$this->db->insert("group_master", $gArr);
				$insert_id = $this->db->insert_id();
				$msg = "Group Created Successfully";
			}
			else
			{
				$success=false;
				$msg = "Duplicate Group Name Found.";
				$insert_id = -1;
			}
		}else if($cmd=="update"){
			$this->db->query("update group_master set assets = '$assets_ids' WHERE id=".$group);
			$insert_id = -1;
			$msg = "Group's Assets Updated Successfully";
		}
		//else if($cmd == "edit"){
		else{	
			$qry="select * from group_master where  group_name='".$newgp."' AND status=1 and del_date is null AND id!=".$group;
			$rarr=$this->db->query($qry);
			if($rarr->num_rows()<1)
			{
				$this->db->query("update group_master set group_name = '$newgp' WHERE id=".$group);
				$insert_id = -1;
				$msg = "Group Updated Successfully";
			}
			else
			{
				$success=false;
				$msg = "Duplicate Group Name Found.";
				$insert_id = -1;
			}
			
		}

		$data = array();
		$data['result'] = $success;
		$data['insert_id'] = $insert_id;
		$data['msg'] = $msg;
		return $data;
	}
	function editToGroup() {

		$success = 'true';
		
		$user = $this->session->userdata('user_id');
		$assets_ids = uri_assoc('assets');
		$group = uri_assoc('cmd');
		$group = str_replace("g-","",$group);
		$newgp = uri_assoc('newgp');
		if($group == "new"){
			$gArr['group_name'] = $newgp;
			$gArr['assets'] = $assets_ids;
			$gArr['add_uid'] = $user;
			$gArr['add_date'] = gmdate('Y-m-d H:i:s');
			$gArr['status'] = 1;
			$this->db->insert("group_master", $gArr);
			$insert_id = $this->db->insert_id();
			$msg = "Group Created Successfully";
		}else{
			$query = $this->db->query("select * from group_master where id = $group");
			$row = $query->row();
			$assets = $row->assets;
			if($assets != ""){
				$assets_ids = $assets.",".$assets_ids;
				$assets_ids = explode(",", $assets_ids);
				$assets_ids = array_unique($assets_ids);
				$assets_ids = implode(",", $assets_ids);
			}
			$this->db->query("update group_master set assets = '$assets_ids' WHERE id=".$group);
			$insert_id = "";
			$msg = "Group Updated Successfully";
		}
		$data = array();
		$data['result'] = $success;
		$data['insert_id'] = $insert_id;
		$data['msg'] = $msg;
		return $data;
	}
	//assets dashboard
	public function assets_det() 
	{
		$device_ids = uri_assoc('id');
		$query = $this->db->query("select am.*, im.icon_path, lp.runtime, lp.data_type, lp.battery_status, lp.alarm_type, ad.division, ao.owner from assests_master am left join icon_master im on im.id = am.icon_id left join tbl_last_point lp on lp.device_id = am.device_id LEFT JOIN assests_division_master ad ON ad.id = am.assets_division LEFT JOIN assests_owner_master ao ON ao.id = am.assets_owner where am.id = $device_ids");
		return $query->row();
	}
	public function current_location() 
	{
		$device_id = uri_assoc('id');
		
				
		$query = $this->db->query("SELECT lp.id, lp.device_id, CONVERT_TZ(lp.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, lp.lati, lp.longi, lp.speed, lp.ignition, am.assets_name, am.sim_number, lp.address from tbl_last_point lp left join assests_master am on am.device_id = lp.device_id left join icon_master im ON im.id = am.icon_id
		WHERE am.id = $device_id order by lp.add_date desc limit 1", FALSE);
		return $query;
	}
	public function distance_today(){
		$device_id = uri_assoc('id');
		$query = $this->db->query("select distance from distance_master where CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."')  BETWEEN '".date('Y-m-d')." 00:00:00' AND '".date('Y-m-d')." 23:59:59' and assets_id = $device_id");
		
		return $query->row();
	}
	
	public function current_speed(){
		$device_id = uri_assoc('id');
		$query = $this->db->query("select lp.speed from tbl_last_point lp left join assests_master am on am.device_id = lp.device_id where am.id = $device_id limit 1");
		return $query->row();
	}
	
	public function save_route(){
		$json = $_POST['str'];
		//echo $json;
		//exit;
		$obj = json_decode($json);
		//print_r($obj->waypoints);
		//exit;
		$start_point = $obj->start->lat.",".$obj->start->lng;
		$end_point = $obj->end->lat.",".$obj->end->lng;
		$wpArr = array();
		foreach($obj->waypoints as $wp){
			$wpArr[] = $wp[0].",".$wp[1];
		}
		
		if(count($wpArr) > 0)
			$wp = implode(":",$wpArr);
		else
			$wp = "";
		foreach($obj->points as $pts){
			$ptsArr[] = $pts[0].",".$pts[1];
		}
		$route_pnt = implode(":",$ptsArr);
		
		$route_nm	= $_POST['route_name'];
		if($_POST['route_device']){
			$device 	= implode(",", $_POST['route_device']);
		}else{
			$device 	= "";
		}
		
		$color 		= $_POST['route_color'];
		$d_value 	= $_POST['distance_value'];
		$d_unit 	= $_POST['distance_unit'];
		$sms_alert 	= $_POST['sms_alert'];
		$email_alert 	= $_POST['email_alert'];
		$round_trip 	= $_POST['roundTrip'];
		
		$total_distance = $_POST['total_distance'];
		$total_time_in_minutes = $_POST['total_time_in_minutes'];
		$landmark_ids 	= $_POST['landmarks'];
		if($round_trip == 1){
			$start_landmark = explode(",", $landmark_ids);
			$start_landmark = $start_landmark[0];
			$landmark_ids = $landmark_ids.",".$start_landmark;
		}
		if($route_nm == "" || count($route_pnt) == 0){
			die("Missing parameter");
		}
		
		$datetime = "'".gmdate("Y-m-d H:i:s")."'";
		
		$data = array('userid'=>$this->session->userdata('user_id'), 'start_point' => $start_point, 'end_point'=>$end_point, 'waypoints'=>$wp, 'route_color' => $color, 'deviceid' => $device, 'routename' => $route_nm, 'points' => $route_pnt, 'add_date' => $datetime, 'add_uid' => $this->session->userdata('user_id'), 'status' => 1, 'distance_value'=>$d_value, 'distance_unit'=>$d_unit, 'sms_alert'=>$sms_alert, 'email_alert'=>$email_alert, 'landmark_ids'=>$landmark_ids, 'total_distance'=>$total_distance, 'total_time_in_minutes'=>$total_time_in_minutes, 'round_trip'=>$round_trip, 'add_date' => gmdate('Y-m-d H:i:s'));
		$query = $this->db->insert_string('tbl_routes', $data);
		$this->db->query($query);
		$insert_id = $this->db->insert_id();	
			//sub-trip
			$landmark_arr = explode(",", $landmark_ids);
			$first_landmark = $landmark_arr[0];
			$last_landmark = end($landmark_arr);
			
			$query = $this->db->query("select * from landmark where id in ($landmark_ids) ORDER BY FIELD( id, $landmark_ids )");
			$rows = $query->result();
			$i=0;
			$total_rows = count($rows);
			foreach ($rows as $row) {
				if($i == 0){
					$start_id_sub = $row->id;
					$start_point_sub = $row->lat.",".$row->lng;
					$first_id_sub = $row->id;
					$first_point_sub = $row->lat.",".$row->lng;
					
				}else{
					if($i == ($total_rows-1)){
						if($first_landmark != $last_landmark){
							$color = "#000000";
						}
					}
					$end_id_sub = $row->id;
					$end_point_sub = $row->lat.",".$row->lng;
					$sub_order = $i;
					$landmark_ids = "$start_id_sub,$end_id_sub";
					
					$data = array('route_id'=>$insert_id, 'sub_order' => $sub_order, 'route_color'=>$color, 'landmark_ids'=>$landmark_ids, 'start_point' => $start_point_sub, 'end_point' => $end_point_sub, 'add_uid' => $this->session->userdata('user_id'), 'add_date' => gmdate('Y-m-d H:i:s'));
					$query = $this->db->insert_string('tbl_sub_routes', $data);
					$this->db->query($query);
					
					$start_id_sub = $end_id_sub;
					$start_point_sub = $end_point_sub;
				}
				$i++;
			}
			if($first_landmark == $last_landmark){		//round trip
				$color = "#000000";
				$sub_order = $sub_order + 1;
				$end_id_sub = $first_id_sub;
				$end_point_sub = $first_point_sub;
				$landmark_ids = "$start_id_sub,$end_id_sub";
				$data = array('route_id'=>$insert_id, 'sub_order' => $sub_order, 'route_color'=>$color, 'landmark_ids'=>$landmark_ids, 'start_point' => $start_point_sub, 'end_point' => $end_point_sub, 'add_uid' => $this->session->userdata('user_id'), 'add_date' => gmdate('Y-m-d H:i:s'));
				$query = $this->db->insert_string('tbl_sub_routes', $data);
				$this->db->query($query);
			}
			
			
		return $insert_id;
	}
	public function update_route() {
		$str = $_POST['str'];
		$route_pnt = array();
		
		for($i=0; $i<count($str); $i++){
			$json = $str[$i];
			$ptsArr = array();
			$wpArr = array();
			$obj = json_decode($json);
			
			$start_point = $obj->start->lat.",".$obj->start->lng;
			$end_point = $obj->end->lat.",".$obj->end->lng;
			$wpArr = array();
			$s = 0; 
			foreach($obj->waypoints as $wp){
				//if($s != 0 && $s != (count($obj->waypoints) - 1))
				$wpArr[] = $wp[0].",".$wp[1];
				
				$s++;
			}
			
			if(count($wpArr) > 0){
				$wp = implode(":",$wpArr);
			}else{
				$wp = "";
			}
			foreach($obj->points as $pts){
				$ptsArr[] = $pts[0].",".$pts[1];
			}
			if(count($ptsArr) > 0){
				$route_pnt[] = implode(":",$ptsArr);
				$subPoints = implode(":",$ptsArr);
			}else{
				$subPoints = '';
			}
			$color = $_POST['route_color'];
			if($i == (count($str) - 1)){
				$color = "#000000";
			}
			$distance = $obj->distance;
			
			$data = array('total_km'=>$distance, 'waypoints'=>$wp, 'route_color' => $color, 'points' => $subPoints);
			$this->db->where('sub_order',($i+1));
			$this->db->where('route_id',$_POST['id']);
			$this->db->update('tbl_sub_routes',$data);
		}
		
		$route_pnt = implode(":",$route_pnt);
		$wp = '';
		$route_nm	= $_POST['route_name'];
		if($_POST['route_device']){
			$device 	= implode(",", $_POST['route_device']);
		}else{
			$device 	= "";
		}
		
		$color 		= $_POST['route_color'];
		$d_value 	= $_POST['distance_value'];
		$d_unit 	= $_POST['distance_unit'];
		$sms_alert 	= $_POST['sms_alert'];
		$email_alert 	= $_POST['email_alert'];
		$round_trip 	= $_POST['roundTrip'];
		$total_distance = $_POST['total_distance'];
		$total_time_in_minutes = $_POST['total_time_in_minutes'];
		
		if($route_nm == ""){
			die("Missing parameter");
		}
				
		$data = array('waypoints'=>$wp, 'route_color' => $color, 'deviceid' => $device, 'routename' => $route_nm, 'points' => $route_pnt,'distance_value'=>$d_value, 'distance_unit'=>$d_unit, 'sms_alert'=>$sms_alert, 'email_alert'=>$email_alert, 'total_distance'=>$total_distance, 'total_time_in_minutes'=>$total_time_in_minutes, 'round_trip'=>$round_trip);
		$this->db->where('id',$_POST['id']);
		$this->db->update('tbl_routes',$data);			
		return $_POST['id'];
	}
	public function load_route_map() {
				
		$ids = $_POST['assets_id'];
		if($ids == "") $ids = 0;
		$query = $this->db->query("SELECT id, landmark_ids FROM tbl_routes WHERE del_date IS NULL AND status = 1 and id in(select current_trip from assests_master where id in($ids))");
		return $query->result();
	}
	public function load_sub_route_map($id, $my_id) {
		if($my_id != ""){
			$query = $this->db->query("SELECT * FROM tbl_sub_routes WHERE del_date IS NULL AND status = 1 and route_id = $id and id <= (SELECT id FROM tbl_sub_routes WHERE route_id = $id and landmark_ids like '%,$my_id')");
		}else{
			$query = $this->db->query("SELECT * FROM tbl_sub_routes WHERE del_date IS NULL AND status = 1 and route_id = $id");
		}
		return $query->result();
	}
	public function load_route($user_id) {
				
		$ids = $_POST['route_ids'];
		if($ids == "") $ids = 0;
		$query = $this->db->query("SELECT id, landmark_ids FROM tbl_routes WHERE userid = '".$user_id."' AND del_date IS NULL AND status = 1 and id in($ids)");
		return $query->result();
	}
	public function load_sub_route_live($id, $my_id) {
		if($my_id != ""){
			$query = $this->db->query("SELECT * FROM tbl_sub_routes WHERE del_date IS NULL AND status = 1 and route_id = $id and id <= (SELECT id FROM tbl_sub_routes WHERE route_id = $id and landmark_ids like '%,$my_id')");
		}else{
			$query = $this->db->query("SELECT * FROM tbl_sub_routes WHERE del_date IS NULL AND status = 1 and route_id = $id");
		}
		return $query->result();
	}
	public function load_sub_route($id) {
		
		$query = $this->db->query("SELECT * FROM tbl_sub_routes WHERE del_date IS NULL AND status = 1 and route_id = $id");
		
		return $query->result();
	}
	public function get_completed_trip_landmark() {
		$id = $_REQUEST['route_ids'];
		
		$query = $this->db->query("SELECT * FROM tbl_sub_routes WHERE del_date IS NULL AND status = 1 and route_id = $id and is_complete = 0 order by sub_order limit 1");	
		return $query->row();
	}
	public function get_completed_trip_landmark_map($id) {
				
		$query = $this->db->query("SELECT * FROM tbl_sub_routes WHERE del_date IS NULL AND status = 1 and route_id = $id and is_complete = 0 order by sub_order limit 1");	
		return $query->row();
	}
	public function load_route_live($user_id) {
				
		$ids = $_REQUEST['route_ids'];
		if($ids == "") $ids = 0;
		$query = $this->db->query("SELECT * FROM tbl_routes WHERE del_date IS NULL AND status = 1 and id in($ids)");
		return $query->result();
	}
	public function get_Landmark_from_comment($code){
		$user_id=$this->session->userdata('user_id');
		$trip=$_REQUEST['route_ids'];
		$asset=$_REQUEST['assets_id'];
		$qry="SELECT * FROM landmark WHERE del_date IS NULL AND status = 1 and comments = '$code' Limit 1";
		//$qry="SELECT lm.* FROM tbl_routes tr left join landmark lm on find_in_set(lm.id,tr.landmark_ids) WHERE tr.id=$trip AND lm.del_date IS NULL AND lm.status = 1 and (lm.comments like '%$code%' or lm.comments like '%$user_id%') Limit 1";
		
		$query = $this->db->query($qry);
		//die($qry);
		return $query->result();
	}
	public function load_route_edit() {
				
		$id = $_POST['id'];
		
		$query = $this->db->query("SELECT landmark_ids FROM tbl_routes WHERE del_date IS NULL AND status = 1 and id = $id");
		return $query->result();
	}
	public function load_sub_route_edit() {
				
		$id = $_POST['id'];
		
		$query = $this->db->query("SELECT * FROM tbl_sub_routes WHERE del_date IS NULL AND status = 1 and route_id = $id");
		return $query->result();
	}
	public function delete_route(){
		$id = $_POST['id'];
		//$this->db->delete('tbl_routes', array('id' => $id)); 
		$res = $this->db->query("update tbl_routes set del_date = '".gmdate('Y-m-d H:i:s')."', status = 0 where id =$id");
		return;
	}
	public function load_route_list($user_id) {
		
		$srch = isset($_POST['routeSrch'])?$_POST['routeSrch']:'';
		$sub = '';
		if($srch != ""){
			$sub = " and rm.routename like '%$srch%'";
		}
 		$query = $this->db->query("SELECT rm.id, am.driver_name, CONVERT_TZ(rm.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, rm.routename, rm.route_color, rm.distance_value, rm.distance_unit, rm.total_distance, rm.total_time_in_minutes, rm.round_trip, (select group_concat(assets_name) assets from assests_master where find_in_set(id, rm.deviceid)) as assets, rm.landmark_ids as landmark FROM tbl_routes rm left join assests_master am on am.id = rm.deviceid WHERE rm.userid = '".$user_id."' AND rm.del_date IS NULL AND rm.status = 1 $sub order by rm.id desc");
		return $query->result();
		
		//select group_concat(name order by FIELD(id,rm.landmark_ids)) from landmark where id in(rm.landmark_ids)
	}
	public function route_path($LandmarkIds) {
		
 		$query = $this->db->query("SELECT name as landmark FROM landmark WHERE find_in_set(id, '$LandmarkIds') ORDER BY FIELD( id, $LandmarkIds )");
		$result = $query->result();
		$arr = array();
		foreach($result as $row){
			$arr[] = $row->landmark;
		}
		return  implode(":", $arr);
		
		//select group_concat(name order by FIELD(id,rm.landmark_ids)) from landmark where id in(rm.landmark_ids)
	}
	public function get_route_landmark($ids)
	{
		$query = $this->db->query("SELECT lm.*, (select group_concat(concat(assets_name,'(',device_id,')')) assets from assests_master where find_in_set(id, lm.device_ids)) as assets FROM `landmark` lm where id in($ids)", FALSE);
		return $query->result();
	}
	public function get_user_photo(){
		$user_id_photo = $this->session->userdata('user_id');
		//echo $user_id_photo;
		$query = "select photo from tbl_users where user_id = $user_id_photo";
		$photo_path=$this->db->query($query);
		$value=$photo_path->result_array();
//		die($value[0]['photo']);
		$data['photo']=$value[0]['photo'];
		$this->session->set_userdata($data);
		return $value[0]['photo'];
	} 
	public function put_user_photo(){
		$filename=$_REQUEST['data'];
	
		$data = array('photo' => $filename);
		$extent = substr($filename ,strrpos($filename,".") + 1);
		$ext = strtolower($extent);
		if(($ext == 'jpg') || ($ext == 'png') || ($ext == 'jpeg') || ($ext == 'gif') || ($ext == 'bmp'))
		{
			$user_id_photo = $this->session->userdata('user_id');
			$this->db->where('user_id',$user_id_photo);
			$this->db->update('tbl_users',$data);
		}else{
			die("only image jppeg,png,bmp,gif  allow");
		}	
	}
	public function addressbook_opt() 
	{		
		
		$user_id = $this->session->userdata('user_id');
		$this->db->select('id, name');
		$this->order_by = 'name';
		if(isset($_REQUEST['id']) && $_REQUEST['id'] != ""){
			$this->db->where("group_id", $_REQUEST['id']);
		}
		$this->db->where("add_uid",$user_id);
		$query = $this->db->get('addressbook');
		return $query->result();
	}	
	public function addressbook_group_opt() 
	{		
		$user_id = $this->session->userdata('user_id');
		$this->db->select('id, group_name');
		$this->order_by = 'group_name';
		$this->db->where("add_uid",$user_id);
		$query = $this->db->get('addressbook_group');
		return $query->result();
	}
	public function getUserDisplay_Settings(){
		$user_id = $this->session->userdata('user_id');
		/*$this->db->select('link, status');
		$this->db->where("uid",$user_id);
		$query = $this->db->get('tbl_users_display_settings');
		return $query->result();*/
		$chk=array();
		$SQL="select link, link_title from tbl_display_values where del_date is null and del_uid is null";
		$query = $this->db->query($SQL);
		$link_menu=array();
		foreach($query->result() as $row)
		{
			if($row->link=='show_map_trip_button' || $row->link=='show_map_landmark_button'){
				$chk[$row->link]=1;
			}else{
				if($this->session->userdata("usertype_id")==3){
					$chk[$row->link]=0;
				}else{
					$chk[$row->link]=1;
				}
			}
			
		}		
		$SQL_app="select link, status from tbl_users_display_settings where uid=$user_id";
		$query_app = $this->db->query($SQL_app);
		$display_setting = array();
		$link_title = array();
		foreach($query_app->result() as $row)
		{
			$id = $row->link;
			$status = $row->status;
			if(array_key_exists($id,$chk)){
				$chk[$id] = $status;
			}
		}	
		return $chk;
	}
	public function getServiceExpiryAlertBeforeDays()
	{
		$SQL = "select data_value from tbl_settings where data_key = 'service_expiry_alert_before_days'";
		$query = $this->db->query($SQL);
		return $query->row();
	}
	public function getMessage()
	{
		$SQL = "select data_value from tbl_settings where data_key = 'message'";
		$query = $this->db->query($SQL);
		return $query->row();
	}
	public function getRemainingDays()
	{
		$user_id = $this->session->userdata('user_id');
		$SQL = "SELECT DATEDIFF( to_date,  now() ) AS days, CONVERT_TZ(to_date,'+00:00','".$this->session->userdata('timezone')."') as to_date from tbl_users where user_id='$user_id'";
		$query = $this->db->query($SQL);
		return $query->row();
	}
	
	public function getZoneNameSetting()
	{
		$user_id = $this->session->userdata('user_id');
		$SQL = "SELECT show_zone_name from tbl_users where user_id='$user_id'";
		$query = $this->db->query($SQL);
		$row = $query->row();
		return $row->show_zone_name;
	}
	public function get_devices_admin($report) 
	{
		$sub = "";
		if($report != ""){
			$rptsub = substr($report, 0, 2);
			
			$group = "";
			$user = "";
			if($rptsub == "g-"){
				$group = str_replace($rptsub, "", $report);
			}		
			elseif($rptsub == "u-"){
				$user = str_replace($rptsub, "", $report);
			}
			
			if($group != ""){
				$sub .= " AND am.assets_group_id = $group";
				/*
				$this->db->select("assets", FALSE);
				$this->db->where('id', $group);
				$this->db->limit(1);
				$query = $this->db->get('group_master');			
				$rows = $query->result();
				foreach ($rows as $row) {
					$assets = $row->assets;
				}
				if($assets!="")
					$sub = " AND am.id in($assets)";
				else
					$sub = " AND am.id in(-1)";
				*/
			}
			elseif($user != ""){		
				$sub = " AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = $user))";			
			}
			else{
				switch ($report) { case "running":
						$sub = " AND (TIME_TO_SEC(TIMEDIFF(NOW( ), lm.add_date)) < ".$this->session->userdata('network_timeout')." and lm.speed > 0)";	break;
					case "parked":
						$sub = " AND TIME_TO_SEC(TIMEDIFF(NOW( ), lm.add_date)) < ".$this->session->userdata('network_timeout')." and lm.speed = 0 and lm.ignition = 0"; break;
					case "idle":
						$sub = " AND TIME_TO_SEC(TIMEDIFF(NOW( ), lm.add_date)) < ".$this->session->userdata('network_timeout')." and lm.speed = 0 and lm.ignition = 1"; break;
					case "out_of_network":
						$sub = " AND TIME_TO_SEC(TIMEDIFF(NOW( ), lm.add_date)) BETWEEN ".$this->session->userdata('network_timeout')." AND ".($this->session->userdata('network_timeout')+36000)."";	break;
					case "device_fault":
						$sub = " AND (TIME_TO_SEC(TIMEDIFF(NOW( ), lm.add_date)) > ".($this->session->userdata('network_timeout')+36000)." or (lm.add_date is null))"; break;
				}
			}
		}
		$query = $this->db->query("select am.id, am.assets_name, am.device_id, am.assets_friendly_nm from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where am.status=1 AND am.del_date is null $sub");
		
		return $query->result();
	}
	public function getAutoRefreshSettings(){
		$user_id = $this->session->userdata('user_id');
		
		$SQL="select auto_refresh_setting from tbl_users where user_id = $user_id";
		$query = $this->db->query($SQL);
		return $query->row();
	}
	public function groupAssets(){
		$user_id = $this->session->userdata('user_id');
		$group = $_POST['grp'];
		
		
		if($user_id != 1){
			$SQL = "SELECT am.id, am.assets_name FROM assests_master am, user_assets_map um WHERE find_in_set(assets_group_id, um.group_id) AND um.user_id = $user_id AND am.status = 1 AND am.del_date is null";
		}else{
			$SQL = "SELECT id, assets_name FROM assests_master am WHERE am.status = 1 AND am.del_date is null";
		}
		
		if($group != ""){
			$SQL .= " and am.assets_group_id = $group";
		}
		
		$SQL .= " order by am.assets_name asc";
		
		$query = $this->db->query($SQL);
		return $query->result();
	}
	
	public function get_subTree(){
		
		$group = intval(uri_assoc('group'));
		$area  = intval(uri_assoc('area'));
		$land  = intval(uri_assoc('landmark'));
		$owner = intval(uri_assoc('owner'));
		$divs  = intval(uri_assoc('division'));
		
		if($group > 0) {
			$sql = "SELECT am.id, am.assets_name, (TIME_TO_SEC( TIMEDIFF( NOW(), CONVERT_TZ(lp.add_date,  '+00:00',  '".$this->session->userdata('timezone')."')))) AS diffr FROM assests_master am LEFT JOIN tbl_last_point lp ON lp.device_id = am.device_id WHERE FIND_IN_SET( am.id, (SELECT assets FROM `group_master` WHERE id = $group)) > 0";
		}
		if($area > 0) {
			$sql = "SELECT am.id, am.assets_name, (TIME_TO_SEC( TIMEDIFF( NOW(), CONVERT_TZ(lp.add_date,  '+00:00',  '".$this->session->userdata('timezone')."')))) AS diffr FROM assests_master am LEFT JOIN tbl_last_point lp ON lp.device_id = am.device_id LEFT JOIN areas ar ON ar.polyname = lp.current_area WHERE ar.id = $area";
		}
		if($land > 0) {
			$sql = "SELECT am.id, am.assets_name, (TIME_TO_SEC( TIMEDIFF( NOW(), CONVERT_TZ(lp.add_date,  '+00:00',  '".$this->session->userdata('timezone')."')))) AS diffr FROM assests_master am LEFT JOIN tbl_last_point lp ON lp.device_id = am.device_id LEFT JOIN landmark ld ON ld.name = lp.current_landmark WHERE ld.id = $land";
		}
		if($owner > 0) {
			$sql = "SELECT am.id, am.assets_name, (TIME_TO_SEC( TIMEDIFF( NOW(), CONVERT_TZ(lp.add_date,  '+00:00',  '".$this->session->userdata('timezone')."')))) AS diffr FROM assests_master am LEFT JOIN tbl_last_point lp ON lp.device_id = am.device_id  WHERE am.assets_owner = $owner";
		}
		if($divs > 0) {
			$sql = "SELECT am.id, am.assets_name, (TIME_TO_SEC( TIMEDIFF( NOW(), CONVERT_TZ(lp.add_date,  '+00:00',  '".$this->session->userdata('timezone')."')))) AS diffr FROM assests_master am LEFT JOIN tbl_last_point lp ON lp.device_id = am.device_id  WHERE am.assets_division = $divs";
		}
		
		if($sql != '') {
			$query = $this->db->query($sql);
			return $query->result();
		} else {
			return false;
		}
	}	
	
}
?>