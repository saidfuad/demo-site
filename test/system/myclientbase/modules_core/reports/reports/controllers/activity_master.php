<?php
class activity_master extends Admin_Controller {
	
	/*function Country  ()
	{
		parent::Controller();	
		$this->load->helper('flexigrid');
		$this->load->helper('url');	
		$this->load->model('form_model');	
	}*/
	function __construct() {

		parent::__construct(TRUE);
		$this->load->model('activity_master_model','',TRUE);
		$this->load->model('allpoints_model','',TRUE);
	}
	function index()
	{
		//$data['device'] = $this->allpoints_model->prepareCombo();
		$this->load->view('activity_master');
	}
	function loaddata($cmd='false')
	{
		if($this->input->get_post('type') == "2") 
			$cmd = "export";
		$data = $this->activity_master_model->get_data($cmd);
		
		$this->output->set_output(($data));
	}
	function view_map(){
		$device=uri_assoc('asset');
		
		$this->load->model('activity_master_model');
		$rows = $this->activity_master_model->get_map_data();
		$data = array();
		$stp_html="";
		if(count($rows)) {
			foreach ($rows as $row) {
				$data['latitude'] = floatval($row->latitude);
				$data['longitude'] = floatval($row->longitude);
				
				$stp_html .= "<table><tr><td>Device : </td><td>".$device."</td></tr>";
				$stp_html .= "<tr><td>Address : </td><td>".$row->clocation."</td></tr>";
				$stp_html .= "<tr><td>Latitude : </td><td>".$row->latitude."</td></tr>";
				$stp_html .= "<tr><td>Longitude : </td><td>".$row->longitude."</td></tr>";
				$stp_html .= "</table>";
				$data['html'] = $stp_html;
			} 
		}
		else{
			//die("No data Found");
			$this->output->set_output("No data Found");
		}
		$this->load->view('activity_master_view_file',$data);
	}
	
}
?>