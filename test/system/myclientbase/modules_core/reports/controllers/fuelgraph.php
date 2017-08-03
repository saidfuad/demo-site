<?php
class Fuelgraph extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('fuelgraph_model','',TRUE);
		$this->load->model('allpoints_model','',TRUE);
	}	
	function index()
	{
		
		$rows = $this->fuelgraph_model->get_devices($this->session->userdata('user_id'));
		$d_assets_cmb="";
		
		if(count($rows)) {
			foreach ($rows as $row) {
				$d_assets_cmb.="<option value='".$row->id."'>";
				$d_assets_cmb.=$row->assets_name." (".$row->device_id.")";
				$d_assets_cmb.="</option>";
			}
		}
		$data['assets_fuel_opt']=$d_assets_cmb;
		$this->load->view('fuelgraph', $data);
	}
	function loadData(){
		$rows = $this->fuelgraph_model->get_fuel();
		$XAxis = array();
		$fuel = array();
		$fuelLimit=0;
		foreach ($rows as $row) {
            $XAxis[] = $row->add_date;
			$fuel[] = $row->fuel_liters;
			$fuelLimit=$row->max_fuel_limit;
        }
		
		//$data['XAxis'] = array("2010-01-08 14:49:28","2010-01-08 14:54:28","2010-01-08 14:59:28","2010-01-08 15:04:28","2010-01-08 15:09:28","2010-01-08 15:14:28","2010-01-08 15:19:28","2010-01-08 15:24:28","2010-01-08 15:29:28","2010-01-08 15:34:28","2010-01-08 15:39:28","2010-01-08 15:44:28");
		//$data['Fuel'] = array(50,30,40,50,40,80,85,90,95,100,120,20);
		$data['XAxis'] = $XAxis;
		$data['Fuel'] = $fuel;
		$data['Name'] = "fuel";
		$data['fuelLimit'] = 50;
		die(json_encode($data));
	}
}
?>