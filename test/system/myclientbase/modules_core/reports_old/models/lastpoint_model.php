<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Eye View Design CMS module Ajax Model
 *
 * PHP version 5
 *
 * @category  CodeIgniter
 * @package   EVD CMS
 * @author    Frederico Carvalho
 * @copyright 2008 Mentes 100Limites
 * @version   0.1
*/

class Lastpoint_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function lastpoint_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->library('session');
		$this->load->database();
		$this->table_track = "tbl_track";
		$this->tbl_assets = "assests_master";
    }
	
	public function get_lastpoints($cmd) 
	{
		$page = isset($_REQUEST["page"])?$_REQUEST["page"]:1; 
		
		$limit = isset($_REQUEST["rows"])? $_REQUEST["rows"]:10; 
		$sidx = isset($_REQUEST['sidx'])?$_REQUEST['sidx']:'id'; 
		$sord = isset($_REQUEST['sord'])?$_REQUEST['sord']:'';
		$where = ""; 
		$searchField = isset($_REQUEST['searchField']) ? $_REQUEST['searchField'] : false;
		$searchOper = isset($_REQUEST['searchOper']) ? $_REQUEST['searchOper']: false;
		$searchString = isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : false;

		if (isset($_REQUEST['_search']) && $_REQUEST['_search'] == 'true') {
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
	
		$reports = $_REQUEST['report'];
	
		$srch = "";
		$stsWhr = "";
		$group = "";
		$sub="";
		$user = $this->session->userdata('user_id');
		
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
			if($rptsub == "z-"){
				$us_zr = str_replace($rptsub, "", $report);
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
				$gsub .= " AND tlp.current_area = '".addslashes($us_area)."'";
		}
		
		if($us_zr != ""){
			$this->db->select("polyname", FALSE);
			$this->db->where('polyid', $us_zr);
			$this->db->limit(1);
			$query = $this->db->get('landmark_areas');			
			$rows = $query->result();
			$this->db->last_query();
			$us_zone = '';
			foreach ($rows as $key => $row) {
				$us_zone = $row->polyname;
			}

			if($us_zone!="")
				$gsub .= " AND tlp.current_zone = '".addslashes($us_zone)."'";
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
				$gsub .= " AND tlp.current_landmark = '".addslashes($us_land)."'";
		}
		
		if($group != ""){
			$gsub .= " AND am.assets_group_id = $group";
			/*
			$this->db->select("*", FALSE);
			$this->db->where('id', $group);
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
		
		if($us_ow != '') {
			$gsub .= " AND am.assets_owner = '".mysql_real_escape_string($us_ow)."'";
		}
		
		if($us_dv != '') {
			$gsub .= " AND am.assets_division = '".mysql_real_escape_string($us_dv)."'";
		}
		
		if($user != ""){
			if($user!=1){
				$usub = " AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id =".$user."))";
			}else{
				$usub = " AND 1=1";
			}
		}

		if(isset($_REQUEST['txt']) && $_REQUEST['txt'] != ""){
			$txt = $_REQUEST['txt'];
			$srch = " AND am.assets_name LIKE ('%".$txt."%')";
		}

		if(!$sidx) 
			$sidx = 1;
	
		$this->db->select('device_id');

		$this->db->where('find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = '.$user.'))');
		$query = $this->db->get("assests_master");
		$dids=array();
		foreach($query->result() as $row) {
			$dids[] = $row->device_id;	
		}
	
		if(count($dids) > 0)
			$dids = implode(",", $dids);
		else
			$dids = 0;
			$andclause="";
		if($sub!="" || $srch!="" || $stsWhr!="")
			$andclause="and";
		
	//	echo $sub."<br/>".$srch."<br/>".$stsWhr;
		//die();
		$SQL = "select count(am.id) as total from assests_master am left join tbl_last_point tlp on tlp.device_id = am.device_id where am.status=1 AND am.del_date is null $gsub $usub $srch $stsWhr ";
		
		
		if($where != "")
			$SQL .= " AND $where";
		//echo $SQL;	
		$result = $this->db->query($SQL);
		$data_arr=$result->result_array();
		
		$count = $data_arr[0]['total'];
		//$totaldata = $query->num_rows();
		
		if($limit == 999){
			$limit = $count;
			$total_pages = 1;
			$page=1;
			$start=0;
		}
		else
		{
			if( $count > 0 ) {
				$total_pages = ceil($count/$limit);	
				$start = ($limit*$page) - $limit; 
				if ($page > $total_pages) $page=$total_pages;
			} else {
				$total_pages = 0;
				$start = 0;
			}
		}

		$SQL = "select tlp.id, CONVERT_TZ(tlp.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, am.id as assets_id, am.assets_name, am.assets_name, am.driver_name, am.assets_image_path, am.driver_mobile, am.sim_number, am.driver_image, am.assets_friendly_nm, am.assets_category_id, am.device_id, am.km_reading, ad.division as assets_division, ao.owner as assets_owner, tlp.device_id, tlp.lati, tlp.longi, tlp.angle_dir, tlp.speed, tlp.ignition, tlp.address, tlp.runtime, tlp.data_type, tlp.battery_status, tlp.alarm_type, '' as map, TIME_TO_SEC(TIMEDIFF(NOW( ) , CONVERT_TZ(tlp.add_date,'+00:00','".$this->session->userdata('timezone')."'))) as beforeTime,(TIMESTAMPDIFF(SECOND,tlp.add_date,NOW())*60) as mins, tlp.current_zone, tlp.current_area as in_area, tlp.current_landmark as near_landmark,tlp.cross_speed as cross_speed from assests_master am left join tbl_last_point tlp on tlp.device_id = am.device_id LEFT JOIN assests_owner_master ao ON ao.id = am.assets_owner LEFT JOIN assests_division_master ad ON ad.id = am.assets_division where am.status=1 AND am.del_date is null $gsub $usub $srch $stsWhr ";
		
		if($where != "")
			$SQL .= " AND $where";
		$export_sql="";
		$export_sql=$SQL;
		
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		
		$query = $this->db->query($SQL);
		
		if($cmd=="export") 
		{
			$result = $this->db->query($export_sql);
			
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=Lastpoint". date("s").".xls"); 
			$EXCEL = "";
			$fitr="";
			
			//session date & time format
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			$fitr .="<tr>"; 
			$fitr.="<th>Datetime</th>";
			$fitr.="<th>Asset Name</th>";
			$fitr.="<th>Address</th>";
			$fitr.="<th>Speed(KM)</th>";
			$fitr.="<th>Status</th>";
			$fitr.="<th>Before Time</th>";
			$fitr.="<th>In Area</th>";
			$fitr.="<th>Near Landmark</th>";
			$fitr.="<th>Battery Status</th>";
			//$fitr.="<th>Alarm Type</th>";
			//$fitr.="<th>Running Time</th>";
			//$fitr.="<th>Message Cause</th>";
			$fitr .="</tr>";
			foreach($result->result_array() as $data)
				{
					$add_date = $data['add_date'];
					
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".date("$date_format $time_format", strtotime($add_date))."</td>"; 
					$EXCEL.="<td>".$data['assets_name']."(".$data['device_id'].")"."</td>"; 
					$EXCEL.="<td>".$data['address']."</td>";
					$EXCEL.="<td>".$data['speed']."</td>";
					$minutes = floor($data['beforeTime']/60);
					//die($minutes);
				//	echo "<br/>befor->".$data['beforeTime'];
					if($minutes!="" || $minutes!=null)
					{
						if($minutes <= 20 && $data['speed'] > 0){
							$EXCEL.="<td>Running</td>";
					//		echo "<br/>Running->".$minutes;
						}
						else if($minutes <= 20  && $data['speed'] == 0){
							$EXCEL.="<td>Parked</td>";
					//		echo "<br/>Parked->".$minutes;
						}else if($minutes > 1440){
							$EXCEL.="<td>Out of network</td>";
					//			echo "<br/>Device->".$minutes;
						}else{
							$EXCEL.="<td>Out Of Network</td>";
					//		echo "<br/>Out->".$minutes;
						}
					}
					else if($minutes==0 && $data['beforeTime']!="")
					{
						$EXCEL.="<td>Running</td>";
				//		echo "<br/>->Running->".$minutes;
					}
					else
					{
						$EXCEL.="<td>Out of network</td>";
				//		echo "<br/>->Device->".$minutes;
					}
					if($data['add_date']!="")
						$ago = ago($data['add_date']) . ' ago';
					else
						$ago = 'No Data';
					$EXCEL.="<td>".$ago."</td>";
					$EXCEL.="<td>".$data['in_area']."</td>";
					$EXCEL.="<td>".$data['near_landmark']."</td>";
					$EXCEL.="<td>".$data['battery_status']."</td>";
					//$EXCEL.="<td>".$data['alarm_type']."</td>";
					//$EXCEL.="<td>".$data['runtime']."</td>";
					//$EXCEL.="<td>".$data['data_type']."</td>";
					$EXCEL .="</tr>";
				}
		
			
			echo "<table border='1'>";
			echo "<tr><th colspan='7'> Last Points</th></tr>";
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
		/*		
		$this->db->select_max('id');
		$this->db->group_by('device_id');
		$this->db->where('device_id in ('.$dids.')'); 
		$query = $this->db->get($this->table_track);
		foreach($query->result() as $row) {
			$ids[] = $row->id;	
			$total = count($ids);
		}
		if(count($ids) > 0)
			$ids = implode(",", $ids);
		else
			$ids = 0;
		
		$this->db->select('tm.id, tm.add_date, am.assets_name, tm.device_id, tm.lati, tm.longi, tm.speed, tm.address');
		$this->db->from($this->table_track. " tm");
		$this->db->join($this->tbl_assets. " am", "am.device_id = tm.device_id", 'LEFT');
		$this->db->where('tm.id in ('.$ids.')'); 
		$this->db->orderBy('tm.id desc');
		$this->CI->flexigrid->build_query();
		
		//Get contents
		$return['records'] = $this->db->get();
		
		//Build count query
		$this->db->select('count(id) as record_count');
		$this->db->from($this->table_track);
		$this->db->where('id in ('.$ids.')'); 
		$this->CI->flexigrid->build_query();
		$this->CI->flexigrid->build_query(FALSE);
		$record_count = $this->db->get();
		$row = $record_count->row();
		
		//Get Record Count
		$return['record_count'] = $total;
		
		return $return;
		*/
	}
	
	public function prepareCombo(){
		
		$this->db->select('phone_imei');
		$this->db->distinct();
		$query = $this->db->get($this->table_track);
		 
		 $option = "<option value=''>Please Select</option>";
		 foreach ($query->result() as $row) {
               $option .= "<option value='".$row->phone_imei."'>".$row->phone_imei."</option>";
         }		  
		 return $option;
	}
	public function delete_lastpoint(){
		$ids = $_REQUEST["id"];
		//$tblAssets="UPDATE tbl_last_point SET `device_id`=concat('del_',device_id) WHERE id in(".$ids.")";
		$tblAssets="DELETE FROM tbl_last_point WHERE id in(".$ids.")";
		$this->db->query($tblAssets) or die("error");
		return TRUE;
	}
	public function get_catagory_image_id($c_id) {
            $SQL="Select assets_cat_image from assests_category_master where assests_category_master.id= '$c_id' ";
                       return $this->db->query($SQL);
        }
}
?>