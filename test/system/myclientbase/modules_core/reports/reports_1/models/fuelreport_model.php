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

class Fuelreport_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function fuelreport_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "tbl_track";
    }
	
	public function get_data($cmd) 
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
			
		/*$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		$device = $this->input->get('device');
		*/
		$SQL = "SELECT count(*) as total from distance_master dm left join assests_master am on am.id=dm.assets_id WHERE ";
		
		if($this->session->userdata("usertype_id")!=1){
			$SQL .=  " am.id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user))) and ";
		}
		$SQL .= " CONVERT_TZ(dm.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		if($device!=""){	//search by device
			$SQL .= " AND find_in_set(dm.assets_id,'$device')";
		}else{
			return;
			die();
		}
		//$SQL .= " AND dm.assets_id = $device";
			
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
		
		$SQL = "SELECT dm.id, am.assets_name, am.id as aId, CONVERT_TZ(dm.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, dm.first_reading, dm.current_reading, dm.distance, dm.fuel_used from distance_master dm left join assests_master am on am.id=dm.assets_id WHERE ";
		if($this->session->userdata("usertype_id")!=1){
			$SQL .=  " am.id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user))) and ";
		}
		$SQL .= " CONVERT_TZ(dm.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		if($device){	//search by device
			$SQL .= " AND find_in_set(dm.assets_id,'$device')";
		}else{
			return;
			die();
		}
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
			header("Content-Disposition: attachment; filename=Fuel_Report.xls"); 
			$EXCEL = ""; 
			$fitr="";
			
			//session date & time format 
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			$fitr .="<tr>";
			$fitr.="<th>".$this->lang->line("Assets Name")."</th>";
			$fitr.="<th>".$this->lang->line("Date")."</th>";
			$fitr.="<th>".$this->lang->line("Start Km")."</th>";
			$fitr.="<th>".$this->lang->line("End Km")."</th>";
			$fitr.="<th>".$this->lang->line("Total Distance(Km)")."</th>";
			$fitr.="<th>".$this->lang->line("Fuel(Ltr)")."</th>";
			$fitr.="<th>".$this->lang->line("Mileage(Kmpl)")."</th>";
			$fitr .="</tr>"; 
			//var_dump($result);
			foreach($result->result_array() as $data)
				{
					$start_km = round($data['first_reading']/1000, 2);
					$end_km = round($data['current_reading']/1000, 2);
					$distance = $data['distance'];
					$fuel_used = $data['fuel_used'];
					$mileage = round($data['distance']/$data['fuel_used'], 2);
					$add_date = date($date_format,strtotime($data['add_date']));
					$assets_name = $data['assets_name'];
										
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$assets_name." </td>"; 
					$EXCEL.="<td>".$add_date." </td>"; 
					$EXCEL.="<td>".$start_km."</td>";
					$EXCEL.="<td>".$end_km."</td>";
					$EXCEL.="<td>".$distance."</td>";
					$EXCEL.="<td>".$fuel_used."</td>"; 
					$EXCEL.="<td>".$mileage."</td>"; 
					$EXCEL .="</tr>";
					$device_name = $data['assets_id'];
				}
			if($this->session->userdata('id')==1)
				$count=3;
			else
				$count=2;
			
			echo "<table border='1'>";
			
			echo "<tr><th colspan='7'>".$this->lang->line("Distance & Fuel Consumption Report")."</th></tr>";
			
			echo $fitr;
			echo $EXCEL;
			echo "</table>";
			die(); 
		}
		
		$data = array();
		$data['result'] = $query->result();
		//$data['count_pay'] = $query1->result();
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;
		return $data; 
		
	}
	
	function get_map_data(){
		$id=uri_assoc('id');
		
		$squery="select id, device_id, CONVERT_TZ(ignition_off,'+00:00','".$this->session->userdata('timezone')."') as ignition_off, CONVERT_TZ(ignition_on,'+00:00','".$this->session->userdata('timezone')."') as ignition_on, duration, address, lat, lng, CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, alert_given, current_area, current_landmark from ".$this->table_name." where id=$id Limit 1";
		$query = $this->db->query($squery);
		return $query->result();	
	}
}
?>