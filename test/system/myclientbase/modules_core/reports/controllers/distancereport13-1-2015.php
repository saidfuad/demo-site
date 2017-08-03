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
		$latitude = array();
		$longitude = array();
		$devices = array();
		foreach($data['result'] as $row) {
			//print_r($latitude);
			/*
			$latitude[$i]=$row->lati;
			$longitude[$i]=$row->longi;
			$devices[$i]= $row->devices;
			*/
			$responce->rows[$i] = $row;
			$i++;
		}
		
		//print_r($unique_device );
		/*
		$total_distance = 0;
		for($i=0;($i<count($latitude)-1);$i++){
			$R = 6371;
			$dLat = ($latitude[$i+1] - $latitude[$i]) * 3.143 / 180;
			$dLon = ($longitude[$i+1] - $longitude[$i]) * pi() / 180;
			$a = sin($dLat / 2) * sin($dLat / 2) +
					cos($lat1 * pi() / 180) * cos($lat2 * pi() / 180) *
					sin($dLon / 2) * sin($dLon / 2);
			$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
			$d = $R * $c;
			// $row->distance1 = $d;
			//$responce->rows[$i]->distance1 = $d;
			$total_distance = $total_distance +  $d;
		}
		$responce->rows[0]->total_distance = $total_distance ;
		$responce->rows[0]->devices = $devices[0] ;
		//$responce->total_distance = $total_distance ;
		//echo $total_distance;
		//print_r($responce->rows1);
		//echo json_encode($responce);
		*/
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
		$group = "<option value=''>Please Select</option>";
		foreach($result as $row) {
			$group .= "<option value='".$row->id."'>".$row->group_name."</option>";
		}
		$responce['group'] = $group;
		$this->load->view('distancereport', $responce);
	}
}