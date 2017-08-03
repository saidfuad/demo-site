<?php
class Inspection extends Admin_Controller {
	
	/*function Country  ()
	{
		parent::Controller();	
		$this->load->helper('flexigrid');
		$this->load->helper('url');	
		$this->load->model('form_model');	
	}*/
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('inspection_model','',TRUE);
	}
	
	function index()
	{
		
	/*	$this->load->helper('flexigrid');
		ver lib
		
		
		 * 0 - display name
		 * 1 - width
		 * 2 - sortable
		 * 3 - align
		 * 4 - searchable (2 -> yes and default, 1 -> yes, 0 -> no.)
		 
		$colModel['id'] = array('ID',40,TRUE,'center',2);
		$colModel['add_date'] = array('Date',100,TRUE,'center',1);
		$colModel['assets_name'] = array('Assets Name',100,TRUE,'left',1);
		$colModel['device_id'] = array('Device',80,TRUE,'left',1);
		$colModel['address'] = array('Address',140,TRUE,'left',1);
		$colModel['lati'] = array('Lat',60, TRUE,'left',1);
		$colModel['longi'] = array('Long',60, TRUE, 'left',1);
		$colModel['speed'] = array('Speed',60, TRUE, 'left',1);
		$colModel['actions'] = array('View on Map',60, FALSE, 'left',0);
		
		
		
		  Aditional Parameters
		 
		$gridParams = array(
		'width' => 'auto',
		'height' => 'auto',
		'rp' => 10,
		'rpOptions' => '[10,15,20,25,40]',
		'pagestat' => 'Displaying: {from} to {to} of {total} items.',
		'blockOpacity' => 0.5,
		'title' => 'List',
		'showTableToggleBtn' => false
		);
		
		
		 * 0 - display name
		 * 1 - bclass
		 * 2 - onpress
		 
		$buttons[] = array('Track on Map','add','viewOnMap');
		$buttons[] = array('Delete','delete','actionUser');
		$buttons[] = array('Edit','edit','actionUser');
		$buttons[] = array('separator');
		$buttons[] = array('Select All','add','actionUser');
		$buttons[] = array('Clear All','delete','actionUser');
		$buttons[] = array('Invert Selection','invert','actionUser');
		
		
		$buttons[] = array('Export','export','actionUser');

		
		Build js
		View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('inspection_list',site_url("/reports/ajax/inspection"),$colModel,'id','asc',$gridParams,$buttons);
		
		$data['js_grid'] = $grid_js;
		
		*/
		
		//$this->output->set_header("Expires: Wed, 02 Jan 2013 05:00:00 GMT"); 
		//$data['device'] = $this->inspection_model->prepareCombo();
		$this->load->view('inspection');
	}
	function loadData($cmd='false'){
		
		$data = $this->inspection_model->getAllData($cmd); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		foreach($data['result'] as $row) {
			$lat = $row->lati;
			$lng = $row->longi;
			$html = date("d.m.Y H:i a", strtotime($row->add_date));
			//$html .= "<br>".$row->assets_name;
			$html .= "<br>".$row->speed." KM";
			$html .= "<br>".$row->address;
			$row->actions = "<a href='#' onclick='viewLocationInspection($row->id)'><img src='".base_url()."/assets/marker-images/mini-RED-BLANK.png'></a>";
			$responce->rows[$i] = $row;
			$i++;
		}
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
		
	}
	function view_map(){
		$this->load->model('inspection_model');
		$rows = $this->inspection_model->get_map_data();
		$data = array();
		$stp_html="";
		if(count($rows)){
			foreach ($rows as $row){
				$data['lat'] = floatval($row->lati);
				$data['lng'] = floatval($row->longi);
				
				$stp_html .="<div>";
				$stp_html .="<div><table>";
				$stp_html = "<tr><td>".date($this->session->userdata('date_format')." ".$this->session->userdata('time_format'), strtotime($row->add_date))."</td></tr>";
				$stp_html .= "<tr><td>".'Vehicle : '."</td><td>".$row->assets.' ('.$row->device_id.')</td></tr>';
				$stp_html .= "<tr><td>".'Address : '."</td><td>".$row->address. "</td></tr>";
				$stp_html .= "<tr><td>".'Speed : '."</td><td>".$row->speed."</td></tr>";
				$stp_html .= "</table></div>";
				$stp_html .= "</div>";
				$data['html'] = $stp_html;
			}
		}
		else{
			//die("No data Found");
			$this->output->set_output("No data Found");
		}
		$this->load->view('inspection_report_view_file',$data);
	}
	function trackOnMap()
	{
		$date_format = $this->session->userdata('date_format');  
		$time_format = $this->session->userdata('time_format');  
		$rows = $this->inspection_model->get_all_locations();
		$lat = array();
		$lng = array();
		$html = array();
		$ignition_status = array();
		$count=0;
		$DistanceVal=0;
		if(sizeof($rows)>1)
		{
			$DistanceVal=floatval(($rows[sizeof($rows)-1]['odometer']-$rows[0]['odometer'])/1000);
		}
		for($i=0;$i<sizeof($rows)-1;$i++)
		{
		
		/*	if($rows[$i]['ignition']!=0)
			{*/
				//$count++;
				$lat[] = $rows[$i]['lati'];
				$lng[] = $rows[$i]['longi'];
				$text = 'Date : '.date($date_format.' '.$time_format, strtotime($rows[$i]['add_date']))."<br>";
				$text .= 'Speed : '.$rows[$i]['speed']."<br>";
				//$text .= 'Lat : '.$row->lati.'<br>';
				//$text .= 'Lng : '.$row->longi.'<br>';
				$text .= 'Address : '.$rows[$i]['address'].'<br>';
				$html[] = $text;
				$ignition_status[]=1;
			/*}
			else if($rows[$i]['ignition']==0 && $rows[$i]['lati']!=$rows[$i+1]['lati'])
			{
				$ignition_status[]=0;
				//$count++;
			}*/
		}
		//echo $count;
		//die();
		$lat2 = '';
		$lng2 = '';
		$distance = 0;
		/*foreach ($rows as $row) {
            $lat[] = $row->lati;
			$lng[] = $row->longi;
			$text = 'Date : '.date('d.m.Y h:i: a', strtotime($row->add_date))."<br>";
			$text .= 'Speed : '.$row->speed."<br>";
			//$text .= 'Lat : '.$row->lati.'<br>';
			//$text .= 'Lng : '.$row->longi.'<br>';
			$text .= 'Address : '.$row->address.'<br>';
			$html[] = $text;
			$data["last_id"] = $row->id;
			
			$lat1 = $row->lati;
			$lng1 = $row->longi;
			if($lat2 != "" && $lng2 != ""){
				$theta = $lng1 - $lng2;  
				$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));  
				$dist = acos($dist);  
				$dist = rad2deg($dist);  
				$miles = $dist * 60 * 1.1515;  
				$unit = "K";  
				if ($unit == "K") {  
					$dstn = round(($miles * 1.609344), 2);
					if(!is_nan($dstn)){
						$distance += $dstn;
					}					
				 }
			}
			$lat2 = $lat1;
			$lng2 = $lng1;*\
			
        }*/
		$data['lat'] = $lat;
		$data['lng'] = $lng;
		$data['html'] = $html;
		$data['distance'] = $DistanceVal;
		$data['ignition_status'] = $ignition_status;
		//die(json_encode($data));
		$this->output->set_output(json_encode($data));
		
	}
}
?>