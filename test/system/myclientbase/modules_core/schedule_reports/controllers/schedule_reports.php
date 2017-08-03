<?php (defined("BASEPATH")) OR exit("No direct script access allowed");

class schedule_reports extends Admin_Controller {
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('schedule_reports_model','',TRUE);
		$this->load->model('form_model','',TRUE);
		$this->load->model('home/home_model','',TRUE);
		$this->load->helper('uri');
	}
	function loadData($cmd='false'){		
		$responce = new stdClass();
		$data = $this->schedule_reports_model->getAllData($cmd); 
		$responce = new stdClass();
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
							
		$i=0;  
		foreach($data["result"] as $row) { 
			$row->daily_monthly_weekly = explode(",",$row->daily_monthly_weekly);
			$string = "";
			foreach($row->daily_monthly_weekly as $val){
				if($val == 1) $string .= $this->lang->line("schedule_reports_daily").",";
				if($val == 2) $string .= $this->lang->line("schedule_reports_monthly").",";
				if($val == 3) $string .= $this->lang->line("schedule_reports_weekly").",";				
			}
			$row->daily_monthly_weekly  = trim($string,",");
			
			$row->excel_pdf = explode(",",$row->excel_pdf);
			$string = "";
			foreach($row->excel_pdf as $val){
				if($val == 1) $string .= $this->lang->line("schedule_reports_excel").",";
				if($val == 2) $string .= $this->lang->line("schedule_reports_pdf").",";
			}
				$row->excel_pdf  = trim($string,",");
			$row->reports = explode(",",$row->reports);
			$string = "";
			foreach($row->reports as $val){
				if($val == 1) $string .= $this->lang->line("Stop Report").",";
				if($val == 2) $string .= $this->lang->line("Landmark Report").",";
				if($val == 3) $string .= $this->lang->line("Run Report").",";
				if($val == 4) $string .= $this->lang->line("Distance Report").",";
				if($val == 5) $string .= $this->lang->line("All Points").",";
				if($val == 6) $string .= $this->lang->line("Alerts").",";
				if($val == 7) $string .= $this->lang->line("Battery Status").",";
			}
			
		
			$row->reports  = trim($string,",");
			
			$responce->rows[$i] = $row;
			$i++; 
		} 
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	function deleteData(){
		//echo $this->schedule_reports_model->delete_schedule_reports(); 
		$this->output->set_output($this->schedule_reports_model->delete_schedule_reports());
	}
	function index(){
		$this->load->view( 'schedule_reports' );
	}
	/*function export(){
		$this->schedule_reports_model->getAllData(); 
	}*/
	function form() {
		if (!$this->form_model->validate()) {

			$this->load->helper('form');

			if (!$_POST AND uri_assoc('id')) {
				$this->form_model->prep_validation(uri_assoc('id'));
			}
			
			$this->load->view('form');

		}
		else {
			
			$formdata = $this->form_model->db_array();
			
			if(is_array($this->form_model->assets_ids)){
				$formdata['assets_ids']=implode(",",$formdata['assets_ids']);
			}
			
			if(is_array($this->form_model->daily_monthly_weekly)){
				$formdata['daily_monthly_weekly']=implode(",",$formdata['daily_monthly_weekly']);
			}
			if(is_array($this->form_model->reports)){
				$formdata['reports']=implode(",",$formdata['reports']);
			}
			
			if(is_array($this->form_model->excel_pdf)){
				$formdata['excel_pdf']=implode(",",$formdata['excel_pdf']);
			}						
			/*$formdata['assets_ids']=explode(",",$this->form_model->assets_ids);
			$formdata['daily_monthly_weekly']=explode(",",$this->form_model->daily_monthly_weekly);
			$formdata['excel_pdf']=explode(",",$this->form_model->excel_pdf);*/
			$formdata['add_date'] = date("Y-m-d H:i:s");
			$formdata['add_uid'] = $this->session->userdata('user_id');
			$formdata['status'] = 1;
			
			
			if(uri_assoc('id')){
				$this->db->where('id',uri_assoc('id'));
				$res = $this->db->get('schedule_reports');
				$this->form_model->save($formdata, uri_assoc('id'));
			}else{
				$this->schedule_reports_model->save($formdata, uri_assoc('id'));
			} 
		}
	} 
}
?>