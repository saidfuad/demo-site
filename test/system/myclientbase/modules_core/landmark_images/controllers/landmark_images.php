<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Landmark_images extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);

		$this->load->model('landmark_images_model','',TRUE);
		$this->load->model('form_model','',TRUE);
		//$this->load->library('image_lib');
	}
	function index()
	{
		$this->load->view('landmark_images');
	}
	function loadData(){
		$data = $this->landmark_images_model->getAllData();
		$responce = new stdClass();
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		foreach($data['result'] as $row){
			$responce->rows[$i] = $row;
			$i++;
		}
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	function deleteData(){		
		//echo $this->landmark_images_model->delete_assets();
		$this->output->set_output($this->landmark_images_model->delete_assets());
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
		
		$grid_js = build_grid_js('allassets_list',site_url("/assets/ajax/allAssets"),$colModel,'id','asc',$gridParams,$buttons);
		
		$data['headerjs'] = '';
		$data['js_grid'] = $grid_js;
//		print_r($data);
		$this->load->view('assets',$data);
	}
	function form() {

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '100';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';

		$this->load->library('upload', $config);
		
		if (!$this->form_model->validate()) {

			$this->load->helper('form');

			if (!$_POST AND uri_assoc('id')) {

				$this->form_model->prep_validation(uri_assoc('id'));

			}
			$this->load->view('form');
		}
		else {
			$formdata = $this->form_model->db_array();			
			$formdata['add_date'] = gmdate('Y-m-d H:i:s');
			$formdata['add_uid'] = $this->session->userdata('user_id');
			$formdata['status'] = 1;
			if(!uri_assoc('id')){
				$this->form_model->save($formdata, uri_assoc('id'));
			}else{
				$this->landmark_images_model->save($formdata, uri_assoc('id'));
			}			
		}

	}
	function export(){
		
		$this->load->plugin('to_excel'); 
		$this->form_model->export();
	}
	function getIco()
	{
		$rows = $this->landmark_images_model->prepare_icon();
		$iconDiv = "<div style='height: 200px; margin-top: 10px; margin-bottom: 10px; margin-left: 10px; overflow: auto; width: 97%;'>";
		foreach ($rows as $row) {
			$iconDiv .= "<div style='border: 1px solid rgb(197, 219, 236); white-space: nowrap; margin: 2px; display: inline-block;cursor:pointer' id='getIcon_div' class='imageSection' ";
			$iconDiv .=" onClick='selectedMarker(\"".$row->icon_path."\",\"".$row->icon_name."\",".$row->id.")'>";
			$iconDiv .="<img src='".base_url()."/assets/marker-images/".$row->icon_path."' height='30' width='20' title='".$row->icon_name."' rel='".$row->id."'></div>";
			
			/*<option title="'.$this->config->item('base_url').'assets/marker-images/'.$row->icon_path.'" value="'.$row->id.'"';
			//if($row->id == $this->form_model->icon_id)
			//	$iconOpt .= ' selected="selected"';
			$iconDiv .= '>'.$row->icon_name.'</option>';*/
		}
		$iconDiv.="</div>";
		//echo $iconDiv;
		$this->output->set_output($iconDiv);
	}
	function asset()
	{
		$this->load->model('landmark_images_model');
		$rows = $this->landmark_images_model->get_locations();
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
	function newPoint()
	{
		$this->load->model('landmark_images_model');
		$rows = $this->landmark_images_model->get_new_locations();
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
		$this->output->set_output(json_encode($data));
		//die(json_encode($data));
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

	function assets_category_data()
	{
		//echo $query=$this->landmark_images_model->get_cat();	
		$this->output->set_output($query=$this->landmark_images_model->get_cat());
	}
	function assets_category_data_post()
	{
		//echo $query=$this->landmark_images_model->get_cat_post();	
		$this->output->set_output($query=$this->landmark_images_model->get_cat_post());
	}	
}
?>