<?php
class zone_report extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('zone_in_out_model','',TRUE);
		$this->load->model('home/home_model','',TRUE);
	}
	
	function index()
	{
		$result = $this->home_model->get_group($this->session->userdata('user_id')); 
		$group = "";
		foreach($result as $row) {
			$group .= "<option value='".$row->id."'>".$row->group_name."</option>";
		}
		$responce['group'] = $group;
		
		$result = $this->zone_in_out_model->get_zone($this->session->userdata('user_id')); 
		$zone = "";
		foreach($result as $row) {
			$zone .= "<option value='".$row->polyid	."'>".$row->polyname."</option>";
		}
		$responce['zone'] = $zone;
		$this->load->view('zonereport', $responce);

	}
	
	function loadData($cmd='false'){
		$data = $this->zone_in_out_model->getLogData($cmd);
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
}
?>