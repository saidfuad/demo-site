<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Home extends Admin_Controller {

	function index()
	{
		
		$this->load->model('live/device_model');
		$this->load->model('assets/asset_model');
		$this->load->model('route_model');
		
		// $active_device = $this->input->get("active");
		
		$this->load->library('GMap');

		$this->gmap->GoogleMapAPI();
		
		// valid types are hybrid, satellite, terrain, map
		$this->gmap->setMapType('map');
		
		$this->gmap->setCenterCoords(22.296024, 70.785540);
		
		$this->gmap->setWidth('100%');
		
		$this->gmap->setHeight('90%');
		
		$this->gmap->setZoomLevel('13');
		
		$deviceOpt = "";
		$rows = $this->home_model->get_landmark($this->session->userdata('user_id'));
		$coords = array();
		if(count($rows) > 0) {
			foreach ($rows as $row) {
				$coords[] = $row;
			}
		}
		$data['coords'] = $coords;
		
		$data['headerjs'] = $this->gmap->getHeaderJS();
		$data['headermap'] = $this->gmap->getMapJS();
		$data['map'] = $this->gmap->printMap();
		$data['onload'] = $this->gmap->printOnLoad();
		$data['sidebar'] = $this->gmap->printSidebar();
		
		$rows = $this->device_model->get_links();
		if(count($rows)) {
			
			foreach ($rows as $row) {				
				$deviceOpt .= "<option value='".$row->device_id."'>".$row->assets_name." (".$row->device_id.")</option>";				
			}
		}
		else {
			$deviceOpt .= "<option value=''>No Assets</option>";
		}
		
		$data['deviceOpt'] = $deviceOpt;
				
		$plyRows = $this->device_model->get_poly_home();
		
		$data['plyId'] = array();
		$data['plyDev'] = array();
		$data['plyLat'] = array();
		$data['plyLng'] = array();
		$data['plyName'] = array();
		$data['plyColor'] = array();
		foreach ($plyRows as $row) {
			
			$data['plyId'][] = $row->polyid;
			$data['plyLat'][$row->polyid][] = $row->lat;
			$data['plyLng'][$row->polyid][] = $row->lng;
			$data['plyName'][$row->polyid][] = $row->polyname;
			$data['plyDev'][$row->polyid][] = $row->deviceid;
			$data['plyColor'][$row->polyid] = ($row->color != "") ? $row->color : "#ff0000";
		}
		
		if(count($data['plyId']) > 0){
			$data['plyId'] = array_unique($data['plyId']);
			foreach($data['plyId'] as $pid){
				if(count($data['plyDev'][$pid]) > 0){
					$data['plyDev'][$pid] = array_unique($data['plyDev'][$pid]);
				}
			}
		}
		$rows = $this->asset_model->prepare_icon();
		$iconOpt = '';
		foreach ($rows as $row) {
			$iconOpt .= '<option title="'.$this->config->item('base_url').'assets/marker-images/'.$row->icon_path.'" value="'.$row->id.'">'.$row->icon_name.'</option>';
		}
		$data['iconOpt'] = $iconOpt;
		$this->load->view('geofence',$data);
		
	}

}
?>