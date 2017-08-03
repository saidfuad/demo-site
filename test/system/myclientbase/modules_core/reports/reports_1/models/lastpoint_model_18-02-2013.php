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
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		
		$limit = isset($_GET["rows"])?$_GET["rows"]:10; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'id'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';
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
	
		if(isset($_REQUEST['report']))
			$report=$_REQUEST['report'];
		else
			$report="";
	
		$srch = "";
		$stsWhr = "";
		$group = "";
		$sub="";
		$user = $this->session->userdata('user_id');
		
		if($report!="")
		{
		
		$rptsub = substr($report, 0, 2);
		
		if($rptsub == "g-"){
			$group = str_replace($rptsub, "", $report);
		}
		elseif($rptsub == "u-"){
			$user = str_replace($rptsub, "", $report);
		}
		elseif($report == "running"){
			$stsWhr = " AND (TIME_TO_SEC(TIMEDIFF(NOW( ) , tlp.add_date)) <= 1200 and tlp.speed > 0)";
		}
		elseif($report == "parked"){
			$stsWhr = " AND TIME_TO_SEC(TIMEDIFF( NOW( ) , tlp.add_date)) <= 1200 and tlp.speed = 0";
		}
		elseif($report == "out_of_network"){
			$stsWhr = " AND TIME_TO_SEC(TIMEDIFF( NOW( ) , tlp.add_date)) between 1201 and 86399";
		}
		elseif($report == "device_fault"){
			$stsWhr = " AND (TIME_TO_SEC(TIMEDIFF( NOW( ) , tlp.add_date)) >= 86400 OR tlp.add_date is null)";
		}
		}
		if($group != ""){
			$this->db->select("*", FALSE);
			$this->db->where('id', $group);
			$query = $this->db->get('group_master');			
			$rows = $query->result();
			foreach ($rows as $row) {
				$assets = $row->assets;
			}
			if($assets!="")
				$sub = " am.id in($assets)";
			else
				$sub = " am.id in(-1)";
		}
		else{
			$sub = " find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id =".$user."))";
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
		$SQL = "select count(*) as total from assests_master am left join tbl_last_point tlp on tlp.device_id = am.device_id where am.status=1 AND am.del_date is null AND $sub $srch $stsWhr ";
		
		
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

		$SQL = "select tlp.id, CONVERT_TZ(tlp.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, am.id as assets_id,am.assets_name, tlp.device_id, tlp.lati, tlp.longi, tlp.speed, tlp.address, '' as map, TIME_TO_SEC(TIMEDIFF(NOW( ) , tlp.add_date)) as beforeTime,(TIMESTAMPDIFF(SECOND,tlp.add_date,NOW())*60) as mins, tlp.current_area as in_area, tlp.current_landmark as near_landmark,tlp.cross_speed as cross_speed from assests_master am left join tbl_last_point tlp on tlp.device_id = am.device_id where am.status=1 AND am.del_date is null AND $sub $srch $stsWhr ";
		
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
					$EXCEL.="<td>".ago($data['add_date']). ' ago'."</td>";
					$EXCEL.="<td>".$data['in_area']."</td>";
					$EXCEL.="<td>".$data['near_landmark']."</td>";
					
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
	
}
?>