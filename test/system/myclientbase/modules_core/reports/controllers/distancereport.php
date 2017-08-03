<?php
class Distancereport extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE); 

		$this->load->model('distancereport_model','',TRUE);
		$this->load->model('allpoints_model','',TRUE);
		$this->load->model('home/home_model','',TRUE);
	}
	
	function loaddata($cmd='false')
	{	
		$data = $this->distancereport_model->get_distancereport($cmd); 
		$responce->page = $data['page'];
		$responce->query = $data['sql'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		foreach($data['result'] as $row) {
			$stop_time = 0;
			$StopRow = $this->distancereport_model->get_stop_time($row->aId, date('Y-m-d', strtotime($row->add_date))); 
			$stop_time = intval(($StopRow->stop_time)/60);
			
					
			$StopRow_1 = $this->distancereport_model->get_stop_time_1($row->aId, date('Y-m-d', strtotime($row->add_date)));
			if($StopRow_1->stop_time > 0)
			
				$stop_time += intval(($StopRow_1->stop_time)/60);
			
			$StopRow_2 = $this->distancereport_model->get_stop_time_2($row->aId, date('Y-m-d', strtotime($row->add_date))); 
			if($StopRow_2->stop_time > 0)			
				$stop_time += intval(($StopRow_2->stop_time)/60);
			
			if(date('Y-m-d', strtotime($row->add_date)) == date('Y-m-d')){
				$total_minutes = (date('H') * 60) + date('i');
			}else{
				$total_minutes = 1440;
			}
			// echo "Total Min : $total_minutes - $stop_time";
			$init = ($stop_time) * 60;			
			$hours = floor($init / 3600);
			$minutes = floor(($init / 60) % 60);
			$seconds = $init % 60;
			$run_time = $row->running_time;
			$row->run_time = $run_time;
			$row->running_time = "$hours Hour $minutes Min";
			if($row->distance < 0.25){
				$row->running_time = "0 Hour 0 Min";
			}
			$responce->rows[$i] = $row;
			$i++;
		}
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	/*function export_pdf(){
	
		$filenames =  "All Points".date("Ymdhis");
		$date_format = $this->session->userdata('date_format');  
		$time_format = $this->session->userdata('time_format');
		$data = array();
		$header = array('Datetime', 'Assets Name','Driver Name','Address');
		$row = $this->allpoints_model->getAllData();
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
		$this->fpdf->Cell(35,6,'Datetime',1,0,'C');
        $this->fpdf->Cell(35,6,'Assets Name',1,0,'C');
        $this->fpdf->Cell(35,6,'Driver Name',1,0,'C');
        $this->fpdf->Cell(80,6,'Address',1,0,'C');
        $this->fpdf->Cell(15,6,'Speed',1,1,'C');
		//$this->fpdf->SetAutoPageBreak(true);
		$this->fpdf->SetFont('Arial','',8);
		        //data	
				
        foreach($row['result'] as $datarow)
        {   
				$add_date = $datarow->add_date;
			//$re_date = $datarow->re_date;
           $this->fpdf->Cell(35,6,date("$date_format $time_format", strtotime($add_date)),1,0,'C');
           $this->fpdf->Cell(35,6,$datarow->assets_name."(".$datarow->device_id.")",1,0,'C');
           $this->fpdf->Cell(35,6,$datarow->driver_name,1,0,'C');
            $this->fpdf->Cell(80,6,$datarow->address,1,0,'C'); 
            $this->fpdf->Cell(15,6,$datarow->speed,1,1,'C'); 
        }
		$basepath = dirname(FCPATH);		
		$this->fpdf->Output($basepath.'/test/pdf/'.$filenames.'.pdf','F');
		redirect(base_url().'/pdf/'.$filenames.'.pdf');
	}

	*/
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
		$grid_js = build_grid_js('distancereport_list',site_url("/reports/ajax/distance"),$colModel,'id','asc',$gridParams);
		
		$data['js_grid'] = $grid_js;
		*/
	//	$data['device'] = $this->allpoints_model->prepareCombo();
		
		$result = $this->home_model->get_group($this->session->userdata('user_id')); 
		$group = "";
		foreach($result as $row) {
			$group .= "<option value='".$row->id."'>".$row->group_name."</option>";
		}
		$responce['group'] = $group;
		$this->load->view('distancereport', $responce);
	}
}