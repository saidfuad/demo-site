<?php 
class Asset_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Asset_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_assets = "assests_master";
		$this->icon_master = "icon_master";
    }
	function getAllData($cmd){
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
			
		$SQL = "SELECT * FROM assests_master WHERE status=1";
		if($this->session->userdata('usertype_id')!=1){
			$SQL .= " AND  find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = ".$user."))";
		}
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
			
		$SQL = "SELECT am.id, am.assets_name, am.device_id, am.assets_friendly_nm, am.device_desc, im.icon_path, cm.assets_cat_name as assets_category, tm.assets_type_nm as assets_type, ad.division as assets_division, ao.owner as assets_owner, am.sim_number, am.assets_image_path, am.driver_name, am.driver_image, am.driver_mobile, am.max_speed_limit, am.max_fuel_capacity , am.max_fuel_liters,am.sensor_type, gm.group_name FROM assests_master am left join icon_master im on im.id = am.icon_id left join group_master gm on gm.id = am.assets_group_id left join assests_category_master as cm on  am.assets_category_id=cm.id left join assests_type_master as tm on tm.id=am.assets_type_id left join assests_division_master ad ON ad.id = am.assets_division left join assests_owner_master ao ON ao.id = am.assets_owner WHERE am.status=1 ";
		if($this->session->userdata('usertype_id')!=1){
			$SQL .= " AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = ".$user."))";
		}
		if($where != "")
			$SQL .= " AND $where";
		$export_sql = $SQL;
		
		if($cmd=="export")   
		{
			$result = $this->db->query($export_sql);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=Assets.xls"); 
			$EXCEL = ""; 
			$fitr="";
			
			//session date & time format 
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			$fitr .="<tr>";
			$fitr.="<th>Assets Name</th>";
			$fitr.="<th>Driver Name</th>";
			$fitr.="<th>Driver Mobile</th>";
			$fitr.="<th>Max Speed Limit</th>";
			$fitr.="<th>Device Id</th>";
			$fitr.="<th>Assets Division</th>";
			$fitr.="<th>Assets Owner</th>";
			$fitr.="<th>Sim No</th>";
			$fitr .="</tr>"; 
			//var_dump($result);
			foreach($result->result_array() as $data)
				{
					
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data['assets_name']." </td>"; 
					$EXCEL.="<td>".$data['driver_name']."</td>";
					$EXCEL.="<td>".$data['driver_mobile']."</td>";
					$EXCEL.="<td>".$data['max_speed_limit']."</td>";
					$EXCEL.="<td>".$data['device_id']."</td>";
					$EXCEL.="<td>".$data['assets_division']."</td>";
					$EXCEL.="<td>".$data['assets_owner']."</td>";
					$EXCEL.="<td>".$data['sim_number']."</td>";
					$EXCEL .="</tr>";
				}
			if($this->session->userdata('id')==1)
				$count=3;
			else
				$count=2;
			
			echo "<table border='1'>";
			
			echo "<tr><th colspan='8'>Assets</th></tr>";
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
	public function delete_assets() 
	{
		$ids = $_POST["id"];
		//$dt=date("Y-m-d H:i:s");
		$dt=gmdate('Y-m-d H:i:s');
		$delete_assets = $this->db->query("UPDATE assests_master SET del_uid=".$this->session->userdata('user_id').", del_date='".$dt."', status=0 WHERE id in(".$ids.")");
		
		
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
		$this->db->select('am.id, am.assets_name, am.device_id, im.icon_path, am.sim_number')->from($this->tbl_assets." am");
		$this->db->join($this->icon_master. " im", "im.id = am.icon_id", 'LEFT');
		//$this->db->where('am.user_id', $this->session->userdata('user_id'));
		$this->db->where('find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = '.$user.'))');
		$this->db->order_by("am.id", "DESC");
		
		$this->CI->flexigrid->build_query();
		
		//Get contents
		$return['records'] = $this->db->get();
		
		//Build count query
		$this->db->select('count(id) as record_count')->from($this->tbl_assets);
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
		$this->db->insert("assests_master", $db_array);
		$insert_id = $this->db->insert_id();

		$sql = "SELECT * FROM `user_assets_map` where user_id = $user";
		
		$query = $this->db->query($sql);
		
		foreach ($query->result() as $row) {
			if($row->assets_ids != "") {
				$temp_as = explode(',', $row->assets_ids);
				if(! in_array($insert_id, $temp_as)) {
					$upd = 'UPDATE user_assets_map SET assets_ids = concat(assets_ids, ",'.$insert_id.'") WHERE id='.$row->id;
					$this->db->query($upd);
				}
			}else{
				$upd = 'UPDATE user_assets_map SET assets_ids = '.$insert_id.' WHERE id='.$row->id;
				$this->db->query($upd);
			}
		}
		
/*
		if($db_array['last_group'] == '') {
			$db_array['last_group'] = $db_array['assets_group_id'];
			$sql = "SELECT * FROM `user_assets_map` where find_in_set (".$db_array['last_group'].", group_id)";
			
			$query = $this->db->query($sql);
			
			while($row = $query->row()) {
				
				if($row->assets_ids != "") {
					$temp_as = explode(',', $row->assets_ids);
					if(! in_array($insert_id, $temp_as)) {
						$this->db->query('UPDATE user_assets_map SET assets_ids = concat(assets_ids, ",'.$insert_id.'") WHERE id='.$row->id);
					}
				}else{
					$this->db->query('UPDATE user_assets_map SET assets_ids = '.$insert_id.' WHERE id='.$row->id);
				}
			}
		}
		else if($db_array['last_group'] != '' && $db_array['assets_group_id'] != '' && $db_array['last_group'] != $db_array['assets_group_id']) {
			
			$sql = "SELECT * FROM `user_assets_map` WHERE find_in_set (".$db_array['last_group'].", group_id)";
			
			$query = $this->db->query($sql);
			
			while($row = $query->row()) {
				if($row->group_id != '') {
					$temp_grp = explode(',', $row->group_id);
					if(!in_array($db_array['assets_group_id'], $temp_grp)) {
						if($row->assets_ids != "") {
							$this->db->query('UPDATE user_assets_map SET assets_ids = concat(assets_ids, ",'.$insert_id.'") WHERE id='.$row->id);
						}
						else {
							$this->db->query('UPDATE user_assets_map SET assets_ids = '.$insert_id.' WHERE id='.$row->id);
						}
					}
				}
			}
		}
*/
		return $success;

	}
	public function prepare_icon() 
	{		
		$this->db->select('*');
		$this->order_by = 'id';
		$query = $this->db->get('icon_master');
		return $query->result();
	}	
	public function getIconPath($id) 
	{
		$this->db->select('*');
		$this->db->where('id',$id);
		$this->order_by = 'id';
		$query = $this->db->get('icon_master');
		return $query->result();
	}
	public function checkDupli($device,$id){
		$user=$this->session->userdata("user_id");
		$qry="SELECT am.assets_name, concat(um.username,'(',um.first_name,' ',um.last_name,')') as username from assests_master am left join user_assets_map uam on find_in_set(am.id,uam.assets_ids) left join tbl_users um on uam.user_id=um.user_id where am.device_id=$device and am.del_date is null and um.del_date is null";
		if($id!=""){
			$qry.=" And am.id!=$id";
		}
		$query = $this->db->query($qry);
		return $query->result();
	}
	public function prepare_assets_type()
	{
		$this->db->select('*');
		$this->db->where("status",1);
		$this->order_by = 'id';
		$query = $this->db->get('assests_type_master');
		return $query->result();
	}
	public function prepare_battery_combo()
	{
		$this->db->select('*');
		$this->order_by = 'size';
		$query = $this->db->get('tbl_ext_battery');
		return $query->result();
	}
	public function prepare_telecom_provider()
	{
		$this->db->select('*');
		$this->order_by = 'size';
		$query = $this->db->get('telecom_provider');
		return $query->result();
	}
	//////////////////////
	public function get_new_locations() 
	{
		//Select table name
		$id = $_POST['id'];
		$device = uri_assoc('id');
		$query = $this->db->select()->from($this->table_name)->where('phone_imei',$device)->where('id >',$id)->order_by('id');
		return $query->result();
	}
	public function get_links() 
	{
		//Select table name
		$this->db->select('phone_imei');
		$this->db->distinct();
		$query = $this->db->get($this->table_name);
		// echo $this->db->last_query();
		return $query->result();
	}

	public function get_cat(){
		$id=uri_assoc('id');
		$query="select id,assets_cat_name from `assests_category_master` where 	assets_type_id='$id' AND  status= '1' AND del_uid is Null AND del_date is Null";
		$data = $this->db->query($query);

		//echo "<option value='' >Select Type</option>";
		$cateOpt="";
		
		foreach ($data->result() as $row)
		{
		$cateOpt .= '<option value="'.$row->id.'"';
			if($row->id == $this->form_model->assets_type_id)
				$cateOpt .= ' selected="selected"';
			$cateOpt .= '>'.$row->assets_cat_name.'</option>';
		}
		
		return $cateOpt;		
	}
	public function assets_group_data(){

		if($this->session->userdata('user_id') != 1) {
			$query = "SELECT gm.id, gm.group_name FROM group_master gm, user_assets_map um WHERE find_in_set(gm.id, um.group_id ) AND um.user_id = ".$this->session->userdata('user_id')." AND gm.status = 1 ORDER BY gm.group_name";
		}
		else {
			$query = "SELECT gm.id, gm.group_name FROM group_master gm WHERE gm.status = 1 ORDER BY gm.group_name";
		}
	
//		$query = "SELECT id, group_name from `group_master` where status= '1' AND del_uid is Null AND del_date is Null AND add_uid = ".$this->session->userdata('user_id');
		$data = $this->db->query($query);
/*
		//echo "<option value='' >Select Type</option>";
		$cateOpt="";
		
		foreach ($data->result() as $row)
		{
		$cateOpt .= '<option value="'.$row->id.'"';
		$cateOpt .= '>'.$row->group_name.'</option>';	
		}
*/		
		return $data->result();
	}
	public function get_cat_post(){
		$id=uri_assoc('id');
		$query="select id,assets_cat_name from `assests_category_master` where id='$id' AND  status= '1' AND del_uid is Null AND del_date is Null";
		$data = $this->db->query($query);

	//	echo "<option value='' >Select Category</option>";
		$cateOpt="";
		
		foreach ($data->result() as $row)
		{
		$cateOpt .= '<option value="'.$row->id.'"';
			if($row->id == $this->form_model->assets_type_id)
				$cateOpt .= ' selected="selected"';
			$cateOpt .= '>'.$row->assets_cat_name.'</option>';
		}
		
		return $cateOpt;		
	}
	

	public function prepare_owner_combo(){

		$query = "select id, owner from `assests_owner_master` where status= '1' AND del_date is Null";
		$data  = $this->db->query($query);

	//	echo "<option value='' >Select Category</option>";
		$ownerOpt="";
		
		foreach ($data->result() as $row)
		{
		$ownerOpt .= '<option value="'.$row->id.'"';
			if($row->id == $this->form_model->assets_owner)
				$ownerOpt .= ' selected="selected"';
			$ownerOpt .= '>'.$row->owner.'</option>';
		}

		return $ownerOpt;		
	}
	
	public function prepare_division_combo(){

//		$divisionOpt = "<option value='' >Select Category</option>";

		$query = "select id, division from `assests_division_master` where status= '1' AND del_date is Null";
		$data  = $this->db->query($query);
		
		foreach ($data->result() as $row)
		{
		$divisionOpt .= '<option value="'.$row->id.'"';
			if($row->id == $this->form_model->assets_division)
				$divisionOpt .= ' selected="selected"';
			$divisionOpt .= '>'.$row->division.'</option>';
		}
		
		return $divisionOpt;		
	}

	public function map_assets() {
		$groups = array();
		$as_sql = "SELECT assets_group_id as groups, group_concat(id) as ids FROM `assests_master` group by groups order by groups";
		
		$grp_res = $this->db->query($as_sql);

		foreach ($grp_res->result() as $row) {
			$groups[$row->groups] = $row->ids;
		}

		$sql = "SELECT id, group_id FROM `user_assets_map` where 1";

		$query = $this->db->query($sql);

		foreach($query->result() as $row) {
			if($row->group_id != '') {
				$us_group = explode(',', $row->group_id);
				$temp_values = array();
				foreach($us_group as $grp) {
					$temp_values[] = $groups[$grp];
				}
				if(count($temp_values)){
					$new_assets = implode(',',$temp_values);
					$sql = 'UPDATE user_assets_map SET assets_ids = "'.$new_assets.'" WHERE id='.$row->id;
					$this->db->query($sql);
				}
			}
		}
	}
	
	public function prepare_assets_cat()
	{
	}
}