<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Routes extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('routes_model','',TRUE);
		$this->load->model('form_model','',TRUE);
		$this->load->helper('uri');
	}	
	function index()
	{
		$this->load->view( 'routes' );
	}
	function loadData(){		
		$data = $this->routes_model->getAllData(); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		foreach($data['result'] as $row) {
			$lString ="";
			if($row->landmark_ids!=""){
				$lArr = explode(",",$row->landmark_ids);
				$lString = str_replace(",", "-To-",$row->landmark_ids);
				if($row->round_trip == 1 && count($lArr))
					$lString .= "-To-".$lArr[0];
			}
			$row->landmark_ids=$lString;
			$responce->rows[$i] = $row;
			$i++;
		}
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	function deleteData(){
		
		//echo $this->routes_model->delete_routes(); 
		$this->output->set_output($this->routes_model->delete_routes()); 
	}
	
	function index1()
	{
		$this->load->view('routes',$data);
	}
	function form() {
	
		if (!$this->form_model->validate()) {
			$this->load->helper('form');

			if (!$_POST AND uri_assoc('id')) {
				$this->form_model->prep_validation(uri_assoc('id'));
			}
	
			$rows = $this->routes_model->prepare_assets();
			$opt = '';
			if(is_array($this->form_model->deviceid))
			{
				$this->form_model->deviceid = implode(",",$this->form_model->deviceid);
			}
			
			$ast = $this->form_model->deviceid;
			$ast = explode(",", $ast);
			foreach ($rows as $row) {
				$opt .= '<option value="'.$row->id.'"';
				if(in_array($row->id, $ast))
					$opt .= ' selected="selected"';
				$opt .= '>'.$row->assets_name.' ('.$row->device_id.')</option>';
				
			}
			$this->form_model->deviceid = $opt;
										
			$opt="";
			$this->form_model->distance_unit;
			if($this->form_model->distance_unit=="KM")
				$opt.="<option selected='selected'>KM</option>";
			else
				$opt.="<option>KM</option>";
			if($this->form_model->distance_unit=="Mile")
				$opt.="<option selected='selected'>Mile</option>";
			else
				$opt.="<option>Mile</option>";
			if($this->form_model->distance_unit=="Meter")
				$opt.="<option selected='selected'>Meter</option>";
			else
				$opt.="<option>Meter</option>";
			
			$this->form_model->distance_unit = $opt;
		
			$this->load->view('form');
		}
		else {
			$user_id=$this->session->userdata('user_id');
			$formdata = $this->form_model->db_array();
		
			if(isset($formdata['deviceid']))
			{
				if(sizeof($formdata['deviceid'])>0)
				{
					$arr=implode(",",$formdata['deviceid']);
					unset($formdata['deviceid']);
					$formdata['deviceid']=$arr;
				}
				else
				{
					unset($formdata['deviceid']);
					$formdata['deviceid']="";
				}
			}
			else
			{
				$formdata['deviceid']="";
			}
			
			if(!isset($formdata['sms_alert']))
			{
				$formdata['sms_alert']=0;
			}
			if(!isset($formdata['email_alert']))
			{
				$formdata['email_alert']=0;
			}
			if(!isset($formdata['round_trip']))
			{
				$formdata['round_trip']=0;
			}

			if(uri_assoc('id')){
                                //Added by Poonam 24-1-2015 13:31 PM
				$this->routes_model->save($formdata, uri_assoc('id'));
				
                               //$this->form_model->save($formdata, uri_assoc('id'));
			}
		}
	}
}
?>