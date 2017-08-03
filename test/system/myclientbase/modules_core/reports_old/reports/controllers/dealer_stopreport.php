<?php
class Dealer_stopreport extends Admin_Controller {
	
	/*function Country  ()
	{
		parent::Controller();	
		$this->load->helper('flexigrid');
		$this->load->helper('url');	
		$this->load->model('form_model');	
	}*/
	function __construct() {

		parent::__construct(TRUE);
		$this->load->model('dealer_stopreport_model','',TRUE);
		$this->load->model('allpoints_model','',TRUE);
	}
	
	function loaddata($cmd='false')
	{
		$data = $this->dealer_stopreport_model->get_stopdata($cmd);
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
		$this->load->view('dealer_stopreport');
	}
	function view_map(){
		$device=uri_assoc('asset');
		$area=uri_assoc('area');
		$this->load->model('dealer_stopreport_model');
		$rows = $this->dealer_stopreport_model->get_map_data();
		$data = array();
		$plyId = array();
		$plyDev = array();
		$plyLat = array();
		$plyLng = array();
		$plyName = array();
		$plyColor = array();
		$stp_html="";
		if(count($rows)){
			foreach ($rows as $row){
				$data['lat'] = floatval($row->lat);
				$data['lng'] = floatval($row->lng);
				
				$ignition_off_date = date($this->session->userdata('date_format'), strtotime($row->ignition_off));
				$ignition_off_time = date($this->session->userdata('time_format'), strtotime($row->ignition_off));
				
				$ignition_on_date = date($this->session->userdata('date_format'), strtotime($row->ignition_on));
				$ignition_on_time = date($this->session->userdata('time_format'), strtotime($row->ignition_on));
				$stp_html .="<div>";
				$stp_html .="<div><table>";
				$stp_html .= "<tr><td>".$this->lang->line("Device")." : "."</td><td>".$device. "</td></tr>";
				$stp_html .= "<tr><td>".$this->lang->line("Area").' : '."</td><td>".$area. "</td></tr>";
				$stp_html .= "<tr><td>".$this->lang->line("Address").' : '."</td><td>".$row->address. "</td></tr>";
				$stp_html .= "<tr><td>".$this->lang->line("Stop From").' : '."</td><td>".$ignition_off_date.' '.$ignition_off_time. "</td></tr>";
				$stp_html .= "<tr><td>".$this->lang->line("Stop To").' : '."</td><td>".$ignition_on_date.' '.$ignition_on_time. "</td></tr>";
				$stp_html .= "<tr><td>".$this->lang->line("Stop Duration").' : '."</td><td>".$row->duration. "</td></tr></table>";
				$stp_html .= "</div>";
				$stp_html .= "</div>";
				$data['html'] = $stp_html;
			}
			if($area!=""){
			$rows_area = $this->dealer_stopreport_model->get_map_data_area($area);
			if(count($rows_area)){
				//$plyRows = $this->device_model->get_poly_home();
				foreach ($rows_area as $row) {
					
					$plyId[] = $row->polyid;
					$plyLat[$row->polyid][] = $row->lat;
					$plyLng[$row->polyid][] = $row->lng;
					$plyName[$row->polyid][] = $row->polyname;
					$plyDev[$row->polyid][] = $device;
					$plyColor[$row->polyid] = ($row->color != "") ? $row->color : "#ff0000";
				}		
				if(count($plyId) > 0){
					$plyId = array_unique($plyId);
					foreach($plyId as $pid){
						if(count($plyDev[$pid]) > 0){
							$plyDev[$pid] = array_unique($plyDev[$pid]);
						}
					}
				}				
			}
			}
		}
		else{
			//die("No data Found");
			$this->output->set_output($this->lang->line("No data Found"));
			die();
		}
		$data['pplyId'] = $plyId;
		$data['pplyDev'] = $plyDev;
		$data['pplyLat'] = $plyLat;
		$data['pplyLng'] = $plyLng;
		$data['pplyName'] = $plyName;
		$data['pplyColor'] = $plyColor;
		$this->load->view('dealer_stopreport_view_file',$data);
	}
	
}
?>