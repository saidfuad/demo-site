<?php
class Runreport extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE); 

		$this->load->model('runreport_model','',TRUE);
		$this->load->model('allpoints_model','',TRUE);
		$this->load->model('home/home_model','',TRUE);
	}
	
	function loaddata($cmd='false')
	{	
		$data = $this->runreport_model->get_runreport($cmd); 
		$responce->page = $data['page'];
		$responce->query = $data['sql'];
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
	function export_pdf(){
	
		$filenames =  "RunReport".date("Ymdhis");
		$date_format = $this->session->userdata('date_format');  
		$time_format = $this->session->userdata('time_format');
		$data = array();
		$header = array('Start Time', 'Stop Time','Start Odo','Stop Odo','Running Time','Distance');
		$row = $this->runreport_model->get_runreport(); 
		//echo "<pre>";
		//print_r($row['result']);exit;
		//$data = $row->result();	
		
		//$data = $row->result;	
	
		$this->load->library('fpdf');
		$this->fpdf =new FPDF();				
		$this->fpdf->SetFont('Arial','B',8);
		$this->fpdf->SetMargins(5, 5);
        $this->fpdf->AddPage();
		$this->fpdf->Cell(200,6,$this->lang->line("Allpoints Report"),1,1,'C');
		$this->fpdf->Cell(25,6,'Device',1,0,'C');
		$this->fpdf->Cell(40,6,'Start Time',1,0,'C');
        $this->fpdf->Cell(40,6,'Stop Time',1,0,'C');
        $this->fpdf->Cell(25,6,'Start Odo',1,0,'C');
        $this->fpdf->Cell(25,6,'Stop Odo',1,0,'C');
		$this->fpdf->Cell(25,6,'Running Time',1,0,'C');
		$this->fpdf->Cell(20,6,'Distance',1,1,'C');
		//$this->fpdf->SetAutoPageBreak(true);
		$this->fpdf->SetFont('Arial','',8);
		        //data	
		foreach($row['result'] as $datarow)
        {   
				$add_date = $datarow->add_date;
			//$re_date = $datarow->re_date;
			//$this->fpdf->Cell(35,6,date("$date_format $time_format", strtotime($add_date)),1,0,'C');
		    $this->fpdf->Cell(25,6,$datarow->asset_name."(".$datarow->device_id.")",1,0,'C');
			$this->fpdf->Cell(40,6,".date($date_format.' '.$time_format,strtotime($datarow->ignition_on)).",1,0,'C');
			$this->fpdf->Cell(40,6,".date($date_format.' '.$time_format,strtotime($datarow->ignition_off)).",1,0,'C');
			$this->fpdf->Cell(25,6,$datarow->start_odometer,1,0,'C');
            $this->fpdf->Cell(25,6,$datarow->stop_odometer,1,0,'C'); 
			$this->fpdf->Cell(25,6,$datarow->duration,1,0,'C'); 
            $this->fpdf->Cell(20,6,$datarow->distance,1,1,'C'); 
        }
		$basepath = dirname(FCPATH);		
		$this->fpdf->Output($basepath.'/test/pdf/'.$filenames.'.pdf','F');
		redirect(base_url().'/pdf/'.$filenames.'.pdf');
	}
	function index()
	{
		
	/*	$this->load->helper('flexigrid');
		//ver lib
		
		
		$colModel['add_date'] = array('Date',200,TRUE,'center',1);
		$colModel['distance'] = array('Distance (KM)',200,TRUE,'center',0);
		$colModel['device'] = array('Device',200,TRUE,'center',0);
		

		$gridParams = array(
		'width' => 'auto',
		'height' => 350,
		'rp' => 15,
		'rpOptions' => '[10,15,20,25,40]',
		'pagestat' => 'Displaying: {from} to {to} of {total} items.',
		'blockOpacity' => 0.5,
		'title' => 'Distance Report',
		'showTableToggleBtn' => false,
		'useRp' => false,
		'usepager' => false
		);
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('Runreport_list',site_url("/reports/ajax/distance"),$colModel,'id','asc',$gridParams);
		
		$data['js_grid'] = $grid_js;
		*/
	//	$data['device'] = $this->allpoints_model->prepareCombo();
		
		$result = $this->home_model->get_group($this->session->userdata('user_id')); 
		$group = "<option value=''>Please Select</option>";
		foreach($result as $row) {
			$group .= "<option value='".$row->id."'>".$row->group_name."</option>";
		}
		$responce['group'] = $group;
		$this->load->view('runreport', $responce);
	}
}