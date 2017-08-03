<?php
class Stopreport extends Admin_Controller {
	
	/*function Country  ()
	{
		parent::Controller();	
		$this->load->helper('flexigrid');
		$this->load->helper('url');	
		$this->load->model('form_model');	
	}*/
	function __construct() {

		parent::__construct(TRUE);
		$this->load->model('stopreport_model','',TRUE);
		$this->load->model('allpoints_model','',TRUE);
		$this->load->model('home/home_model','',TRUE);
	}
	
	function loaddata($cmd='false')
	{
		$data = $this->stopreport_model->get_stopdata($cmd);
		$responce = new stdClass();
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		foreach($data['result'] as $row) {
			if($row->duration == ""){
				$to_time = strtotime($row->ignition_off);
				$from_time = strtotime($row->now);
				
				$minutes = round(abs($from_time - $to_time) / 60,2);
				
				$d = floor ($minutes / 1440);
				$h = floor (($minutes - $d * 1440) / 60);
				$m = $minutes - ($d * 1440) - ($h * 60);
				$stop_time = '';
				$hh = $d * 24;
				if($d > 0)
					$stop_time .= $d." Day ";
				if($h > 0)
					$stop_time .= $h + $hh." Hour ";
				if($m > 0)
					$stop_time .= intval($m)." Min";
				
				$row->duration = $stop_time;
			}else{
				$duration = explode(":", $row->duration);
				$stop_time = '';
				
				if($duration[0] > 0)
					$stop_time .= intval($duration[0])." Hour ";
				if($duration[1] > 0)
					$stop_time .= intval($duration[1])." Min";
				
				$row->duration = $stop_time;
			}
			$responce->rows[$i] = $row;
			$i++;
		}
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	function index()
	{
		
		/*$this->load->helper('flexigrid');
		
		$colModel['sr_no'] = array('',10,TRUE,'center',1);
		$colModel['start_time'] = array('Start Time',100,TRUE,'center',1);
		$colModel['end_time'] = array('End Time',100,TRUE,'center',1);
		$colModel['location'] = array('Location',300,TRUE,'center',1);
		$colModel['duration'] = array('Duration',100, TRUE,'center',1);
		$colModel['actions'] = array('View on Map',60, FALSE, 'right',0);

		$gridParams = array(
		'width' => 'auto',
		'height' => 350,
		'rp' => 15,
		'rpOptions' => '[10,15,20,25,40]',
		'pagestat' => 'Displaying: {from} to {to} of {total} items.',
		'blockOpacity' => 0.5,
		'title' => 'List',
		'showTableToggleBtn' => false,
		'useRp' => false,
		'usepager' => false
		);
		

		//$buttons[] = array('Export','export','actionUser');
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('stopreport_list',site_url("/reports/ajax/stop"),$colModel,'id','asc',$gridParams);
		
		$data['js_grid'] = $grid_js;
		*/
	//	$data['device'] = $this->allpoints_model->prepareCombo();
		
		$result = $this->home_model->get_group($this->session->userdata('user_id')); 
		$group = "<option value=''>Please Select</option>";
		foreach($result as $row) {
			$group .= "<option value='".$row->id."'>".$row->group_name."</option>";
		}
		$responce['group'] = $group;
		$this->load->view('stopreport', $responce);
	}
	function view_map(){
		$device=uri_assoc('asset');
		
		$this->load->model('stopreport_model');
		$rows = $this->stopreport_model->get_map_data();
		$data = array();
		$stp_html="";
		if(count($rows)) {
			foreach ($rows as $row) {
				$data['lat'] = floatval($row->lat);
				$data['lng'] = floatval($row->lng);
				
				$ignition_off_date = date($this->session->userdata('date_format'), strtotime($row->ignition_off));
				$ignition_off_time = date($this->session->userdata('time_format'), strtotime($row->ignition_off));
				
				$ignition_on_date = date($this->session->userdata('date_format'), strtotime($row->ignition_on));
				$ignition_on_time = date($this->session->userdata('time_format'), strtotime($row->ignition_on));
				
				$stp_html .= "<table><tr><td>Device : </td><td>".$device."</td></tr>";
				$stp_html .= "<tr><td>Address : </td><td>".$row->address."</td></tr>";
				$stp_html .= "<tr><td>Stop From : </td><td>".$ignition_off_date.' '.$ignition_off_time."</td></tr>";
				$stp_html .= "<tr><td>Stop To : </td><td>".$ignition_on_date.' '.$ignition_on_time."</td></tr>";
				$stp_html .= "<tr><td>Stop Duration : </td><td>".$row->duration."</td></tr>";
				$stp_html .= "<tr><td>Latitude : </td><td>".$row->lat."</td></tr>";
				$stp_html .= "<tr><td>Longitude : </td><td>".$row->lng."</td></tr>";
				$stp_html .= "</table>";
				$data['html'] = $stp_html;
			} 
		}
		else{
			//die("No data Found");
			$this->output->set_output("No data Found");
		}
		$this->load->view('stopreport_view_file',$data);
	}
	
}
?>