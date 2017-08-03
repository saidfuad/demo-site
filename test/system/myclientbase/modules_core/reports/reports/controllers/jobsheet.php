<?php
class jobsheet extends Admin_Controller {
	
	/*function Country  ()
	{
		parent::Controller();	
		$this->load->helper('flexigrid');
		$this->load->helper('url');	
		$this->load->model('form_model');	
	}*/
	function __construct() {

		parent::__construct(TRUE);
		$this->load->model('jobsheet_model','',TRUE);
		$this->load->model('allpoints_model','',TRUE);
	}
	function index()
	{
		//$data['device'] = $this->allpoints_model->prepareCombo();
		$this->load->view('jobsheet');
	}
	function loaddata($cmd='false')
	{
		if($this->input->get_post('type') == "2") 
			$cmd = "export";
		$data = $this->jobsheet_model->get_data($cmd);
		
		$this->output->set_output(($data));
	}	
}
?>