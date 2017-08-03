<?php (defined("BASEPATH")) OR exit("No direct script access allowed");

class ignition_motion_report extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model("ignition_motion_report_model","",TRUE);
		$this->load->model('home/home_model','',TRUE);
		$this->load->helper('uri');
	}
	function index(){
		$this->load->view( "ignition_motion_report" );
	}
	function loadData(){
		$data = $this->ignition_motion_report_model->getAllData();
		$responce->page = $data["page"];
		$responce->total = $data["total_pages"];
		$responce->records = $data["count"];		
		$i=0;  
		foreach($data["result"] as $row) {  
			$row->motion_hour = gmdate("H:i:s", $row->motion_hour);
			$row->ignition_hour = gmdate("H:i:s", $row->ignition_hour);			
			$responce->rows[$i] = $row;
			$i++; 
		} 
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	function export_pdf(){
		$filenames =  "Operating_hour".date("dmYhis");
		$date_format = $this->session->userdata('date_format');  
		$time_format = $this->session->userdata('time_format');
		$data = array();
		$header = array('Device Id', 'Date', 'Ignition Hour', 'Motion Hour');
		$row = $this->ignition_motion_report_model->getAllData();
		$data = $row->result();	
		$this->load->library('fpdf');
		$this->fpdf =new FPDF();				
		$this->fpdf->SetFont('Arial','B',10);
		$this->fpdf->SetMargins(5, 5);
        $this->fpdf->AddPage();
		$this->fpdf->Cell(200,6,'Ignition Motion Report',1,1,'C');
		
		$this->fpdf->Cell(80,6,'Assets Name(Device)',1,0,'C');
        $this->fpdf->Cell(40,6,'Date',1,0,'C');
        $this->fpdf->Cell(40,6,'Motion Hour',1,0,'C');
        $this->fpdf->Cell(40,6,'Ignition Hour',1,1,'C');
   
		$this->fpdf->SetFont('Arial','',10);
		        //data	
        foreach($data as $datarow)
        {      
			$re_date = $datarow->re_date;
            $this->fpdf->Cell(80,6,$datarow->device_name,1,0,'C');
            $this->fpdf->Cell(40,6,date("$date_format",strtotime($re_date)),1,0,'C');
            $this->fpdf->Cell(40,6,gmdate("H:i:s", $datarow->motion_hour),1,0,'C');
            $this->fpdf->Cell(40,6,gmdate("H:i:s", $datarow->ignition_hour),1,1,'C'); 
        }
		$basepath = dirname(FCPATH);		
		$this->fpdf->Output($basepath.'/htdocs/pdf/'.$filenames.'.pdf','F');
		redirect(base_url().'pdf/'.$filenames.'.pdf');
	}	
	
}
?>