<?php
class Tripreport extends Admin_Controller {
	

	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('tripreport_model','',TRUE);
		$this->load->model('allpoints_model','',TRUE);
	}
	function loadData(){
		$data = $this->tripreport_model->get_trip(); 
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
	function index()
	{
		
	/*	$this->load->helper('flexigrid');
		
		$colModel['add_date'] = array('Date',100,TRUE,'center',1);
		$colModel['start_point'] = array('Start Point',200,TRUE,'center',1);
		$colModel['end_point'] = array('End Point',200,TRUE,'center',1);
		$colModel['avrg_speed'] = array('Avg. Speed',100, TRUE,'center',1);
		$colModel['dist'] = array('Distance',100, TRUE, 'center',1);
		
	
		 * Aditional Parameters
		 
		$gridParams = array(
		'width' => 'auto',
		'height' => 350,
		'rp' => 15,
		'rpOptions' => '[10,15,20,25,40]',
		'pagestat' => 'Displaying: {from} to {to} of {total} items.',
		'blockOpacity' => 0.5,
		'title' => 'List',
		'showTableToggleBtn' => false,
		'useRp' => false,
		'usepager' => false
		);
		
	
		 * 0 - display name
		 * 1 - bclass
		 * 2 - onpress
		 
		//$buttons[] = array('Export','export','actionUser');
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('tripreport_list',site_url("/reports/ajax/trip"),$colModel,'id','asc',$gridParams);
		
		$data['js_grid'] = $grid_js;
		
		*/
		//$data['device'] = $this->allpoints_model->prepareCombo();
		
		$this->load->view('tripreport');
	}
	
}
?>