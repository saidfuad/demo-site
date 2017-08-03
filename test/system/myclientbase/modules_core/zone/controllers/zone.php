<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Zone extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('zone_model','',TRUE);
		$this->load->model('form_model','',TRUE);
		$this->load->helper('uri');
	}	
	function index()
	{
		$this->load->view( 'zone' );
	}
	function loadData($cmd='false'){		
		$data = $this->zone_model->getAllData($cmd); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$responce->sql = $data['sql'];
		$i=0;
		foreach($data['result'] as $row) {
			$row->polyname = '<a style="cursor:pointer;" onclick="edit_in_map_zone('.$row->polyid.')">'.$row->polyname.'</a>';
			$responce->rows[$i] = $row;
			$i++;
		}
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	function deleteData(){
		
		//echo $this->areas_model->delete_areas(); 
		$this->output->set_output($this->zone_model->delete_zone());
	}
	
	function index1()
	{
		$this->load->view('zone',$data);
	}
	function form() {
	
		if (!$this->form_model->validate()) {
			$this->load->helper('form');

			if (!$_POST AND uri_assoc('id')) {
				$this->form_model->prep_validation(uri_assoc('id'));
			}
	
			$rows = $this->zone_model->prepare_assets();
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
			
			
			$rows = $this->zone_model->getAddressBookGroupList();
			$opt = '<option value="">Please Select</option>';
			foreach ($rows as $row) {
				$opt .= '<option value="'.$row->id.'"';
				$opt .= '>'.$row->group_name.')</option>';
			}
			
			
			$this->load->model('home/home_model');
			
			$rows = $this->home_model->addressbook_opt();
			$addressbookOpt = '';
			
			if(is_array($this->form_model->addressbook_ids))
			{
				$this->form_model->addressbook_ids = implode(",",$this->form_model->addressbook_ids);
			}
			$ars=$this->form_model->addressbook_ids;
			$ars = explode(",", $ars);
			foreach ($rows as $row) {
			$addressbookOpt .= '<option value="'.$row->id.'" ';
				if(in_array($row->id, $ars))
					$addressbookOpt .= ' selected="selected"';
				$addressbookOpt .= ' >'.$row->name.'</option>';
			}		
			$this->form_model->addressbook_ids = $addressbookOpt;
			$this->load->view('form');
		}
		else {
			$user_id=$this->session->userdata('user_id');
			$formdata = $this->form_model->db_array();
			unset($formdata['address_book_group']);
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
			
			//die(print_r($formdata['addressbook_ids']));
			if(isset($formdata['addressbook_ids']))
			{
				if(sizeof($formdata['addressbook_ids'])>0)
				{
					$arr=implode(",",$formdata['addressbook_ids']);
					unset($formdata['addressbook_ids']);
					$formdata['addressbook_ids']=$arr;
				}
				else
				{
					unset($formdata['addressbook_ids']);
					$formdata['addressbook_ids']="";
				}
			}
			if(!isset($formdata['sms_alert']))
			{
				$formdata['sms_alert']=0;
			}
			if(!isset($formdata['email_alert']))
			{
				$formdata['email_alert']=0;
			}
			if(!isset($formdata['in_alert']))
			{
				$formdata['in_alert']=0;
			}
			if(!isset($formdata['out_alert']))
			{
				$formdata['out_alert']=0;
			}

			if(uri_assoc('id')){
				$this->zone_model->save($formdata, uri_assoc('id'));
			}
		}
	}
	function getIco()
	{
	
		$directory = "assets/landmark_images/";
		$images = glob($directory . "{*.jpg,*.gif,*.png}", GLOB_BRACE);
		$iconDiv = "<div style='height: 200px; margin-top: 10px; margin-bottom: 10px; margin-left: 10px; overflow: auto; width: 97%;'>";
		foreach($images as $image)
		{
			$iconDiv .= "<div style='border: 1px solid rgb(197, 219, 236); white-space: nowrap; margin: 2px; display: inline-block;cursor:pointer' id='getIcon_div' class='imageSection' ";
			$iconDiv .=" onClick='selectedMarker_land(\"".$image."\")'>";
			$iconDiv .="<img src='".base_url().$image."'></div>";
			//$iconOpt .= '<option title="'.base_url().$image.'" value="'.$image.'"></option>';
		}

		$iconDiv.="</div>";
		//echo $iconDiv;
		$this->output->set_output($iconDiv);
	}
	function export(){
		
		$this->load->plugin('to_excel'); 
		$this->form_model->export();
	}	
	function state_data(){
		$this->load->model('zone_model','',TRUE);
		//echo $query=$this->areas_model->state();
		$this->output->set_output($query=$this->zone_model->state());
	}
	function city_data(){
		$this->load->model('zone_model','',TRUE);
		//echo $query=$this->areas_model->city();
		$this->output->set_output($query=$this->zone_model->city());
	}
	function get_json_data()
	{
		$this->load->model('zone_model','',TRUE);
		$rs=$this->zone_model->get_json();
		//echo json_encode($rs);
		$this->output->set_output(json_encode($rs));
	}
	function check_duplicates()
	{
		$username=uri_assoc("name");
		$id=uri_assoc("id");
		if(!$this->zone_model->checkUserDuplicate($username,$id))
		{
			//echo "This Username Already Taken, Please Choose Unique Username.";
			$this->output->set_output("This Username Already Taken, Please Choose Unique Username.");
		}
	}
	function getDashCombo()
	{
		$this->load->model('home/home_model','',TRUE);
		$combo_s="";
		$combo_s.="<option value=''>".$this->lang->line('all_assets')."</option>";
		$combo_s.="<option value='running'>".$this->lang->line('running')."</option>";
		$combo_s.="<option value='parked'>".$this->lang->line('parked')."</option>";
		$combo_s.="<option value='out_of_network'>".$this->lang->line('out_of_network')."</option>";
		$combo_s.="<option value='device_fault'>".$this->lang->line('device_fault')."</option>";
		$rows = $this->home_model->get_group($this->session->userdata('user_id'));
			if(count($rows)) {
				foreach ($rows as $row) {
					$combo_s .= "<option value='g-".$row->id."'>".$row->group_name."</option>";
				}
			}
		
		$rows = $this->home_model->get_subuser($this->session->userdata('user_id'));
		if(count($rows)) {
			foreach ($rows as $row) {
				$combo_s .= "<option value='u-".$row->user_id."'>".$row->username." (".$row->first_name." ".$row->last_name.")</option>";
			}
		}
		//echo $combo_s;
		$this->output->set_output($combo_s);
	}
}
?>