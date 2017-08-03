<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Reports extends Admin_Controller {
	
		
	function index()
	{
		$this->load->library('GMap');

		$this->gmap->GoogleMapAPI();
		
		// valid types are hybrid, satellite, terrain, map
		$this->gmap->setMapType('map');
		
		$this->gmap->setCenterCoords(22.296024, 70.785540);
		
		$this->gmap->setWidth('100%');
		
		$this->gmap->setHeight('90%');
		
		$this->gmap->setZoomLevel('13');
		
		$arr = array();
		$arr[0]['lat'] = 22.292090;
		$arr[0]['long'] = 70.792210;
		$arr[1]['lat'] = 22.3021545;
		$arr[1]['long'] = 70.782444;
		$arr[2]['lat'] = 22.3121545;
		$arr[2]['long'] = 70.802444;
		
		$this->gmap->addPolylineByCoordsArray($arr,true,'#cc0000',3,50);
		
		// you can also use addMarkerByCoords($long,$lat)
		// both marker methods also support $html, $tooltip, $icon_file and
		//$icon_shadow_filename
		$this->gmap->addMarkerByAddress("rajkot, gujarat", "Marker Description");
		
		$data['headerjs'] = $this->gmap->getHeaderJS();
		$data['headermap'] = $this->gmap->getMapJS();
		$data['map'] = $this->gmap->printMap();
		$data['onload'] = $this->gmap->printOnLoad();
		$data['sidebar'] = $this->gmap->printSidebar();
		
		$this->load->model('device_model');
		$rows = $this->device_model->get_links();
		/*$links = "";
		
		foreach ($rows as $row) {
			$links .= '<li><a name="'.base_url().'index.php/live/device/id/'.$row->device_id.'/window/current" title="'.$row->assets_name.'" href="#" class="link">'.$row->assets_name.' ('.$row->device_id.')</a></li>';
        }
		$data['links'] = $links;
		*/
		$this->load->view('reports',$data);  
		
	}
	
	function device()
	{
		$this->load->model('device_model');
		$rows = $this->device_model->get_locations();
		foreach ($rows as $row) {
            $data['lat'] = $row->lati;
			$data['lng'] = $row->longi;
			$data["last_id"] = $row->id;
			$text  = 'Lat : '.$row->lati."<br>";
			$text .= 'Lng : '.$row->longi."<br>";
			$text .= 'Date : '.$row->add_date."<br>";
			$text .= 'Speed : '.$row->speed."<br>";
			$data['html'] = $text;
        }
		
		$this->load->library('GMap');

		$this->gmap->GoogleMapAPI();
		
		// valid types are hybrid, satellite, terrain, map
		$this->gmap->setMapType('map');
		
		$this->gmap->setCenterCoords($data['lat'], $data['lng']);
		
		$this->gmap->setWidth('100%');
		
		$this->gmap->setHeight('90%');
		
		$this->gmap->setZoomLevel('13');
		$data['headerjs'] = $this->gmap->getHeaderJS();
		
		$get = $this->uri->uri_to_assoc();
		$data["prefix"] = uri_assoc('id');
		if($get['window']=='new'){
			$this->load->view('device_new_window',$data);
		}else{
			$this->load->view('device',$data);
		}
		
		
	}
	function newPoint()
	{
		$this->load->model('device_model');
		$rows = $this->device_model->get_new_locations();
		$lat = array();
		$lng = array();
		$html = array();
		foreach ($rows as $row) {
            $lat[] = $row->lati;
			$lng[] = $row->longi;
			$text  = 'Lat : '.$row->lati.'<br>';
			$text .= 'Lng : '.$row->longi.'<br>';
			$text .= 'Date : '.$row->add_date."<br>";
			$text .= 'Speed : '.$row->speed."<br>";
			$html[] = $text;
			$data["last_id"] = $row->id;
        }
		$data['lat'] = $lat;
		$data['lng'] = $lng;
		$data['html'] = $html;
		die(json_encode($data));
	}
	
	
	function trackList() {
		
		$page = (uri_assoc('page')) ? uri_assoc('page') : 1;
		$params = array(
			'limit'		=>	$this->mdl_mcb_data->results_per_page,
			'paginate'	=>	TRUE,
			'page'		=>	$page,
			'order_by'	=>	'id desc'
		);
		
		$data = array(
			'track' =>	$this->mdl_track->get($params)
		);
		$this->load->view('tracklist', $data);
	}
	
	function map3()
	{
		$this->load->library('GMap');

		$this->gmap->GoogleMapAPI('map3_map');
		
		// valid types are hybrid, satellite, terrain, map
		$this->gmap->setMapType('map');
		
		$this->gmap->setCenterCoords(22.296024, 70.785540);
		
		$this->gmap->setWidth('100%');
		
		$this->gmap->setHeight('90%');
		
		$this->gmap->setZoomLevel('13');
		
		$data["prefix"] = "map3_";
		$data["c_lat"]	= 22.296024;
		$data["c_lng"]	= 70.785540;
		
		$this->load->view('map3',$data);
	}
		
	function map_form()
	{
		$this->load->library('GMap');

		$this->gmap->GoogleMapAPI();
		
		// valid types are hybrid, satellite, terrain, map
		$this->gmap->setMapType('map');
		
		$this->gmap->setCenterCoords(22.296024, 70.785540);
		
		$this->gmap->setWidth('100%');
		
		$this->gmap->setHeight('90%');
		
		$this->gmap->setZoomLevel('13');
		
		/*$arr = array();
		$arr[0]['lat'] = 22.292090;
		$arr[0]['long'] = 70.792210;
		$arr[1]['lat'] = 22.3021545;
		$arr[1]['long'] = 70.782444;
		$arr[2]['lat'] = 22.3121545;
		$arr[2]['long'] = 70.802444;
		
		$this->gmap->addPolylineByCoordsArray($arr,true,'#cc0000',3,50);*/
		
		// you can also use addMarkerByCoords($long,$lat)
		// both marker methods also support $html, $tooltip, $icon_file and
		//$icon_shadow_filename
		$this->gmap->addMarkerByAddress("rajkot, gujarat", "Marker Description");
				
		$this->load->view('map_form');
	}
	}
?>