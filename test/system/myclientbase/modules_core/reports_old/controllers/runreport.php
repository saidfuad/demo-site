<?php
class Runreport extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE); 

		$this->load->model('runreport_model','',TRUE);
		$this->load->model('allpoints_model','',TRUE);
		$this->load->model('home/home_model','',TRUE);
	}
	
	function loaddata($cmd='false')
	{	
		$data = $this->runreport_model->get_runreport($cmd); 
		$responce->page = $data['page'];
		$responce->query = $data['sql'];
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
		//ver lib
		
		
		$colModel['add_date'] = array('Date',200,TRUE,'center',1);
		$colModel['distance'] = array('Distance (KM)',200,TRUE,'center',0);
		$colModel['device'] = array('Device',200,TRUE,'center',0);
		

		$gridParams = array(
		'width' => 'auto',
		'height' => 350,
		'rp' => 15,
		'rpOptions' => '[10,15,20,25,40]',
		'pagestat' => 'Displaying: {from} to {to} of {total} items.',
		'blockOpacity' => 0.5,
		'title' => 'Distance Report',
		'showTableToggleBtn' => false,
		'useRp' => false,
		'usepager' => false
		);
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('Runreport_list',site_url("/reports/ajax/distance"),$colModel,'id','asc',$gridParams);
		
		$data['js_grid'] = $grid_js;
		*/
	//	$data['device'] = $this->allpoints_model->prepareCombo();
		
		$result = $this->home_model->get_group($this->session->userdata('user_id')); 
		$group = "<option value=''>Please Select</option>";
		foreach($result as $row) {
			$group .= "<option value='".$row->id."'>".$row->group_name."</option>";
		}
		$responce['group'] = $group;
		$this->load->view('runreport', $responce);
	}
}