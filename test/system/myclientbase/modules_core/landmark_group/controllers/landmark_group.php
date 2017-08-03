<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Landmark_group extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('landmark_group_model','',TRUE);
		$this->load->model('landmark_form_model','',TRUE);
	}
	function index()
	{
		$this->load->view('landmark_group_grid');
	}
	function loadData(){
		
		$data = $this->landmark_group_model->getAllData(); 
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
	function deleteData(){
		//echo $this->landmark_group_model->delete_assets(); 
		$this->output->set_output($this->landmark_group_model->delete_assets()); 
	}

	
	function index1()
	{
		
		$this->load->helper('flexigrid');
		/*
		 * 0 - display name
		 * 1 - width
		 * 2 - sortable
		 * 3 - align
		 * 4 - searchable (2 -> yes and default, 1 -> yes, 0 -> no.)
		 */
		$colModel['id'] = array('ID',40,TRUE,'center',2);
		$colModel['assets_name'] = array('Asset Name',150,TRUE,'center',1);
		$colModel['device_id'] = array('Device',100,TRUE,'center',1);
		$colModel['icon_id'] = array('Icon',100,TRUE,'center',1);
		$colModel['sim_number'] = array('Sim Number',150, TRUE,'center',1);
		
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 'auto',
		'height' => 'auto',
		'rp' => 10,
		'rpOptions' => '[10,15,20,25,40]',
		'pagestat' => 'Displaying: {from} to {to} of {total} items.',
		'blockOpacity' => 0.5,
		'title' => 'My Assets List',
		'showTableToggleBtn' => false
		);
		
		$buttons[] = array('Add','add','actionAssets');
		$buttons[] = array('Edit','edit','actionAssets');
		$buttons[] = array('Delete','delete','actionAssets');
		$buttons[] = array('separator');
		$buttons[] = array('Select All','add','actionAssets');
		$buttons[] = array('DeSelect All','delete','actionAssets');
		$buttons[] = array('separator');
		
		
		
		$data['headerjs'] = '';
		$data['js_grid'] = $grid_js;
//		print_r($data);
		$this->load->view('assets',$data);
	}

		function form() {

		if (!$this->landmark_form_model->validate()) {

			$this->load->helper('form');

			if (!$_POST AND uri_assoc('id')) {

				$this->landmark_form_model->prep_validation(uri_assoc('id'));

			}
			$this->load->view('form');

		}

		else {
			$formdata = $this->landmark_form_model->db_array();
			$formdata['user_id'] = $this->session->userdata('user_id');
			if(count($formdata) == 2)
			{
			}
			else
			{
				$landmark_name=$formdata['landmark_name'];
				unset($formdata['landmark_name']);
			}
			if(uri_assoc('id')){
				$this->landmark_form_model->save($formdata, uri_assoc('id'));
				if(!empty($landmark_name)){
					$data = $this->landmark_group_model->setdefault_user($formdata['landmark_group_name'],$landmark_name,uri_assoc('id')); 
				}else{
						$data = $this->landmark_group_model->getdefault_user($formdata['landmark_group_name'],uri_assoc('id')); 
				}
			}else{
				$this->landmark_group_model->save($formdata, uri_assoc('id'));
				if(!empty($landmark_name)){
				$data = $this->landmark_group_model->setdefault_user($formdata['landmark_group_name'],$landmark_name,uri_assoc('id')); 
				}
			}

		}
			
	}
	function export(){
		
		$this->load->plugin('to_excel'); 
		$this->landmark_form_model->export();
	}
	
	function asset()
	{
		$this->load->model('landmark_group_model');
		$rows = $this->landmark_group_model->get_locations();
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
			$this->load->view('asset_new_window',$data);
		}else{
			$this->load->view('asset',$data);
		}
		
		
	}
	function default_update_landmark(){
			$this->load->model('landmark_group_model');
			$this->landmark_group_model->setdefault_user();
	}
	function newPoint()
	{
		$this->load->model('landmark_group_model');
		$rows = $this->landmark_group_model->get_new_locations();
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
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
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
	function chk_nm()
	{
		$rows = $this->landmark_group_model->check_landmark_name();
		//die($rows);
		//echo $rows;
		$this->output->set_output($rows);
	}
	
	
}
?>