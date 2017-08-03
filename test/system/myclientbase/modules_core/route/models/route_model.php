<?php 
class Route_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Route_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "tbl_track";
		$this->tbl_assets = "assests_master";
		$this->icon_master = "icon_master";
		
    }
	public function get_devices($user) 
	{
		//$query = $this->db->query("select assets_name, device_id from assests_master where user_id = $user");
		$query = $this->db->query("select id, assets_name, device_id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user))");
		return $query->result();
	}	
	public function last_location($user) 
	{
		$page = $_POST['page'];
		$limit = $_POST['limit'];
		$report = $_POST['report'];
		
		$rptsub = substr($report, 0, 2);
		
		$stsWhr = "";
		$group = "";
		if($rptsub == "g-"){
			$group = str_replace($rptsub, "", $report);
		}
		else if($rptsub == "u-"){
			$user = str_replace($rptsub, "", $report);
		}
		elseif($report == "running"){
			$stsWhr = " AND (TIME_TO_SEC(TIMEDIFF(CONVERT_TZ( NOW( ) , '+00:00', '+05:30' ) , lm.add_date)) <= 600 and lm.speed > 0)";
		}
		elseif($report == "idle"){
			$stsWhr = " AND TIME_TO_SEC(TIMEDIFF(CONVERT_TZ( NOW( ) , '+00:00', '+05:30' ) , lm.add_date)) <= 600 and lm.speed = 0 AND lm.ignition = 1";
		}
		elseif($report == "parked"){
			$stsWhr = " AND TIME_TO_SEC(TIMEDIFF(CONVERT_TZ( NOW( ) , '+00:00', '+05:30' ) , lm.add_date)) <= 600 and lm.speed = 0  AND lm.ignition = 0";
		}
		elseif($report == "out_of_network"){
			$stsWhr = " AND TIME_TO_SEC(TIMEDIFF(CONVERT_TZ( NOW( ) , '+00:00', '+05:30' ) , lm.add_date)) between 601 and 86399";
		}
		elseif($report == "device_fault"){
			$stsWhr = " AND TIME_TO_SEC(TIMEDIFF(CONVERT_TZ( NOW( ) , '+00:00', '+05:30' ) , lm.add_date)) >= 86400";
		}
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
			$sub = "find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = $user))";
		}
		
		$srch = "";
		if(isset($_POST['txt']) && $_POST['txt'] != ""){
			$txt = $_POST['txt'];
			$srch = " AND am.assets_name LIKE ('%".$txt."%')";
		}
		
		
		//$this->db->select('count(id) as record_count');
		
		//$query = $this->db->query("SELECT tr.id FROM (SELECT dm.device_id, MAX( dm.id ) AS max_id, am.assets_name FROM tbl_track dm LEFT JOIN assests_master am ON am.device_id = dm.device_id WHERE $sub GROUP BY dm.device_id ) AS x INNER JOIN tbl_track AS tr ON tr.device_id = x.device_id AND tr.id = x.max_id $srch $speedWhr order by tr.add_date desc", FALSE);
		
		$query = $this->db->query("SELECT am.id from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id where $sub $srch $stsWhr", FALSE);
		
		$totaldata = $query->num_rows();
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
		
		$query = $this->db->query("SELECT am.id as assets_id, am.assets_name, am.driver_name, am.driver_image, am.device_id, lm.add_date, TIME_TO_SEC(TIMEDIFF(CONVERT_TZ( NOW( ) , '+00:00', '+05:30' ) , lm.add_date)) as beforeTime, lm.address, lm.lati, lm.longi, lm.speed, lm.ignition, im.icon_path from assests_master am left join tbl_last_point lm on lm.device_id = am.device_id LEFT JOIN icon_master im ON im.id = am.icon_id where $sub $srch $stsWhr order by lm.add_date desc LIMIT $start, $limit", FALSE);
		
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
		$query = $this->db->query("SELECT lm.*, (select group_concat(concat(assets_name,'(',device_id,')')) assets from assests_master where find_in_set(device_id, lm.device_ids)) as assets FROM `landmark` lm where lm.add_uid = $user", FALSE);
		return $query->result();
	}
	public function removeLandmark(){
		$id = $_POST['id'];
		$this->db->delete('landmark', array('id' => $id)); 
		return;
	}
	public function get_todays_points($device){
		$query = $this->db->query("select lati, longi from ".$this->table_name." where date(add_date) = '" . date('Y-m-d') . "' and device_id = $device");
		return $query->result();
	}
	
	public function get_group($user) 
	{
		$query = $this->db->query("select id, group_name from group_master where add_uid = $user");
		return $query->result();
		
	}
	
	public function get_subuser($user) 
	{
		$query = $this->db->query("select * from tbl_users where admin_id = $user");
		return $query->result();
		
	}
	public function device_map($user) 
	{
		$device_ids = uri_assoc('id');
		
		$sub = "dm.device_id in(SELECT device_id FROM assests_master where id in($device_ids))";
		
		$query = $this->db->query("SELECT tr.id, tr.device_id, DATE_FORMAT( tr.add_date,  '%d.%m.%Y %H:%i' ) AS add_date, tr.lati, tr.longi, tr.speed, tr.ignition, (

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
	function addUser() {

		$success = 'true';
		
		$user = $this->session->userdata('user_id');
		$assets_ids = $_POST['assets_ids'];
		unset($_POST['assets_ids']);
		$_POST['from_date'] = date('Y-m-d H:i:s', strtotime($_POST['from_date']));
		$_POST['to_date'] = date('Y-m-d H:i:s', strtotime($_POST['to_date']));
		$_POST['admin_id'] = $user;
		$_POST['usertype_id'] = 3;
		$_POST['add_date'] = date('Y-m-d H:i:s');
		$_POST['password'] = md5($_POST['password']);
		$this->db->insert("tbl_users", $_POST);
		$insert_id = $this->db->insert_id();
		
		$this->db->query('insert into user_assets_map (user_id, assets_ids, add_date, add_uid, status) values("'.$insert_id.'", "'.$assets_ids.'", "'.date('Y-m-d H:i:s').'", '.$user.', 1)');
		
		$msg = "User Created Successfully";
		
		$data = array();
		$data['result'] = $success;
		$data['insert_id'] = $insert_id;
		$data['msg'] = $msg;
		return $data;

	}
	function addLandmark() {

		$success = 'true';
		
		$user = $this->session->userdata('user_id');
		$pData['name'] = $_POST['name'];
		$pData['address'] = $_POST['address'];
		$pData['radius'] = $_POST['radius'];
		$pData['device_ids'] = $_POST['device'];
		$pData['icon_path'] = $_POST['icon'];
		$pData['lat'] = $_POST['lat'];
		$pData['lng'] = $_POST['lng'];
		$pData['add_date'] = date('Y-m-d H:i:s');
		$pData['add_uid'] = $user;
		$pData['status'] = 1;
		
		$this->db->insert("landmark", $pData);
		$insert_id = $this->db->insert_id();
		
		$msg = "Landmark Created Successfully";
		
		$data = array();
		$data['result'] = $success;
		$data['insert_id'] = $insert_id;
		$data['msg'] = $msg;
		return $data;

	}
	function addToGroup() {

		$success = 'true';
		
		$user = $this->session->userdata('user_id');
		$assets_ids = uri_assoc('assets');
		$group = uri_assoc('group');
		$group = str_replace("g-","",$group);
		$newgp = uri_assoc('newgp');
		if($group == "new"){
			$gArr['group_name'] = $newgp;
			$gArr['assets'] = $assets_ids;
			$gArr['add_uid'] = $user;
			$gArr['add_date'] = date('Y-m-d H:i:s');
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
		$query = $this->db->query("select am.*, im.icon_path from assests_master am left join icon_master im on im.id = am.icon_id where am.device_id = $device_ids");
		return $query->row();
	}
	public function current_location()
	{
		$device_id = uri_assoc('id');
				
		$query = $this->db->query("SELECT tr.id, tr.device_id, DATE_FORMAT( tr.add_date,  '%d.%m.%Y %H:%i' ) AS add_date, tr.lati, tr.longi, tr.speed, tr.ignition, am.assets_name, am.sim_number, tr.address from tbl_track tr left join assests_master am on am.device_id = tr.device_id left join icon_master im ON im.id = am.icon_id
WHERE am.device_id = $device_id order by tr.add_date desc limit 1", FALSE);

		//echo $this->db->last_query();
		//exit;
		return $query->row();
	}
	public function distance_today(){
		$device_id = uri_assoc('id');
		$query = $this->db->query("select distance from distance_master where add_date = '".date('Y-m-d')."' and assets_id = $device_id");
		return $query->row();
	}
	
	public function current_speed(){
		$device_id = uri_assoc('id');
		$query = $this->db->query("select lp.speed from tbl_last_point lp left join assests_master am on am.device_id = lp.device_id where am.id = $device_id limit 1");
		return $query->row();
	}
	
}
?>