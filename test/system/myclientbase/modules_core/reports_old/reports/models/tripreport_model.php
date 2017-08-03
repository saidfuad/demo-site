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

class Tripreport_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function tripreport_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "tbl_track";
    }
	
	public function get_trip() 
	{
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
	//	$this->db->select('*')->from($this->table_name);
	//	$this->db->where("date(add_date) BETWEEN '" . $sdate . "' AND '" . $edate . "'");
		$SQL = "SELECT * from tbl_track where CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "' ";
		if($device){	//search by device
			$SQL .= " AND find_in_set(device_id,'$device')";
		}else{
			return;
			die();
		}
		if($where != "")
			$SQL .= " AND $where";
			$SQL .= " ORDER BY $sidx $sord";
		$result = $this->db->query($SQL);
//get trip
		$record_items = array();
		$i = 0;
		$speed = 1;
		$start = '';
		$j = 0;
		$totalDistance = 0;
		foreach ($result->result() as $row)
		{
			if($i == 0 && $row->speed != '0' && $row->address!=""){
				$start = $row->address;
				$lat1 	= $row->lati;
				$lng1 	= $row->longi;
				$speed += $row->speed;
				$start_time = date('h:i a', strtotime($row->add_date));
				$i++;
				
			}
			if($row->speed == '0' && $speed != 0 && $start != ""){
				$end = $row->address;
				$end_time = date('h:i a', strtotime($row->add_date));
				$lat2 	= $row->lati;
				$lng2 	= $row->longi;
				//caculate distance
				$dist = 0;
				$theta = $lng1 - $lng2;  
				$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));  
				$dist = acos($dist);  
				$dist = rad2deg($dist);  
				$miles = $dist * 60 * 1.1515;  
				$unit = "K";  
				if ($unit == "K") {  
					$distance = ($miles * 1.609344);  	
				}  
				else if ($unit == "N") {  
					echo ($miles * 0.8684);  
				}  
				else {  
					echo $miles;  
				}
				//avg. speed
				$speed = $speed / $i;
				$record_items[] = array("id"=>'',"add_date"=>date('d.m.Y',strtotime($row->add_date)),"start_point"=>
				$start."<br>".$start_time,
				"end_point"=>$end."<br>".$end_time,
				"avrg_speed"=>$speed,
				"dist"=>number_format($distance, 2)
				);
				$totalDistance += number_format($distance, 2);
				$speed = 0;
				$i = 0;
				$j++;
			}
		}
		if(count($record_items) == 0){
			$record_items[] = array("id"=>'',"start_point"=>'No Data Found',
			"add_date"=>'',
			"end_point"=>'',
			"avrg_speed"=>'',
			"dist"=>''
			);
			$j = 1;
		}else{
			$record_items[] = array("id"=>'',"add_date"=>'',
				"start_point"=>'',
				"end_point"=>'',
				"avrg_speed"=>'Total Distance',
				"dist"=>$totalDistance
				);
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

		$data = array();
		$data['result'] = $record_items_new;
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;
		return $data;
	}
/*	public function get_trip() 
	{	
		$sdate = $this->input->post('sdate');
		$edate = $this->input->post('edate');
		$device = $this->input->post('device');
		if($device == "")
			$device = -1;		
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-d");
			$edate = date("Y-m-d");
		}
		
		$this->db->select('*')->from($this->table_name);
		$this->db->where("date(add_date) BETWEEN '" . $sdate . "' AND '" . $edate . "'");
		if($device){	//search by device
			$this->db->where('device_id',$device);
		}
		$this->CI->flexigrid->build_query(false);
		
		//Get contents
		$return['records'] = $this->db->get();
		
		//Build count query
		$this->db->select('count(id) as record_count')->from($this->table_name);
		$this->db->where("date(add_date) BETWEEN '" . $sdate . "' AND '" . $edate . "'");
		if($device){	//search by device
			$this->db->where('device_id',$device);
		}
		$this->CI->flexigrid->build_query(FALSE);
		$record_count = $this->db->get();
		$row = $record_count->row();
		
		//Get Record Count
		$return['record_count'] = $row->record_count;
		
		//Return all
		return $return;
	}*/
	
}
?>