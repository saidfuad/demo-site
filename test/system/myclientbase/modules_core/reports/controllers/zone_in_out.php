<?php
class Zone_in_out extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('zone_in_out_model','',TRUE);
		$this->load->model('home/home_model','',TRUE);
	}
	
	function index()
	{
		$result = $this->home_model->get_group($this->session->userdata('user_id')); 
		$group = "";
		foreach($result as $row) {
			$group .= "<option value='".$row->id."'>".$row->group_name."</option>";
		}
		$responce['group'] = $group;
		
		$result = $this->zone_in_out_model->get_zone($this->session->userdata('user_id')); 
		$zone = "";
		foreach($result as $row) {
			$zone .= "<option value='".$row->polyid	."'>".$row->polyname."</option>";
		}
		$responce['zone'] = $zone;
		$this->load->view('zone_in_out', $responce);
	}
	function loadData($cmd='false'){
		$data = $this->zone_in_out_model->getAllData($cmd); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		foreach($data['result'] as $row) {
			$responce->rows[$i] = $row;
			$i++;
		}
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	function trackOnMap()
	{
		$rows = $this->zone_in_out_model->get_all_locations();
		$lat = array();
		$lng = array();
		$html = array();
		
		$lat2 = '';
		$lng2 = '';
		$distance = 0;
		foreach ($rows as $row) {
            $lat[] = $row->lati;
			$lng[] = $row->longi;
			$text = 'Date : '.date('d.m.Y h:i: a', strtotime($row->add_date))."<br>";
			$text .= 'Speed : '.$row->speed."<br>";
			//$text .= 'Lat : '.$row->lati.'<br>';
			//$text .= 'Lng : '.$row->longi.'<br>';
			$text .= 'Address : '.$row->address.'<br>';
			$html[] = $text;
			$data["last_id"] = $row->id;
			
			$lat1 = $row->lati;
			$lng1 = $row->longi;
			if($lat2 != "" && $lng2 != ""){
				$theta = $lng1 - $lng2;  
				$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));  
				$dist = acos($dist);  
				$dist = rad2deg($dist);  
				$miles = $dist * 60 * 1.1515;  
				$unit = "K";  
				if ($unit == "K") {  
					$dstn = round(($miles * 1.609344), 2);
					if(!is_nan($dstn)){
						$distance += $dstn;
					}					
				 }
			}
			$lat2 = $lat1;
			$lng2 = $lng1;
			
        }
		$data['lat'] = $lat;
		$data['lng'] = $lng;
		$data['html'] = $html;
		$data['distance'] = $distance;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
}
?>