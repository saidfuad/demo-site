<?php
class Speedgraph extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('speedgraph_model','',TRUE);
		$this->load->model('allpoints_model','',TRUE);
	}	
	function index()
	{
		
		$data['device'] = $this->allpoints_model->prepareCombo();
		$this->load->view('speedgraph', $data);
	}
	/*function loadData(){
		$device=trim($this->input->post('device'),",");
		$rows = $this->speedgraph_model->get_speed();
		$XAxis = array();
		$Speed = array();
		$Devices = array();
		$speedLimit=0;
		
		for($i=0;$i<count($rows);$i++){
			$XAxis[$i] = explode(",",$rows[$i]['add_date']);
			$Speed[$i] = explode(",",$rows[$i]['speed']);
			$Devices[$i] =$rows[$i]['assets_name'];
		}
		$AllDataAtOne = array();
		
		for($i=0;$i<count($XAxis);$i++){
		$AllDataAtOne_1=array();
			for($j=0;$j<count($XAxis[$i]);$j++){
					if($XAxis[$i][$j]!="2012"){
						$speed1=intval($Speed[$i][$j]);
						$date=date('Y-m-d H:i:s',strtotime($XAxis[$i][$j]));
						$AllDataAtOne_1[]=array($date,$speed1);
					}
			}
			$AllDataAtOne[]=$AllDataAtOne_1;
		}
		
		//$data['Speed'] = $Speed;
		//$data['Speed'] = $Speed;
		$data['Speed'] = $AllDataAtOne;
		$data['YSpeeds'] = $Speed;
		$data['XDates'] = $XAxis;
		$data['Name'] = "Speed";
		$data['SpeedLimit'] = $speedLimit;
		$data['Devices'] = $Devices;
		echo json_encode($data);
		print_r($rows);
		die();
	}*/
	function loadData(){
		$rows = $this->speedgraph_model->get_speed();
		$XAxis = array();
		$Speed = array();
		$Devices = array();
		$speedLimit=0;
		
		for($i=0;$i<count($rows);$i++){
		$XAxis[$rows[$i]['assets_name']][]=array($rows[$i]['add_date'], intval($rows[$i]['speed']),$rows[$i]['assets_name']);
		
			
			//$XAxis[$i] = explode(",",$rows[$i]['add_date']);
			//$Speed[$i] = explode(",",$rows[$i]['speed']);
			//$Devices[$i] =$rows[$i]['assets_name'];
		}
		foreach($XAxis as $key=>$val){
			$Speed[]=$XAxis[$key];
			$Devices[]=$key;
		}
		$data['Devices']=$Devices;
		$data['Speed']=$Speed;
		
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
		/*
		$AllDataAtOne = array();
		
		for($i=0;$i<count($XAxis);$i++){
		$AllDataAtOne_1=array();
			for($j=0;$j<count($XAxis[$i]);$j++){
					if($XAxis[$i][$j]!="2012"){
						$speed1=intval($Speed[$i][$j]);
						$date=date('Y-m-d H:i:s',strtotime($XAxis[$i][$j]));
						$AllDataAtOne_1[]=array($date,$speed1);
					}
			}
			$AllDataAtOne[]=$AllDataAtOne_1;
		}
		
		//$data['Speed'] = $Speed;
		//$data['Speed'] = $Speed;
		$data['Speed'] = $AllDataAtOne;
		$data['YSpeeds'] = $Speed;
		$data['XDates'] = $XAxis;
		$data['Name'] = "Speed";
		$data['SpeedLimit'] = $speedLimit;
		$data['Devices'] = $Devices;
		echo json_encode($data);
		print_r($rows);
		die();*/
	}
}
?>