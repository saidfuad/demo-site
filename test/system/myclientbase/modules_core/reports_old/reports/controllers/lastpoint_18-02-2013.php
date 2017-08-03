<?php
class Lastpoint extends Admin_Controller {
	
	/*function Country  ()
	{
		parent::Controller();	
		$this->load->helper('flexigrid');
		$this->load->helper('url');	
		$this->load->model('form_model');	
	}*/
	function __construct() {

		parent::__construct(TRUE);

		//$this->_post_handler();
		$this->load->model('lastpoint_model','',TRUE);
	}
	
	function index()
	{
		/*$this->load->model('home/home_model');
		$rows = $this->home_model->getAssetsStatus($this->session->userdata('user_id'));
		$running="";
		$parked="";
		$out_of_network="";
		$device_fault="";
		$total="";
		//die(print_r($rows));
		if(count($rows)){
			foreach ($rows as $row) {
				$running .= $row[0]['Running'];
				$parked .= $row[0]['Parked'];
				$out_of_network .= $row[0]['out_of_network'];
				$device_fault .= $row[0]['device_fault'];
				$total .= $row[0]['total'];
			}
		}*/
		//$this->form_model->icon_id = $iconOpt;
		$data['device'] = $this->lastpoint_model->prepareCombo();
		/*$data['running_1'] = $running;
		$data['parked_1'] = $parked;
		$data['out_of_network_1'] = $out_of_network;
		$data['device_fault_1'] = $device_fault;
		$data['total_1'] = $total;*/
		$this->load->view('lastpoint',$data);
	}
	function loadData($cmd='false'){
		
		$data = $this->lastpoint_model->get_lastpoints($cmd);
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		
		$i=0;
		foreach($data['result'] as $row) {
			$row->assets_name = $row->assets_name." (".$row->device_id.")";
			$row->received_time = ago($row->add_date) . ' ago';
			$responce->rows[$i] = $row;
			$i++;
			
		}
		$this->load->model('home/home_model');
		$rows = $this->home_model->getAssetsStatus($this->session->userdata('user_id'));
		$running="";
		$parked="";
		$out_of_network="";
		$device_fault="";
		$total="";
	
		if(count($rows)){
			foreach ($rows as $row) {
				$running .= $row[0]['Running'];
				$parked .= $row[0]['Parked'];
				$out_of_network .= $row[0]['out_of_network'];
				$device_fault .= $row[0]['device_fault'];
				$total .= $row[0]['total'];
			}
		}
		
		$responce->userdata->running =$running;
		$responce->userdata->parked =$parked;
		$responce->userdata->out_of_network =$out_of_network;
		$responce->userdata->device_fault =$device_fault;
		$responce->userdata->total =$total;

		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
}
?>