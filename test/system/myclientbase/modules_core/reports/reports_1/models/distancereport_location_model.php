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

class Distancereport_location_model extends Model
{
	/**
	* Instanciar o CI
	*/
	public function distancereport_location_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
    }
	
	public function get_distancereport_location($cmd) 
	{
		$user = $this->session->userdata('user_id');
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		//$device = $this->input->get('device');
		$device=trim($this->input->get('device'),",");
		
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-d");
			$edate = date("Y-m-d");
		}
		$sdate = $sdate." 00:00:00";
		$edate = $edate." 23:59:59";
		
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:10; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'id'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';
	//	$cmd=uri_assoc('cmd');
		
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
			
		
		//$SQL = "SELECT count(*) as total from landmark_log dm left join assests_master am on am.id=dm.device_id WHERE am.id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user))) and CONVERT_TZ(dm.date_time,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		$sub = "";
		if($this->session->userdata('usertype_id') != 1){	
			$sub = " and am.id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";
		}
		$SQL = "SELECT count(*) as total from landmark_log dm left join assests_master am on am.id=dm.device_id WHERE 1 $sub and CONVERT_TZ(dm.date_time,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";

		if($device!=""){	//search by device
			$SQL .= " AND find_in_set(dm.device_id,'$device')";
		}else{
			return;
			die();
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
		
		//$SQL = "SELECT dm.id, am.assets_name, am.id as aId, CONVERT_TZ(dm.date_time,'+00:00','".$this->session->userdata('timezone')."') as date_time, dm.distance_from_last as distance, lm1.name as to_location, lm2.name as from_location from landmark_log dm left join assests_master am on am.id=dm.device_id left join landmark lm1 on lm1.id=dm.landmark_id left join landmark lm2 on lm2.id=dm.last_landmark_id WHERE am.id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user))) and CONVERT_TZ(dm.date_time,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		$sub = "";
		if($this->session->userdata('usertype_id') != 1){	
			$sub = " and am.id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";
		}
		$SQL = "SELECT dm.id, am.assets_name, am.id as aId, CONVERT_TZ(dm.date_time,'+00:00','".$this->session->userdata('timezone')."') as date_time, dm.distance_from_last as distance, lm1.name as to_location, lm2.name as from_location from landmark_log dm left join assests_master am on am.id=dm.device_id left join landmark lm1 on lm1.id=dm.landmark_id left join landmark lm2 on lm2.id=dm.last_landmark_id WHERE 1 $sub and CONVERT_TZ(dm.date_time,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";

		if($device){	//search by device
			$SQL .= " AND find_in_set(dm.device_id,'$device')";
		}else{
			return;
			die();
		}
		if($where != "")
			$SQL .= " AND $where";
			
		$export_sql="";
		$export_sql=$SQL;
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		// die($SQL);
		$query = $this->db->query($SQL);
		 
		if($cmd=="export")   
		{
			$result = $this->db->query($export_sql);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=Distance_Report.xls"); 
			$EXCEL = ""; 
			$fitr="";
			
			//session date & time format 
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			$fitr .="<tr>"; 
			$fitr.="<th>".$this->lang->line("Date")."</th>";
			$fitr.="<th>".$this->lang->line("Vehicle")."</th>";
			$fitr.="<th>".$this->lang->line("From")."</th>";
			$fitr.="<th>".$this->lang->line("To")."</th>";
			$fitr.="<th>".$this->lang->line("Distance(KM)")."</th>";
			$fitr .="</tr>"; 
			//var_dump($result);
			foreach($result->result_array() as $data)
				{
					$date_time = $data['date_time'];
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td> &nbsp;".date($date_format." ".$time_format, strtotime($date_time))." </td>"; 
					$EXCEL.="<td>".$data['assets_name']."</td>"; 
					$EXCEL.="<td>".$data['from_location']."</td>"; 
					$EXCEL.="<td>".$data['to_location']."</td>"; 
					$EXCEL.="<td>".$data['distance']."</td>";
					$EXCEL .="</tr>";
					$device_name = $data['assets_id'];
				}
			if($this->session->userdata('id')==1)
				$count=3;
			else
				$count=2;
			
			echo "<table border='1'>";
			//echo "<tr><th colspan='3'>Distance Between " . date($date_format,strtotime($sdate)) . " AND " . date($date_format,strtotime($edate)) . "</th></tr>";
			echo "<tr><th colspan='3'>".$this->lang->line("Distance")."</th></tr>";
			echo "<tr><th colspan='1'>".$this->lang->line("Start Date")."</th><th colspan='1'>".$this->lang->line("End Date")."</th><th colspan='1'>".$this->lang->line("Vehicle")."</th></tr>";
			echo "<tr><th colspan='1'>&nbsp;".date("$date_format $time_format", strtotime($sdate))."</th><th colspan='1'>&nbsp;".date("$date_format $time_format", strtotime($edate))."</th><th colspan='1'></th></tr>";
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
}