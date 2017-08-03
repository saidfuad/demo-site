<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Landmarks extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('landmarks_model','',TRUE);
		$this->load->model('form_model','',TRUE);
		$this->load->helper('uri');
	}	
	function index()
	{
		$this->load->view( 'landmarks' );
	}
	function loadData($cmd='false'){		
		$data = $this->landmarks_model->getAllData($cmd); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		foreach($data['result'] as $row) {
			$row->name = '<a style="cursor:pointer;" onclick="edit_in_map_lnd('.$row->id.')">'.$row->name.'</a>';
			$responce->rows[$i] = $row;
			$i++;
		}
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	function deleteData(){
		
		//echo $this->landmarks_model->delete_landmarks(); 
		$this->output->set_output($this->landmarks_model->delete_landmarks()); 
	}
	
	function index1()
	{
		$this->load->view('landmarks',$data);
	}
	function form() {
	
		if (!$this->form_model->validate()) {
			$this->load->helper('form');

			if (!$_POST AND uri_assoc('id')) {
				$this->form_model->prep_validation(uri_assoc('id'));
			}
			
			$rows = $this->landmarks_model->prepare_assets();
			$opt = '';
			if(is_array($this->form_model->device_ids))
			{
				$this->form_model->device_ids = implode(",",$this->form_model->device_ids);
			}
			
			$ast = $this->form_model->device_ids;
			$ast = explode(",", $ast);
			foreach ($rows as $row) {
				$opt .= '<option value="'.$row->id.'"';
				if(in_array($row->id, $ast))
					$opt .= ' selected="selected"';
				$opt .= '>'.$row->assets_name.' ('.$row->device_id.')</option>';
				
			}
			$this->form_model->device_ids = $opt;
			
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
			
			$rows = $this->landmarks_model->getAddressBookGroupList();
			$opt = '<option value="">Please Select</option>';
			foreach ($rows as $row) {
				$opt .= '<option value="'.$row->id.'"';
				$opt .= '>'.$row->group_name.')</option>';
			}
			
			$this->form_model->address_book_group = $opt;
			
			$rows = $this->landmarks_model->prepare_LandmarkGroups_1();
			$opt = '<option value="">Please Select</option>';
			$L_group = $this->form_model->id;
			foreach ($rows as $row) {
				$opt .= '<option value="'.$row->id.'"';
				if($row->id == $L_group)
					$opt .= ' selected="selected"';
				$opt .= '>'.$row->landmark_group_name.')</option>';
				
			}
			$this->form_model->group_id = $opt;
	
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
			if(isset($formdata['device_ids']))
			{
				if(sizeof($formdata['device_ids'])>0)
				{
					$arr=implode(",",$formdata['device_ids']);
					unset($formdata['device_ids']);
					$formdata['device_ids']=$arr;
				}
				else
				{
					unset($formdata['device_ids']);
					$formdata['device_ids']="";
				}
			}
			else
			{
				$formdata['device_ids']="";
			}
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
			if(uri_assoc('id')){
				$this->form_model->save($formdata, uri_assoc('id'));
			}
		}
	}
	function radius_form(){
		$rows = $this->landmarks_model->getLandmarkList();
		$opt = '';
		foreach ($rows as $row) {
			$opt .= '<option value="'.$row->id.'"';
			$opt .= ' selected="selected"';
			$opt .= '>'.$row->name.' ('.$row->address.')</option>';
			
		}
		$device_ids = $opt;
		
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
		
		$distance_unit = $opt;
		$radius="5.00";
		$data['device_ids']=$device_ids;
		$data['distance_unit']=$distance_unit;
		$data['radius']=$radius;
		$this->load->view('landmark_radius_form',$data);
	}
	function getIco()
	{	
		$rows=$this->landmarks_model->getIconPaths();
		
		//$directory = "assets/landmark_images/";
		//$images = glob($directory . "{*.jpg,*.gif,*.png}", GLOB_BRACE);
		
		$iconDiv = "<div style='height: 200px; margin-top: 10px; margin-bottom: 10px; margin-left: 10px; overflow: auto; width: 97%;'>";
		foreach($rows as $image)
		{
			$iconDiv .= "<div style='border: 1px solid rgb(197, 219, 236); white-space: nowrap; margin: 2px; display: inline-block;cursor:pointer' id='getIcon_div' class='imageSection' ";
			$iconDiv .=" onClick='selectedMarker_land(\"".$image->image_path."\")'>";
			$iconDiv .="<img src='".base_url()."/".$image->image_path."'></div>";
			//$iconOpt .= '<option title="'.base_url().$image.'" value="'.$image.'"></option>';
		}
		
		//$iconDiv.="</div>";
		//echo $iconDiv;
		$this->output->set_output($iconDiv);
	}
	function export(){
		
		$this->load->plugin('to_excel'); 
		$this->form_model->export();
	}	
	function state_data(){
		$this->load->model('landmarks_model','',TRUE);
		//echo $query=$this->landmarks_model->state();
		$this->output->set_output($query=$this->landmarks_model->state());
	}
	function submit_landmark_radius(){
		$landmarks=$_REQUEST['landmark_ids'];
		$radius=$_REQUEST['radius'];
		$distance_unit=$_REQUEST['distance_unit'];
		$arr=array();
		$dt=array();
		if($radius!=0){
			$landmarks=$_REQUEST['landmark_ids'];
			$radius=$_REQUEST['radius'];
			$distance_unit=$_REQUEST['distance_unit'];
			$result=$this->landmarks_model->updateLandmarkRadius($landmarks,$radius,$distance_unit);
			if($result==true){
				$arr['result']=true;
				$arr['err']="none";
				$dt['response']=$arr;
			}else{
				$arr['result']=false;
				$arr['err']="update";
				$dt['response']=$arr;
			}
			//echo json_encode($dt);
			$this->output->set_output(json_encode($dt));
		}else{
			$arr['result']=false;
			$arr['err']="radius";
			$dt['response']=$arr;		
			//echo json_encode($dt);
			$this->output->set_output(json_encode($dt));
		}
	}
	function city_data(){
		$this->load->model('landmarks_model','',TRUE);
		//echo $query=$this->landmarks_model->city();
		$this->output->set_output($query=$this->landmarks_model->city());
	}
	function get_json_data()
	{
		$this->load->model('landmarks_model','',TRUE);
		$rs=$this->landmarks_model->get_json();
		//echo json_encode($rs);
		$this->output->set_output(json_encode($rs));
	}
	function check_duplicates()
	{
		$username=uri_assoc("name");
		$id=uri_assoc("id");
		if(!$this->landmarks_model->checkUserDuplicate($username,$id))
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
//		$combo_s.="<option value='device_fault'>".$this->lang->line('device_fault')."</option>";
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