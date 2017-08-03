<?php

class Distancegraph extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('allpoints_model','',TRUE);
		$this->load->model('home/home_model','',TRUE);
	}
	
	function index()
	{
		//$data['device'] = $this->allpoints_model->prepareCombo();
		$result = $this->home_model->get_group($this->session->userdata('user_id')); 
		$group = "";
		foreach($result as $row) {
			$group .= "<option value='".$row->id."'>".$row->group_name."</option>";
		}
		$responce['group'] = $group;
		$this->load->view('distancegraph', $responce);
	}
	
	/*function loadData(){
		
		$this->load->model('reports/distancereport_model');
		$records = $this->distancereport_model->get_distancegraph();
		$max_distance=0;
		$dates = array();
		$assets = array();
		$device = array();
		$devices = array();
		$record_items = array();
		$distance = array();
		$i=0;
	//	print_r($records);
		//die();
		foreach ($records as $row) //fill all dates
		{
			//$date = date('d.m.Y',strtotime($row->add_date));
			$date = $row->add_date;
			$asset = explode(",",$row->assets_id);
			if(!in_array($date,$dates)){
				$dates[]=date("d.m.Y",strtotime($date));
			}
			for($i=0;$i<count($asset);$i++){
				if(!in_array($asset[$i],$assets)){
					$assets[]=$asset[$i];
				}
			}
			$distance[$date] = $row->distance;
			$dev = explode(",",$row->devices);
			
			for($x=0;$x<count($dev);$x++){
				if(!in_array($dev[$x],$devices)){
					$devices[]=$dev[$x];
				}
			}
		}

		$allArray = array();
		foreach ($records as $row)
		{
			$DTS=explode(",",$row->assets_id);
			for($i=0;$i<count($assets);$i++){
				if(!in_array($assets[$i],$DTS)){
					$DTS[$assets[$i]]=$assets[$i];
					$row->assets_id.=",".$assets[$i];
					$row->distance.=",0";
				}
			}
			$allArray[]=$row;
		}
		$DataArrays = array();
		foreach($allArray as $row){
			$assets_ids=explode(",",$row->assets_id);
			for($i=0;$i<count();$i++){
				$DataArrays[$assets_ids[$i]][]=array();
			}			
		}
		foreach($allArray as $row){
			$assets_ids=explode(",",$row->assets_id);
			$distance=explode(",",$row->distance);
			for($i=0;$i<count($assets_ids);$i++){
				$DataArrays[$assets_ids[$i]][]=floatval($distance[$i]);
				if($max_distance < floatval($distance[$i])){
					$max_distance=intval($distance[$i]);
				}								
			}
		}
		$x_max=intval($max_distance%25);
		$x_max=intval($max_distance%25);
		$max_distance=($max_distance-$x_max)+100;
		sort($DataArrays);
		$data['distance']=$DataArrays;
		$data['max_distance']=$max_distance;
		$data['dates']=$dates;
		$data['assets']=$assets;		
		$data['device']=$devices;		
		
		/*
		foreach ($records as $row)
		{
			
			$date = date('d.m.Y',strtotime($row->add_date));
			$distance[$date] = $row->distance;
			$device = $row->assets_id;
		}*/
		/*$total = 0;

		$XAxis = array();
		$Speed = array();
		$x_axis = array();
		$y_axis = array();
		foreach ($distance as $date => $value) {
            $x_axis[] = $date;
			$y_axis[] = round($value, 2);
        }*/
/*		
		$x_axis = implode(",",array_keys($distance));
		$y_axis = implode(",",array_values($distance));
*/		
		//$data["x_axis"] = $x_axis;
		//$data["y_axis"] = $y_axis;
		
		//$values = array_values($distance);
		
		/*if(count($distance)) {
		//	$data["x_max"] = ceil(max($y_axis)) + 100;
			//$data["x_max"] = 1500;
		}*-/
		
		die(json_encode($data));
	}*/
	function loadData(){
		
		$this->load->model('reports/distancereport_model');
		$records = $this->distancereport_model->get_distancegraph();
		$devices= array();
		$dates = array();
		$fuelArr = array();
		$max_distance=0;
		foreach($records as $rows){		
			$rows->add_date=date("d.m.Y",strtotime($rows->add_date));
			if(!in_array($rows->devices,$devices)){
				$devices[]=$rows->devices;
			}
			if(!in_array($rows->add_date,$dates)){
				$dates[]=$rows->add_date;
			}
			if($max_distance < floatval($rows->distance)){
				$max_distance=intval($rows->distance);
			}
		}
		$x_max=intval($max_distance%25);
		$x_max=intval($max_distance%25);
		$max_distance=($max_distance-$x_max)+400;
		$addDates=array();
		$v=0;
		$lbl=array();
		for($j=0;$j<count($dates);$j++){
			for($i=0;$i<count($devices);$i++){
				$v=0;
				foreach($records as $rows){
					if($rows->add_date==$dates[$j] && $rows->devices==$devices[$i]){
						$addDates[$rows->devices][]=$rows->distance;
						
						$fuel_used = 0;
						$label = "<b>".$rows->distance." KM";
						
						if($rows->fuel_in_out_sensor == 1){
							$fuel_used = $rows->fuel_used;
							$label .= "<br><span style='color:red'>".$fuel_used." Ltr</span><span style='color:green'><br>Avg:".round($rows->distance/$fuel_used, 2)." Kmpl</span>";
						}
						$label .= "<b>";
						if($rows->distance == 0 && $fuel_used == 0){
							$label = "";
						}
						//$lblArr[$rows->devices][] = $label;
						$lbl[]=$label;
						$v=1;		
					}
				}
				if($v==0){
					$addDates[$devices[$i]][]=0;
				}				
			}
		}
		$Line=array();
		
		for($x=0;$x<count($devices);$x++){
			$Line[]=$addDates[$devices[$x]];
		}
		$data['label']=$lbl;
		$data['distance']=$Line;
		$data['assets']=$devices;
		$data['device']=$devices;
		$data['dates']=$dates;
		$data['max_distance']=$max_distance;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
	}
}
?>