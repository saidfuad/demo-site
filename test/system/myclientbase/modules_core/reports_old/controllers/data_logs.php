<?php
class data_logs extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('data_logs_model','',TRUE);
		$this->load->model('home/home_model','',TRUE);
	}
	
	function index()
	{
		$rows = $this->home_model->get_group($this->session->userdata('user_id'));
		$group1 = "<option value=''>Please Select</option>";
		if(count($rows)) {
			foreach ($rows as $row) {
				$group1 .= "<option value='".$row->id."'>".$row->group_name."</option>";
			}
		}

		$data['group1'] = $group1;
		$data['devices'] = $this->data_logs_model->prepareCombo();
		$this->load->view('data_logs', $data);
	}
	function loadData(){
		
		$data = $this->data_logs_model->getAllData(); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		foreach($data['result'] as $row) {
			$responce->rows[$i] = $row;
			$i++;
		}
		$this->output->set_output(json_encode($responce));
	}
	function filter_assets()
	{
		$rows = $this->data_logs_model->groupAssets();
		foreach ($rows as $row) {
			$opt .= "<option value='".$row->device_id."'>".$row->assets_name. "(".$row->device_id.")</option>";
		}
		die($opt);
	}
	
}
?>