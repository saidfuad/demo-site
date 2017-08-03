<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Rfid extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('rfid_model','',TRUE);
		$this->load->model('form_model','',TRUE);
	}
	function index()
	{
		$this->load->view('rfid');
	}
	
	function loadData(){
		
		$data = $this->rfid_model->getAllData(); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		foreach($data['result'] as $row) {
			$responce->rows[$i] = $row;
			$i++;
		}
		echo json_encode($responce);
	}
	function deleteData(){
		
		echo $this->rfid_model->delete_rfid(); 
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
		$colModel['rfid'] = array('Card No',150,TRUE,'center',1);
		$colModel['asset_id'] = array('Asset',100,TRUE,'center',1);
		$colModel['person'] = array('Person',100,TRUE,'center',1);
		$colModel['inform_mobile'] = array('SMS To',100, TRUE,'center',1);
		$colModel['inform_email'] = array('Email To',100, TRUE,'center',1);
		$colModel['send_sms'] = array('SMS',40, TRUE,'center',1);
		$colModel['send_email'] = array('Email', 40, TRUE,'center',1);
		
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
		'title' => 'My RFID List',
		'showTableToggleBtn' => false
		);
		
		$buttons[] = array('Add','add','actionRfid');
		$buttons[] = array('Edit','edit','actionRfid');
		$buttons[] = array('Delete','delete','actionRfid');
		$buttons[] = array('separator');
		$buttons[] = array('Select All','add','actionRfid');
		$buttons[] = array('DeSelect All','delete','actionRfid');
		$buttons[] = array('separator');
		
		$grid_js = build_grid_js('allrfid_list',site_url("/rfid/ajax/allrfid"),$colModel,'id','asc',$gridParams,$buttons);
		
		$data['headerjs'] = '';
		$data['js_grid'] = $grid_js;
//		print_r($data);
		$this->load->view('rfid',$data);
	}
	
	function form() {

		if (!$this->form_model->validate()) {

			$this->load->helper('form');

			if (!$_POST AND uri_assoc('id')) {

				$this->form_model->prep_validation(uri_assoc('id'));

			}
			
			$assets_list = $this->rfid_model->prepare_assets();
			$assetOpt = '';
			foreach ($assets_list as $asset) {
				$assetOpt .= '<option value="'.$asset->id.'"';
				if($asset->id == $this->form_model->asset_id)
					$assetOpt .= ' selected="selected"';
				$assetOpt .= '>'.$asset->assets_name.'</option>';
			}
			$this->form_model->asset_id = $assetOpt;
			
			$landmark_list = $this->rfid_model->prepare_landmark();
			$landmarkOpt = '';
			foreach ($landmark_list as $landmark) {
				$landmarkOpt .= '<option value="'.$landmark->id.'"';
				if($landmark->id == $this->form_model->landmark_id)
					$landmarkOpt .= ' selected="selected"';
				$landmarkOpt .= '>'.$landmark->name.'</option>';
			}
			$this->form_model->landmark_id = $landmarkOpt;
			
			$this->form_model->user_id = $this->session->userdata('user_id');
			
			$this->load->view('form');

		}

		else {
			$formdata = $this->form_model->db_array();
			
			$formdata['add_date'] = gmdate('Y-m-d H:i:s');
			$formdata['add_uid'] = $this->session->userdata('user_id');
			if(uri_assoc('id')){
				$this->form_model->save($formdata, uri_assoc('id'));
			}else{
				$this->rfid_model->save($formdata, uri_assoc('id'));
			}
			
		}

	}
	function export(){
		
		$this->load->plugin('to_excel'); 
		$this->form_model->export();
	}
	
	function getIco()
	{
		$rows = $this->rfid_model->prepare_icon();
		$iconDiv = "<div style='height: 200px; margin-top: 10px; margin-bottom: 10px; margin-left: 10px; overflow: auto; width: 97%;'>";
		foreach ($rows as $row) {
			$iconDiv .= "<div style='border: 1px solid rgb(197, 219, 236); white-space: nowrap; margin: 2px; display: inline-block;cursor:pointer' id='getIcon_div' class='imageSection' ";
			$iconDiv .=" onClick='selectedMarker(\"".$row->icon_path."\",\"".$row->icon_name."\",".$row->id.")'>";
			$iconDiv .="<img src='".$this->config->item('base_url')."assets/marker-images/".$row->icon_path."' height='30' width='20' title='".$row->icon_name."' rel='".$row->id."'></div>";
			
		}
		$iconDiv.="</div>";
		echo $iconDiv;
	}
}
?>