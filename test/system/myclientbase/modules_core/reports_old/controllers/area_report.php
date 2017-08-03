<?php
class area_report extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('area_in_out_model','',TRUE);
		$this->load->model('home/home_model','',TRUE);
	}
	
	function index()
	{
		$result = $this->home_model->get_group($this->session->userdata('user_id')); 
		$group = "<option value=''>Please Select</option>";
		foreach($result as $row) {
			$group .= "<option value='".$row->id."'>".$row->group_name."</option>";
		}
		$responce['group'] = $group;
		
		$result = $this->area_in_out_model->get_area($this->session->userdata('user_id')); 
		$area = "<option value=''>Please Select</option>";
		foreach($result as $row) {
			$area .= "<option value='".$row->polyid	."'>".$row->polyname."</option>";
		}
		$responce['area'] = $area;
		$this->load->view('areareport', $responce);

	}
	
	function loadData($cmd='false'){
		$data = $this->area_in_out_model->getLogData($cmd);
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		// $responce->sql = $data['sql'];
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