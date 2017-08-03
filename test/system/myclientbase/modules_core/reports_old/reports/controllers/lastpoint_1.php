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
		$this->load->model('home/home_model');
		$stopArr = array();
		$reports = $_REQUEST['report'];
		
		foreach($reports as $report) {
			$rptsub = substr($report, 0, 2);
			
			if($rptsub == "g-"){
				$group = str_replace($rptsub, "", $report);
			}
			
			if($rptsub == "u-"){
				$user = str_replace($rptsub, "", $report);
			}
			
			if($rptsub == "a-"){
				$us_ar = str_replace($rptsub, "", $report);
			}
			
			if($rptsub == "l-"){
				$us_ln = str_replace($rptsub, "", $report);
			}
			
			if($rptsub == "o-"){
				$us_ow = str_replace($rptsub, "", $report);
			}
			
			if($rptsub == "d-"){
				$us_dv = str_replace($rptsub, "", $report);
			}
		}
		if($user == '') {
			$user = $this->session->userdata('user_id');
		}
		
		if($us_ar != ""){
			$this->db->select("polyname", FALSE);
			$this->db->where('polyid', $us_ar);
			$this->db->limit(1);
			$query = $this->db->get('areas');			
			$rows = $query->result();
			$us_area = '';
			foreach ($rows as $key => $row) {
				$us_area = $row->polyname;
			}

			if($us_area!="")
				$gsub .= " AND lm.current_area = '".addslashes($us_area)."'";
		}
		
		if($us_ln != ""){
			$this->db->select("name", FALSE);
			$this->db->where('id', $us_ln);
			$this->db->limit(1);
			$query = $this->db->get('landmark');			
			$rows = $query->result();
			$us_land = '';
			foreach ($rows as $row) {
				$us_land = $row->name;
			}
			if($us_land!="")
				$gsub .= " AND lm.current_landmark = '".addslashes($us_land)."'";
		}
		
		if($group != ""){
			$this->db->select("assets", FALSE);
			$this->db->where('id', $group);
			$this->db->limit(1);
			$query = $this->db->get('group_master');			
			$rows = $query->result();
			foreach ($rows as $row) {
				$assets = $row->assets;
			}
			if($assets!="")
				$gsub .= " AND am.id in($assets)";
			else
				$gsub .= " AND am.id in(-1)";
		}
		
		if($us_ow != '') {
			$gsub .= " AND am.assets_owner = '".intval($us_ow)."'";
		}
		
		if($us_dv != '') {
			$gsub .= " AND am.assets_division = '".intval($us_dv)."'";
		}
		
		$rows = $this->home_model->stop_duration($user);

		if(count($rows) > 0) {
			foreach ($rows as $row) {
				$minutes = $row->stop_from;
				
				$d = floor ($minutes / 1440);
				$h = floor (($minutes - $d * 1440) / 60);
				$m = $minutes - ($d * 1440) - ($h * 60);
				$stop_time = '';
				if($d > 0)
					$stop_time .= $d." Day ";
				if($h > 0)
					$stop_time .= $h." Hour ";
				if($m > 0)
					$stop_time .= intval($m)." Min";
				
				$stopArr[$row->device_id] = $stop_time;
			}
		}
		$data = $this->lastpoint_model->get_lastpoints($cmd);
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		
		$i=0;
		foreach($data['result'] as $row) {
			if (array_key_exists($row->assets_id, $stopArr)) {
				$row->stop_from = $stopArr[$row->assets_id];
			}
			$row->assets_name = $row->assets_name." (".$row->device_id.")";
			$row->received_time = ago($row->add_date) . ' ago';
			$responce->rows[$i] = $row;
			$i++;
		}
		$this->load->model('home/home_model');
		$rows = $this->home_model->getAssetsStatus($user, $gsub);
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
	function deleteData(){		
		$this->output->set_output($this->lastpoint_model->delete_lastpoint());
	}
}
?>