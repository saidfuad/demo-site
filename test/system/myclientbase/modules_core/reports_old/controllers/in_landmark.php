<?php
class In_landmark extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);
		$this->load->model('in_landmark_model','',TRUE);
	}
	
	function loaddata($cmd='false')
	{
		$data = $this->in_landmark_model->get_data($cmd);
		$responce = new stdClass();
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
		$option = '';
		$rows = $this->in_landmark_model->get_landmark();
		foreach($rows as $row) {
			$option .= "<option value='".$row->id."'>".$row->name."</option>";
			$i++;
		}
		$data['landmark'] = $option;
		$this->load->view('in_landmark', $data);
	}
}
?>