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

class Stopreport_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function stopreport_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "tbl_stop_report";
    }
	
	public function get_stopdata($cmd) 
	{
		$user = $this->session->userdata('user_id');
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		$group = $this->input->get('group');
		$stop_minute = $this->input->get('stop_minute');
		$device=trim($this->input->get('device'),",");
		//$device = implode(",",$this->input->get('device'));
		
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d H:i:s", strtotime($sdate));
			$edate = date("Y-m-d H:i:s", strtotime($edate));
		}else{
			$sdate = date("Y-m-d H:i:s");
			$edate = date("Y-m-d H:i:s");
		}
		
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:10; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'tm.id'; 
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

	
		if(!$sidx)  
			$sidx = 1;
		
		//$SQL = "SELECT count(*) FROM ".$this->table_name." tm left join ".$this->tbl_assets." am on am.device_id = tm.device_id WHERE am.status=1 AND am.del_date is null AND date(tm.add_date) BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		$SQL = "SELECT count(*) as total FROM ".$this->table_name." tm WHERE CONVERT_TZ(tm.ignition_off,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '".$edate."'";
		if($device){	//search by device
			$SQL .= " AND find_in_set(tm.device_id,'$device')";
		}else{
			return;
			die();
			//$SQL .= " AND tm.device_id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";		
		}
		if($group != ""){
			$SQL .=" AND find_in_set(tm.device_id, (select assets from group_master where id = $group))";
		}
		if($stop_minute != ""){
			$off = "CONVERT_TZ(tm.ignition_off,'+00:00','".$this->session->userdata('timezone')."')";
			$on = "CONVERT_TZ(tm.ignition_on,'+00:00','".$this->session->userdata('timezone')."')";
			$SQL .=" AND (TIME_TO_SEC( TIMEDIFF(  $on,  $off ) ) /60) >= $stop_minute";
		}else{
			$off = "CONVERT_TZ(tm.ignition_off,'+00:00','".$this->session->userdata('timezone')."')";
			$on = "CONVERT_TZ(tm.ignition_on,'+00:00','".$this->session->userdata('timezone')."')";
			$SQL .=" AND (TIME_TO_SEC( TIMEDIFF(  $on,  $off ) ) /60) >= 1";
		}
		if($where != "")
			$SQL .= " AND $where";
		
		$result = $this->db->query($SQL);
		$data_arr=$result->result_array();
		
		$count = $data_arr[0]['total'];
	
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);
			$start = ($limit*$page) - $limit;  
		} else {
			$total_pages = 0;
			$start = 0;
		}

		if ($page > $total_pages) 
			$page = $total_pages;

		$SQL = "SELECT tm.id, tm.lat, tm.lng, am.device_id, CONVERT_TZ(now(),'+00:00','".$this->session->userdata('timezone')."') as now, CONVERT_TZ(tm.ignition_off,'+00:00','".$this->session->userdata('timezone')."') as ignition_off, CONVERT_TZ(tm.ignition_on,'+00:00','".$this->session->userdata('timezone')."') as ignition_on, tm.duration, tm.address, am.assets_name FROM ".$this->table_name." tm left join assests_master am on tm.device_id=am.id WHERE CONVERT_TZ(tm.ignition_off,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '".$edate."'";
		if($device){	//search by device
			$SQL .= " AND find_in_set(tm.device_id,'$device')";
		}else{
			$SQL .= " AND tm.device_id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";		
		}
		if($group != ""){
			$SQL .=" AND find_in_set(tm.device_id, (select assets from group_master where id = $group))";
		}
		if($stop_minute != ""){
			$off = "CONVERT_TZ(tm.ignition_off,'+00:00','".$this->session->userdata('timezone')."')";
			$on = "CONVERT_TZ(tm.ignition_on,'+00:00','".$this->session->userdata('timezone')."')";
			$SQL .=" AND (TIME_TO_SEC( TIMEDIFF(  $on,  $off ) ) /60) >= $stop_minute";
		}else{
			$off = "CONVERT_TZ(tm.ignition_off,'+00:00','".$this->session->userdata('timezone')."')";
			$on = "CONVERT_TZ(tm.ignition_on,'+00:00','".$this->session->userdata('timezone')."')";
			$SQL .=" AND (TIME_TO_SEC( TIMEDIFF(  $on,  $off ) ) /60) >= 1";
		}
		if($where != "")
			$SQL .= " AND $where";
		//$result = $this->db->query($SQL);
		//die($SQL);
		$export_sql="";
		$export_sql=$SQL;
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		
		$query = $this->db->query($SQL);
		
		
		if($cmd=="export") 
		{
			$result = $this->db->query($export_sql);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=stopreport". date("s").".xls"); 
			$EXCEL = "";
			$fitr="";
			
			//session date & time format
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			$date="";
			//$device="";
			$fitr .="<tr>";
			$fitr.="<th>".$this->lang->line("Assets Name")."</th>";			
			$fitr.="<th>".$this->lang->line("Stop Time")."</th>";
			$fitr.="<th>".$this->lang->line("Start Time")."</th>";
			$fitr.="<th>".$this->lang->line("Location")."</th>";
			$fitr.="<th>".$this->lang->line("Duration")."</th>";
			$fitr .="</tr>";
			
			foreach($result->result_array() as $data)
				{
					$ignition_off = $data['ignition_off'];
					$ignition_on = $data['ignition_on'];
					//$add_date = $data['add_date'];
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data['assets_name']."(".$data['device_id'].")</td>"; 
					$EXCEL.="<td>&nbsp;".date("$date_format $time_format", strtotime($ignition_off))."</td>"; 
					$EXCEL.="<td>&nbsp;".date("$date_format $time_format", strtotime($ignition_on))."</td>"; 
					$EXCEL.="<td>".$data['address']."</td>";
					$EXCEL.="<td>".$data['duration']."</td>";
					$EXCEL .="</tr>";
					$date=date("$date_format $time_format", strtotime($ignition_off));
					$device_name = $data['assets_name']." (".$data['device_id'].")";
				}
				$sdt = date("$date_format $time_format", strtotime($sdate));
				$edt = date("$date_format $time_format", strtotime($edate));
			if($this->session->userdata('id')==1)
				$count=3; 
			else
				$count=2; 
			
			if($device == '')
				$device = $this->lang->line("ALL");
			else
				$device = $device_name;
				
 			echo "<table border='1'>";
			echo "<tr><th colspan='5'>".$this->lang->line("Stop Report")."</th></tr>";
			echo "<tr><th colspan='2'>".$this->lang->line("Start Date")."</th><th colspan='2'>".$this->lang->line("Stop Date")."</th><th>".$this->lang->line("Assets Name")."</th></tr>";
			echo "<tr><th colspan='2'>&nbsp;$sdt</th><th colspan='2'>&nbsp;$edt</th><th>&nbsp;</th></tr>";
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
		/*----
		$SQL = "SELECT tr.*, concat(am.assets_name, concat('(',am.device_id,')')) as device_name from tbl_track tr Left Join assests_master am on am.device_id=tr.device_id WHERE date(tr.add_date) = '" . $add_date ."'";
		if($device!="")	//search by device
			$SQL .= " AND tr.device_id = $device";
		if($where != "")
			$SQL .= " AND $where";
		
		$result = $this->db->query($SQL);
		

		$record_items = array();
		$i = 0;
		$start_time = '';
		$end_time = '';
		$duration = '';
		$count = 0;
		$j = 0;
		foreach ($result->result() as $row)
		{
			if($row->speed != 0 && $i != 0){
		
				$i = 0;
				$end_time = date('h:i:s a', strtotime($row->add_date));
				
				$minutes = round(abs(strtotime($end_time) - strtotime($
				 _time)) / 60,2);
				
				$d = floor ($minutes / 1440);
				$h = floor (($minutes - $d * 1440) / 60);
				$m = floor($minutes - ($d * 1440) - ($h * 60));

				$s = strtotime($end_time) - strtotime($start_time);
				$s = $s - ($m * 60);
				$duration = str_pad($h, 2, "0", STR_PAD_LEFT).":".str_pad($m, 2, "0", STR_PAD_LEFT).":".str_pad($s, 2, "0", STR_PAD_LEFT);
				
				$html .= 'Stop From : '.$start_time. "<br>";
				$html .= 'Stop To : '.$end_time. "<br>";
				$html .= 'Stop Duration : '.$duration. "<br>";				
				
				$count++;
				$record_items[] = array("id"=>$row->id,$count,"start_time"=>$start_time, "end_time"=>$end_time,	"location"=>$location, "duration"=>$duration,"map"=> '<a href=\'#\' onclick=\'view_map_stop_report("'.$lati.'","'.$longi.'","'.$html.'",'.$row->device_id.')\'><img border=\'0\' src=\''.$this->config->item('base_url').'assets/style/css/images/icon_marker.png\'></a>');
				$j++;
			}
			if($row->speed == 0 && $i == 0 && $row->address!=""){
				$location = $row->address;
				$start_time = date('h:i:s a', strtotime($row->add_date));
				$lati = $row->lati;
				$longi = $row->longi;
				$html = 'Device : '.$row->device_name. "<br>";
				$html .= 'Address : '.$location. "<br>";
				$i++;
			}		
			 
		}
		
		if(count($record_items) == 0){
			$record_items[] = array("id"=>'','',"start_time"=>'',"location"=>'No Data Found',"end_time"=> '',"duration"=> '',"map"=>"");
			$j = 1;
		}
		
		$count = count($record_items);
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);
			$start = ($limit*$page) - $limit; 
		} else {
			$total_pages = 0;
			$start = 0;
		}
		if ($page > $total_pages) 
			$page = $total_pages;
		$record_items_new = array();
		for($i=$start;$i<($start+$limit);$i++)
		{
			if(isset($record_items[$i]))
			$record_items_new[] = $record_items[$i];
		}
		//die($SQL);
		if($cmd=="export") 
		{
			
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=stopreport". date("s").".xls"); 
			$EXCELTR = "";
			$addressfill = "";
			$fitr="";
		
			$record_items = array();
			$i = 0;
			$start_time = '';
			$end_time = '';
			$duration = '';
			$count = 0;
			$j = 0;
		
			//session date & time format
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			if($this->session->userdata('id')==1)
			{
				$fitr.="<th>Owner</th>";
			}
			$fitr .="<tr>";
			$fitr.="<th>Start Time</th>";
			$fitr.="<th>End Time</th>";
			$fitr.="<th>Duration</th>";
			$fitr.="<th>Location</th>";
			$fitr .="</tr>"; 
			 
			foreach ($result->result() as $row)
			{
				
				if($row->speed != 0 && $i != 0){
					$i = 0;
					$EXCELTR .="<tr align='center'>";
					$end_time = date('h:i:s a', strtotime($row->add_date));
					
					$minutes = round(abs(strtotime($end_time) - strtotime($start_time)) / 60,2);
					
					$d = floor ($minutes / 1440);
					$h = floor (($minutes - $d * 1440) / 60);
					$m = floor($minutes - ($d * 1440) - ($h * 60));

					$s = strtotime($end_time) - strtotime($start_time);
					$s = $s - ($m * 60);
					$duration = str_pad($h, 2, "0", STR_PAD_LEFT).":".str_pad($m, 2, "0", STR_PAD_LEFT).":".str_pad($s, 2, "0", STR_PAD_LEFT);
					
					$EXCELTR.="<td>".$start_time."</td>"; 
					$EXCELTR.="<td>".$end_time."</td>"; 
					$EXCELTR.="<td>".$duration."</td>";
					$EXCELTR.=$addressfill;
					$count++;
					//$record_items[] = array("id"=>'',$count,"start_time"=>$start_time, "end_time"=>$end_time,	"location"=>$location, "duration"=>$duration,"map"=> '<a href=\'#\' onclick=\'view_map_stop_report("'.$lati.'","'.$longi.'","'.$html.'",'.$row->device_id.')\'><img border=\'0\' src=\''.$this->config->item('base_url').'assets/style/css/images/icon_marker.png\'></a>');
					$j++;
					$EXCELTR .="</tr>";
				}
				if($row->speed == 0 && $i == 0 && $row->address!=""){
				
					$location = $row->address;
					$start_time = date('h:i:s a', strtotime($row->add_date));
					$lati = $row->lati;
					$longi = $row->longi;
					//$EXCEL.="<td>".$row->device_id."</td>";
					$addressfill="<td>".$row->address."</td>";
					$i++;
				}		
			}
			if($this->session->userdata('id')==1)
				$count=3;
			else
				$count=2;

			echo "<table border='1'>";
			echo "<tr><th colspan='4'> Stop Report</th></tr>";
			echo $fitr;
			echo $EXCELTR;
			echo "</table>";
			die(); 
		}
		$data = array();
		$data['result'] = $record_items_new;
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;
		return $data;
	*/
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*	//Select table name
		$date = $this->input->post('date');
		$device = $this->input->post('device');
		if($device == "")
			$device = -1;	
		if($date){	//search by date
			$date = date("Y-m-d", strtotime($date));
		}else{
			$date = date("Y-m-d");
		}
				
		$this->db->select('*')->from($this->table_name);
		$this->db->where('date(add_date)', $date);
		if($device){	//search by device
			$this->db->where('device_id',$device);
		}
		
		//Get contents
		$return['records'] = $this->db->get();
		
		//Build count query
		$this->db->select('count(id) as record_count')->from($this->table_name);
		$this->db->where('date(add_date)', $date);
		if($device){	//search by device
			$this->db->where('device_id',$device);
		}
		$record_count = $this->db->get();
		$row = $record_count->row();
		
		//Get Record Count
		$return['record_count'] = $row->record_count;
		
		return $return;*/
	}
	
	function get_map_data(){
			$id=uri_assoc('id');
			
			$squery="select id, device_id, CONVERT_TZ(ignition_off,'+00:00','".$this->session->userdata('timezone')."') as ignition_off, CONVERT_TZ(ignition_on,'+00:00','".$this->session->userdata('timezone')."') as ignition_on, duration, address, lat, lng, CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, alert_given, current_area, current_landmark from ".$this->table_name." where id=$id Limit 1";
			$query = $this->db->query($squery);
			return $query->result();	
	
	}
}
?>