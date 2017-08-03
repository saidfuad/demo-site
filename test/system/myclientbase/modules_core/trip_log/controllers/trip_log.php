<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Trip_log extends Admin_Controller {
	
	function __construct() {
		parent::__construct(FALSE);
		$this->load->model('trip_log_model','',FALSE);
	}
	function index()
	{
		$this->load->view('trip_log');
	}
	function view_map(){
		//echo $this->history_model->get_map_data();

		$rows = $this->trip_log_model->get_map_data();
		$data = array();
		
		if(count($rows)){
			foreach ($rows as $row) {
				$data['lat'] = floatval($row->lat);
				$data['lng'] = floatval($row->lng);
				$data['distance'] =  $row->distance;
	  		    $data['device_id'] =  $row->device_name."(".$row->device_id.")";
	  		    $data['date_time'] =  date('d.m.Y h:i a',strtotime($row->date_time));
			    $data['landmark_name'] =  $row->landmark_name;
			}
		}
		else{
			$this->output->set_output("No data Found");
			//die("No data Found");
			die();
		}
		
		$this->load->library('GMap');
		$this->gmap->GoogleMapAPI();
		$this->gmap->setMapType('map');
		$this->gmap->setCenterCoords($data['lat'], $data['lng']);
		$this->gmap->setWidth('100%');
		$this->gmap->setHeight('90%');
		$this->gmap->setZoomLevel('13');
		$data['headerjs'] = $this->gmap->getHeaderJS();
		$get = $this->uri->uri_to_assoc();
		//$data["prefix"] = uri_assoc('id');
		$this->load->view('view_file', $data);
	}
	function loadData(){
		
		$data = $this->trip_log_model->getAllData(); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		
		
		$i=0;  
		foreach($data['result'] as $row) {  
			$seconds = $row->time_taken;
			$hours = floor($seconds / (60 * 60));
			$divisor_for_minutes = $seconds % (60 * 60);
			$minutes = floor($divisor_for_minutes / 60);
			$row->time_taken = '';
			if($hours > 0)
				$row->time_taken .= $hours." Hour,";
			if($minutes > 0)	
				$row->time_taken .= $minutes." Min";
			$responce->rows[$i] = $row;
			$i++; 
		} 
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	function sub_grid(){
		
		$data = $this->trip_log_model->getsubData(); 
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
	function view_map_all(){

		$rows = $this->trip_log_model->get_map_data_all();
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
		$rws = $this->trip_log_model->get_assets_name();
		$data['assets_name'] = $rws[0]['assets_name'];
		$this->load->view('view_allPoints', $data);
		
	}	 
}