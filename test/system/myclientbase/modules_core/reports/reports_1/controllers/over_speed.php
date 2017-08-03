<?php
class over_speed extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('over_speed_model','',TRUE);
		$this->load->model('home/home_model','',TRUE);
	}
	
	function index()
	{
		$result = $this->home_model->get_group($this->session->userdata('user_id')); 
		$group = "<option value=''>".$this->lang->line("Please Select")."</option>";
		foreach($result as $row) {
			$group .= "<option value='".$row->id."'>".$row->group_name."</option>";
		}
		$responce['group'] = $group;
		
		$responce['area'] = $area;
		$this->load->view('over_speed', $responce);
	}
	function loadData($cmd='false'){
		$data = $this->over_speed_model->getAllData($cmd); 
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
	function view_map(){
		$device=uri_assoc('asset');
		
		$this->load->model('over_speed_model');
		$row = $this->over_speed_model->get_map_data();
		$data = array();
		$stp_html="";
		
		$data['lat'] = floatval($row->lati);
		$data['lng'] = floatval($row->longi);
		
		$stp_html .= "<table><tr><td>".$this->lang->line("Name")." : </td><td>".$device."</td></tr>";
		$stp_html .= "<tr><td>".$this->lang->line("Address")." : </td><td>".$row->address."</td></tr>";
		$stp_html .= "<tr><td>".$this->lang->line("Speed")." : </td><td>".$row->speed."</td></tr>";
		$stp_html .= "<tr><td>".$this->lang->line("Datetime")." : </td><td>".$row->add_date."</td></tr>";
		$stp_html .= "</table>";
		$data['html'] = $stp_html;

		$this->load->view('over_speed_view_file',$data);
	}
}
?>