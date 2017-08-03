<?php 
class Device_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Device_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "tbl_track";
		$this->tbl_assets = "assests_master";
		$this->icon_master = "icon_master";
		
    }
	public function get_history() 
	{
		$device_id = uri_assoc('device');
		$id = uri_assoc('id');
		$sub = '';
		if($id != ""){
			$sub = " and id < $id";
		}
		//$query = $this->db->query("SELECT id, speed, lati, longi, CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, address from tbl_track where device_id = $device_id $sub and speed > 0 order by id desc limit 20");
		$sql = "SELECT id, speed, lati, longi, CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, address from tbl_track where assets_id = $device_id $sub and speed > 0 order by id desc limit 20";
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	public function check_assets()
	{
		$id = uri_assoc('device');
		//$query = $this->db->query("SELECT uam.id from user_assets_map uam left join assests_master am on am.add_uid=uam.user_id where find_in_set(am.id, assets_ids) and uam.user_id = '".$this->session->userdata('user_id')."' and uam.del_date is null and uam.status = 1");
		$query = $this->db->query("select uam.id from user_assets_map uam where uam.user_id='".$this->session->userdata('user_id')."' and find_in_set((select id from assests_master where device_id=$id Limit 1),uam.assets_ids)");
		//SELECT uam.id from user_assets_map uam left join assests_master am on am.add_uid=uam.user_id where find_in_set(am.id, assets_ids) and uam.user_id = '298' and uam.del_date is null and uam.status = 1
		//9007
		return $query->result();
	}
	public function getCurrentTrip()
	{
		$id = uri_assoc('id');
		$qry=$this->db->query("select tr.landmark_ids as landmarks, am.assets_name from assests_master am left join tbl_routes tr on am.current_trip=tr.id where am.device_id=$id and am.current_trip is not null");
		$res = $qry->result_array();
		if(count($res)>0){
			$assets_name=$res[0]['assets_name'];
			$arr=explode(',',$res[0]['landmarks']);
			$arr_un=array_unique($arr);
			$landmark_ids=implode(",",$arr_un);
			
			$qry=$this->db->query("SELECT lm.*, '$assets_name' as assets from landmark lm where find_in_set(id,'$landmark_ids')");
			return $qry->result();
		}
		else{
			return;
		}
	}
	public function getDealerLandmark($id){
		$user_nm=$this->session->userdata('username');
		$qy="select concat(lat,':',lng) as latlng from landmark where find_in_set(id,(select landmark_ids from tbl_routes where id=(select current_trip from assests_master where device_id=$id Order by Id desc Limit 1))) and comments='".$user_nm."'";
		$qry=$this->db->query($qy);
		$latlng="";
		$i=0;
		$x=count($qry->result());
		if($x>0){
			foreach($qry->result() as $row){
				if($i>1 && $i!=$x-1){
					$latlng.=";";
				}
				$latlng.=$row->latlng;
				$i++;
			}
		}
		return $latlng;
	}
	public function getDistanceofTrip($id)
	{
		$qry=$this->db->query("select tl.distance_travelled from trip_log tl left join assests_master am on tl.device_id=am.id where am.current_trip=tl.trip_id and am.device_id=$id and tl.distance_travelled is not null ORDER BY tl.id desc LIMIT 1");
		return $qry->result();
		
	}
	
	public function getCurrentTrip_ID()
	{
		$id = uri_assoc('id');
		$qry=$this->db->query("select id, current_trip from assests_master where current_trip is not null and device_id=$id");
		return $qry->result_array();
	}
	public function getCurrentTrip_landmarks()
	{
		$id = uri_assoc('id');
		$qry=$this->db->query("SELECT lm.*, (select group_concat(concat(assets_name,'(',device_id,')')) assets from assests_master where find_in_set(device_id, lm.device_ids)) as assets FROM `landmark` lm where lm. deviceid = $id and lm.del_date is null and lm.status = 1");
		return $qry->result();
	}
	public function get_completed_trip_landmark() {
		
		$id = $_POST['route_id'];
		$query = $this->db->query("SELECT group_concat(landmark_ids) as landmark_ids FROM tbl_sub_routes WHERE del_date IS NULL AND status = 1 and route_id = $id and is_complete = 1");	
		return $query->row();
	}
	public function get_locations() 
	{
		
		$id = uri_assoc('id');
		
		$query = $this->db->query("SELECT lm.id, am.id as assets_id, am.assets_name, am.sim_number, am.driver_name, am.driver_image, am.driver_mobile, am.device_id, CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, TIME_TO_SEC(TIMEDIFF( NOW( ) , lm.add_date)) as beforeTime, lm.address, lm.lati, lm.longi, lm.gsm_leti, lm.gsm_longi, lm.in_batt, lm.ext_batt_volt, lm.angle_dir, lm.speed, lm.ignition, im.icon_path, am.assets_category_id, lm.fuel_percent, lm.temperature from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id LEFT JOIN icon_master im ON im.id = am.icon_id where am.del_date is null and am.status = 1 and lm.device_id = $id limit 1");
/*		
$query = $this->db->query("SELECT tr.id,tr.address, tr.device_id, DATE_FORMAT( tr.add_date,  '%d.%m.%Y %H:%i' ) AS add_date, tr.lati, tr.longi, tr.speed, tr.ignition, (

SELECT assets_name
FROM assests_master
WHERE device_id = $id
LIMIT 1
) AS assets_name, (

SELECT sim_number
FROM assests_master
WHERE device_id = $id
LIMIT 1
) AS sim_number, (

SELECT im.icon_path
FROM assests_master am
LEFT JOIN icon_master im ON im.id = am.icon_id
WHERE am.device_id = $id
LIMIT 1
) AS icon_path
FROM (

SELECT dm.device_id, MAX( dm.id ) AS max_id, am.assets_name
FROM tbl_track dm
LEFT JOIN assests_master am ON am.device_id = dm.device_id
WHERE dm.device_id = $id
) AS x
INNER JOIN tbl_track AS tr ON tr.device_id = x.device_id
AND tr.id = x.max_id", FALSE);
*/
		//echo $this->db->last_query();
		//exit;
		return $query->result();		
	}
	
	public function get_old_locations($id)
	{
		$query = $this->db->query("SELECT lm.id, am.id as assets_id, am.assets_name, am.sim_number, am.driver_name, am.driver_image, am.driver_mobile, am.device_id,  CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, TIME_TO_SEC(TIMEDIFF( NOW( )  , lm.add_date)) as beforeTime, lm.address, lm.lati, lm.longi, lm.in_batt, lm.ext_batt_volt, lm.angle_dir, lm.speed, lm.ignition, im.icon_path, am.assets_category_id, lm.fuel_percent, lm.temperature, lm.gsm_strength from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id LEFT JOIN icon_master im ON im.id = am.icon_id where lm.device_id = $id limit 1");
		return $query->result();
	}
	function vehicle_stop_status(){
		$device = uri_assoc('device');
		$query = $this->db->query("Select stop.ignition_on from tbl_stop_report stop left join assests_master am on am.id = stop.device_id where am.device_id = $device order by stop.id desc limit 1");
		return $query->row();
	}
	public function get_new_locations() 
	{
		//Select table name
		
		$date_time = date("Y-m-d H:i:s", $_POST['date_time']);	
		
		$device = uri_assoc('device');
		/*$this->db->select("tr.id, tr.address, date_format(tr.add_date, '%d.%m.%Y %H:%i') as add_date, tr.device_id, tr.lati, tr.longi, tr.speed, tr.ignition, am.assets_name, am.sim_number", FALSE);
		$this->db->from($this->table_name." tr");
		$this->db->join($this->tbl_assets. " am", "am.device_id = tr.device_id", 'LEFT');
		$this->db->join($this->icon_master. " im", "im.id = am.icon_id", 'LEFT');
		$this->db->where('tr.device_id',$device);
		$this->db->where('tr.lati >', 0);
		$this->db->where('tr.longi >', 0);
		$this->db->where('tr.id >',$id);
		$this->db->order_by('tr.dt', "DESC");
		$this->db->limit(1);
		$query = $this->db->get();
		*/
		$sql = "SELECT lm.id, am.id as assets_id, am.assets_name, am.sim_number, am.driver_name, am.driver_image, am.driver_mobile, am.device_id, CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, TIME_TO_SEC(TIMEDIFF( NOW( )  , lm.add_date)) as beforeTime, lm.address, lm.lati, lm.longi, lm.gsm_leti, lm.gsm_longi, lm.angle_dir, lm.speed, lm.ignition, im.icon_path, lm.fuel_percent, lm.temperature, lm.gsm_strength, lm.in_batt, lm.ext_batt_volt from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id LEFT JOIN icon_master im ON im.id = am.icon_id where lm.device_id = '$device' and CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."') > '$date_time' Limit 1";
		
		$query = $this->db->query($sql);
		
		//echo $this->db->last_query();
		//exit;
		return $query->result();
	}
	
	
	public function get_device_location($device_id) 
	{
/*		
		$this->db->select("tr.id, date_format(tr.add_date, '%d.%m.%Y %H:%i') as add_date, tr.device_id, tr.lati, tr.longi, tr.speed, tr.ignition, am.assets_name, am.sim_number, im.icon_path", FALSE);
//		$this->db->select("id, date_format(add_date, '%d.%m.%Y %H:%i') as add_date, device_id, lati, longi, speed, ignition", FALSE);
		$this->db->from($this->table_name." tr");
		$this->db->join($this->tbl_assets. " am", "am.device_id = tr.device_id", 'LEFT');
		$this->db->join($this->icon_master. " im", "im.id = am.icon_id", 'LEFT');
		$this->db->where('tr.device_id', $device_id);
		$this->db->where('tr.lati > ', 0);
		$this->db->where('tr.longi > ', 0);
		$this->db->order_by("tr.dt", "DESC");
		$this->db->limit(1);
		$query = $this->db->get($this->table_name);
		// echo $this->db->last_query();
		return $query->result();
*/

$query = $this->db->query("SELECT tr.id, tr.address, tr.device_id, DATE_FORMAT( CONVERT_TZ(tr.add_date,'+00:00','".$this->session->userdata('timezone')."') ,  '%d.%m.%Y %H:%i' ) AS add_date, tr.lati, tr.longi, tr.speed, tr.ignition, (

SELECT assets_name
FROM assests_master
WHERE device_id = $device_id
LIMIT 1
) AS assets_name, (

SELECT sim_number
FROM assests_master
WHERE device_id = $device_id
LIMIT 1
) AS sim_number, (

SELECT im.icon_path
FROM assests_master am
LEFT JOIN icon_master im ON im.id = am.icon_id
WHERE am.device_id = $device_id
LIMIT 1
) AS icon_path
FROM (

SELECT dm.device_id, MAX( dm.id ) AS max_id, am.assets_name
FROM tbl_track dm
LEFT JOIN assests_master am ON am.device_id = dm.device_id
WHERE dm.device_id = $device_id
) AS x
INNER JOIN tbl_track AS tr ON tr.device_id = x.device_id
AND tr.id = x.max_id", FALSE);

		//echo $this->db->last_query();
		//exit;
		return $query->result();
	}
	
	public function add_poly() 
	{
		$nm = $_POST['name'];
		$latAdd = $_POST['latAdd'];
		$lngAdd = $_POST['lngAdd'];
		$group_id = $_POST['group'];
		$color = $_POST['color'];
		$area_size = $_POST['area_size'];
		if($_POST['group'] != '') {
			//$assets_sql =  "SELECT GROUP_CONCAT(id) as devices FROM assests_master WHERE assets_group_id IN (" . $_POST['group'] .")";
			$assets_sql =  "SELECT GROUP_CONCAT(id) as devices FROM assests_master WHERE id IN (" . $_POST['group'] .")";
			$assets_res = $this->db->query($assets_sql);
			$rows = $assets_res->result();
			$row = $rows[0];
			$_POST['device'] = $row->devices;
		}
				
		$device = $_POST['device'];
		$in_alert = ($_POST['in_alert'] == 'true') ? 1 : 0;
		$out_alert = ($_POST['out_alert'] == 'true') ? 1 : 0;
		$sms_alert = ($_POST['sms_alert'] == 'true') ? 1 : 0;
		$email_alert = ($_POST['email_alert'] == 'true') ? 1 : 0;
		$addressbook_ids = $_POST['addressbook_ids'];
		$area_type_opt = $_POST['area_type_opt'];
		if($addressbook_ids != ""){
			$addressbook_ids = implode(",", $addressbook_ids);
		}else{
			$addressbook_ids = '';
		}
		if($nm == "" || count($latAdd) == 0 || count($lngAdd) == 0){
			//die("Missing parameter");
			$this->output->set_output("Missing parameter");
		}
		
		$this->db->select("MAX( polyid )+1 as PolyID", FALSE);
		$query = $this->db->get('areas');
		$row = $query->result();
		
		if($row[0]->PolyID!=null){
			$polyid = $row[0]->PolyID;
		}else{
			$polyid = 1;
		}
		
		
		$datetime = gmdate("Y-m-d H:i:s");
		for($i=0;$i<count($latAdd);$i++)
		{
			$j = $i + 1;
			//$data = array('polyid' => $polyid, 'group_id' => $group_id, 'area_size'=>$area_size, 'color' => $color, 'deviceid' => $device, 'polyname' => $nm, 'lat' => round($latAdd[$i],6), 'lng' => round($lngAdd[$i],6), 'pointid' => $j, 'Audit_Enter_Dt' => $datetime, 'Audit_Enter_uid' => $this->session->userdata('user_id'), 'Audit_Status' => 1, 'in_alert'=>$in_alert, 'out_alert'=>$out_alert, 'sms_alert' => $sms_alert, 'email_alert' => $email_alert, 'speed_alert'=>1, 'addressbook_ids'=>$addressbook_ids, 'area_type_opt'=>$area_type_opt);
			
			$data = array('polyid' => $polyid, 'area_size'=>$area_size, 'color' => $color, 'deviceid' => $device, 'polyname' => $nm, 'lat' => round($latAdd[$i],6), 'lng' => round($lngAdd[$i],6), 'pointid' => $j, 'Audit_Enter_Dt' => $datetime, 'Audit_Enter_uid' => $this->session->userdata('user_id'), 'Audit_Status' => 1, 'in_alert'=>$in_alert, 'out_alert'=>$out_alert, 'sms_alert' => $sms_alert, 'email_alert' => $email_alert, 'speed_alert'=>1, 'addressbook_ids'=>$addressbook_ids, 'area_type_opt'=>$area_type_opt);
			
			$query = $this->db->insert_string('areas', $data);
			//echo $query;
			$this->db->query($query);
			//return $this->db->last_query();			
		}
		return;
	}
	
	public function get_links() 
	{
		$user = $this->session->userdata('user_id');
		$this->db->select("id, assets_name, device_id", FALSE);
		$this->db->where('find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = '.$user.'))');
		$this->db->where('status',1);
		$this->db->where('del_date',null);
		//$this->db->where('user_id', $this->session->userdata('user_id'));
		$query = $this->db->get($this->tbl_assets);
		//echo $this->db->last_query();
		return $query->result();
	}
	
	public function get_poly() 
	{
		$this->db->select("*", FALSE);
		$this->db->where('deviceid', uri_assoc('id'));
		$query = $this->db->get('areas');
		$this->db->order_by("id", "asc"); 
		//echo $this->db->last_query();
		return $query->result();
	}
	
	public function get_poly_home() 
	{
		/*$this->db->select("*", FALSE);
		$this->db->where('Audit_Enter_uid', $this->session->userdata('user_id'));
		$this->db->order_by("id", "asc"); 
		$query = $this->db->get('areas');
		//echo $this->db->last_query();
		return $query->result();
		*/
	
		$query = $this->db->query("select area.*, (select group_concat(concat(assets_name,'(',device_id,')')) assets from assests_master where find_in_set(id, area.deviceid)) as assets from areas area where  Audit_Enter_uid = ".$this->session->userdata('user_id')." and Audit_Status = 1 and Audit_Del_Dt is null");
		return $query->result();
	}
	public function get_poly_zone() 
	{
		$query = $this->db->query("select area.*, (select group_concat(concat(assets_name,'(',device_id,')')) assets from assests_master where find_in_set(id, area.deviceid)) as assets from landmark_areas area where  Audit_Enter_uid = ".$this->session->userdata('user_id')." and Audit_Status = 1 and Audit_Del_Dt is null");
		return $query->result();
	}
	public function delete_poly() 
	{
		$id = $_POST['id'];
		//$this->db->delete('areas', array('polyid' => $id)); 
		$res = $this->db->query("update areas set Audit_Del_Dt = '".gmdate('Y-m-d H:i:s')."', Audit_Status = 0 where polyid=$id");
		return;
	}
	
	
	//last location all device
	public function get_all_last_location($user) 
	{
		
		if(isset($_POST['group']) && $_POST['group'] != ""){
			$group = $_POST['group'];
			$this->db->select("*", FALSE);
			$this->db->where('id', $group);
			$query = $this->db->get('group_master');			
			$rows = $query->result();
			foreach ($rows as $row) {
				$assets = $row->assets;
			}
			$sub = "am.id in($assets)";
		}
		else{
			//$sub = "am.user_id = $user";
			$sub = "find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = $user))";
		}
		$query = $this->db->query("SELECT tr.id, tr.address, tr.device_id, DATE_FORMAT( CONVERT_TZ(tr.add_date,'+00:00','".$this->session->userdata('timezone')."'),  '%d.%m.%Y %H:%i' ) AS add_date, tr.lati, tr.longi, tr.speed, tr.ignition, (

SELECT assets_name
FROM assests_master
WHERE device_id = tr.device_id
LIMIT 1
) AS assets_name, (

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
AND tr.id = x.max_id", FALSE);

		//echo $this->db->last_query();
		//exit;
		return $query->result();
	}
	
	public function get_group() 
	{
		$this->db->select("*", FALSE);
		$this->db->where('add_uid', $this->session->userdata('user_id'));
		$query = $this->db->get('group_master');
		//echo $this->db->last_query();
		return $query->result();
	}
	
	public function get_group_device($devices) 
	{
		$query = $this->db->query("select assets_name, device_id from assests_master where id in($devices)");
		//echo $this->db->last_query();
		//exit;
		return $query->result();
	}
	
	public function get_group_location($user) 
	{
		$group = $_POST['group'];
		if($group != ""){
			$this->db->select("*", FALSE);
			$this->db->where('id', $group);
			$query = $this->db->get('group_master');			
			$rows = $query->result();
			foreach ($rows as $row) {
				$assets = $row->assets;
			}
			$sub = "am.id in($assets)";
		}
		else{
			//$sub = "am.user_id = $user";
			$sub = "find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = $user))";
		}
		$query = $this->db->query("SELECT tr.id, tr.device_id, DATE_FORMAT( CONVERT_TZ(tr.add_date,'+00:00','".$this->session->userdata('timezone')."'),  '%d.%m.%Y %H:%i' ) AS add_date, tr.lati, tr.longi, tr.speed, tr.ignition, (

	SELECT assets_name
	FROM assests_master
	WHERE device_id = tr.device_id
	LIMIT 1
	) AS assets_name, (

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
	AND tr.id = x.max_id", FALSE);
		
		//echo $this->db->last_query();
		//exit;
		return $query->result();
	}
	function get_track_id($device,$dt){
		$query = $this->db->query("select id from tbl_track where CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."')='$dt' and device_id=$device Order by id Desc Limit 1");
		//echo $this->db->last_query();
		//exit;
		return $query->result();
	}
	function insertIntoInspection(){
		$trackId = uri_assoc('id');
		$query = $this->db->query("INSERT `tbl_inspection_track` (`id`, `assets_id`, `lati`, `longi`, `phone_imei`, `add_date`, `speed`, `url_id`, `device_id`, `gps`, `dt`, `tm`, `ignition`, `box_open`, `altitude`, `direction`, `gsm_strength`, `angle_dir`, `power_st`, `acc_st`, `reserved`, `mileage`, `address`, `msg_serial_no`, `reason`, `reason_text`, `command_key`, `command_key_value`, `msg_key`, `odometer`, `sat_mode`, `gsm_register`, `gprs_register`, `server_avail`, `in_batt`, `ext_batt_volt`, `digital_io`, `analog_in_1`, `analog_in_2`, `analog_in_3`, `analog_in_4`, `rfid`, `fuel_percent`, `temperature`) SELECT `id`, `assets_id`, `lati`, `longi`, `phone_imei`, `add_date`, `speed`, `url_id`, `device_id`, `gps`, `dt`, `tm`, `ignition`, `box_open`, `altitude`, `direction`, `gsm_strength`, `angle_dir`, `power_st`, `acc_st`, `reserved`, `mileage`, `address`, `msg_serial_no`, `reason`, `reason_text`, `command_key`, `command_key_value`, `msg_key`, `odometer`, `sat_mode`, `gsm_register`, `gprs_register`, `server_avail`, `in_batt`, `ext_batt_volt`, `digital_io`, `analog_in_1`, `analog_in_2`, `analog_in_3`, `analog_in_4`, `rfid`, `fuel_percent`, `temperature` FROM tbl_track WHERE id=$trackId");
		return true;
	}
	function insertIntoWaypoints($wpNm,$L1,$L2,$Wp){
		$success=true;
		$user=$this->session->userdata("user_id");
		$dt=gmdate("Y-m-d H:i:s");
		$query = $this->db->query("SELECT * FROM tbl_landmarks_waypoints WHERE (landmark1=$L1 or landmark1=$L2) and (landmark2=$L2 or landmark2=$L1) AND waypoint='$Wp' AND add_uid=$user");
		if(count($query->result())==0){
			$query = $this->db->query("INSERT INTO tbl_landmarks_waypoints (waypoint_name,landmark1,landmark2,waypoint,add_uid,add_date,status) VALUES ('$wpNm',$L1,$L2,'$Wp',$user,'$dt',1)");
			return $success;
		}else{
			return false;
		}
	}
	function getListofLandmarks(){
		$user=$this->session->userdata('user_id');
		$query = $this->db->query("Select id, name, icon_path from landmark where add_uid=$user");
		return $query->result();
	}
	function getLatLngTrack($id){
		if($id!=""){
			$query = $this->db->query("Select concat(lati,',',longi) as latlng from tbl_track where id=$id");
			if(count($query->result())==1){
				$rs=$query->result_array();
				return $rs[0]['latlng'];
			}else{
				return false;
			}
		}
		return false;
	}
}