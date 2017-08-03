<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Profile extends Admin_Controller {
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('form_model','',TRUE);
		$this->load->model('profile_model','',TRUE);
		$this->load->model('payment/payment_model','',TRUE);
	}
	function get_language()
	{
		$this->output->set_output($this->profile_model->get_language(uri_assoc('language')));
		//echo $this->profile_model->get_language(uri_assoc('language'));
	}
	function get_currency_format()
	{
		$this->output->set_output($this->profile_model->get_currency_format(uri_assoc('currency_format')));
		//echo $this->profile_model->get_currency_format(uri_assoc('currency_format'));
	}
	function get_time_format()
	{
		$this->output->set_output($this->profile_model->get_time_format(uri_assoc('time_format')));
		//echo $this->profile_model->get_time_format(uri_assoc('time_format'));
	}
	function get_date_format()
	{
		$this->output->set_output($this->profile_model->get_date_format(uri_assoc('date_format')));
		//echo $this->profile_model->get_date_format(uri_assoc('date_format'));
	}
	function get_city()
	{
		$this->output->set_output($this->profile_model->get_city(uri_assoc('state')));
		//echo $this->profile_model->get_city(uri_assoc('state'));
	}
	function get_state()
	{
		$this->output->set_output($this->profile_model->get_state(uri_assoc('country')));
		//echo $this->profile_model->get_state(uri_assoc('country'));
	}
	function get_country()
	{
		$this->output->set_output($this->profile_model->get_country(uri_assoc('country')));
		//echo $this->profile_model->get_country(uri_assoc('country'));
	}
	function get_State_name()
	{
		$this->output->set_output($this->profile_model->get_State_name(uri_assoc('state')));
		//echo $this->profile_model->get_State_name(uri_assoc('state'));
	}
	function get_city_name()
	{
		//echo $this->profile_model->get_city_name(uri_assoc('city'));
		$this->output->set_output($this->profile_model->get_city_name(uri_assoc('city')));
	}
	function get_time_zone()
	{
		////echo $this->profile_model->get_time_zone(uri_assoc('zone'));
		$this->output->set_output($this->profile_model->get_time_zone(uri_assoc('zone')));
	}
	function get_timespan()
	{
		$this->output->set_output($this->profile_model->get_timespan(uri_assoc('network_timeout')));
		//echo $this->profile_model->get_language(uri_assoc('language'));
	}
	function form()
	{
		if (!$this->form_model->validate(uri_assoc('form_name'))) {
			$this->load->helper('form');
			$this->form_model->prep_validation(uri_assoc('form_name'));
			$data['result'] = $this->profile_model->get_alll_data();
			$this->load->view('profile',$data);
		}
		else {
			$formdata = $this->form_model->db_array();
			$formdata['sms_alert'] = (!isset($formdata['sms_alert'])) ? 0 : 1;
			$formdata['email_alert'] = (!isset($formdata['email_alert'])) ? 0 : 1;
			$formdata['location_with_tag'] = (!isset($formdata['location_with_tag'])) ? 0 : 1;
			$formdata['ignition_on_alert'] = (!isset($formdata['ignition_on_alert'])) ? 0 : 1;
			$formdata['ignition_off_alert'] = (!isset($formdata['ignition_off_alert'])) ? 0 : 1;
			$formdata['show_zone_name'] = (!isset($formdata['show_zone_name'])) ? 0 : 1;
			$formdata['auto_refresh_setting'] = (!isset($formdata['auto_refresh_setting'])) ? 0 : 1;
			$formdata['onscreen_alert'] = (!isset($formdata['onscreen_alert'])) ? 0 : 1;
			$formdata['network_timeout'] = (!isset($formdata['network_timeout'])) ? 36000 : $formdata['network_timeout'];
			
			if(isset($formdata['all_point_setting'])){
				$data['all_point_setting']=$formdata['all_point_setting'];
				unset($formdata['all_point_setting']);
				$this->profile_model->update_settings($data);
			}
			//$(alert_start_time);
			if(isset($formdata['alert_time'])){
				if($formdata['alert_time']=='any'){
					$formdata['alert_start_time']=date("H:i",strtotime("12:00"));
					$formdata['alert_stop_time']=date("H:i",strtotime("23:59"));
				}else if($formdata['alert_start_time']!="" && $formdata['alert_stop_time']!="" && $formdata['alert_time']=='given'){
					$formdata['alert_start_time']=date("H:i",strtotime($formdata['alert_start_time']));
					$formdata['alert_start_time']=gmdate("H:i",strtotime($formdata['alert_start_time']));
					$formdata['alert_stop_time']=date("H:i",strtotime($formdata['alert_stop_time']));
					$formdata['alert_stop_time']=gmdate("H:i",strtotime($formdata['alert_stop_time']));
				}else{
					$formdata['alert_start_time']=date("H:i",strtotime("12:00"));
					$formdata['alert_stop_time']=date("H:i",strtotime("23:59"));
					$formdata['alert_time']='any';
				}
			}else{
				$formdata['alert_start_time']=date("H:i",strtotime("12:00"));
				$formdata['alert_stop_time']=date("H:i",strtotime("23:59"));
				$formdata['alert_time']='any';
			}
			// die(print_r($formdata));
			$this->form_model->save($formdata, $this->session->userdata('user_id'));
		}
	}
	function index()
	{
		$data['result'] = $this->profile_model->get_alll_data();
		$data['countryOpt'] = $this->profile_model->get_country($data['result']->country);
		$data['def_dash_viewOpt'] = $this->profile_model->get_dashboard_view($data['result']->def_dash_view);
		$data['stateOpt'] = $this->profile_model->get_state($data['result']->country,$data['result']->state);
		$data['cityOpt'] = $this->profile_model->get_city($data['result']->state,$data['result']->city);
		$data['timezoneOpt'] = $this->profile_model->get_time_zone($data['result']->timezone);
		$data['date_format_Opt'] = $this->profile_model->get_date_format($data['result']->date_format);
		$data['time_format_Opt'] = $this->profile_model->get_time_format($data['result']->time_format);
		$data['language_Opt'] = $this->profile_model->get_language($data['result']->language);
		$data['currency_format_Opt'] = $this->profile_model->get_currency_format($data['result']->currency_format);
		$data['timeSpan'] = $this->profile_model->get_timespan($data['result']->network_timeout);
		if($this->session->userdata("usertype_id")==1){
			//$data['all_point_setting'] = $this->profile_model->get_all_point_setting();
		}
		$charges = $this->payment_model->getPerDayAmount($this->session->userdata("user_id"));
		
		$data['charges'] = $charges * 30;
		
		$data['expirt_date'] = $this->payment_model->getExpiryDate($this->session->userdata("user_id"));
		
		$data['sms_balance'] = $this->profile_model->getSmsBalance($this->session->userdata("user_id"));
		
		$max_time= gmdate("G:i", $data['result']->max_stop_time*60);
		$max=explode(":",$max_time);
		$hourOpt="";
		$minuteOpt="";
		$alert_box_open= gmdate("G:i", $data['result']->alert_box_open_time*60);
		$box_open=explode(":",$alert_box_open);
		$hourOpt="";
		$hourOpt_box="";
		$minuteOpt="";
		$minuteOpt_box="";
		for($i=0;$i<=100;$i++)
		{
			$hourOpt.="<option value='".$i."'";
			if($i==$max[0])
			{
				$hourOpt.=" selected='selected' ";
			}
			$hourOpt.=">".$i."</option>";
			$hourOpt_box.="<option value='".$i."'";
			if($i==$box_open[0])
			{
				$hourOpt_box.=" selected='selected' ";
			}
			$hourOpt_box.=">".$i."</option>";
			
			if($i<=59)
			{
				$minuteOpt.="<option value='".$i."'";
				if($i==$max[1])
				{
					$minuteOpt.=" selected='selected' ";
				}
				$minuteOpt.=">".$i."</option>";
				
				$minuteOpt_box.="<option value='".$i."'";
				if($i==$box_open[1])
				{
					$minuteOpt_box.=" selected='selected' ";
				}
				$minuteOpt_box.=">".$i."</option>";
			}
		}
		$data['max_stop_time_hour'] = $hourOpt;
		$data['max_stop_time_minute'] =$minuteOpt;
		$data['alert_box_time_hour'] = $hourOpt_box;
		$data['alert_box_time_minute'] =$minuteOpt_box;
		$this->load->view('profile',$data);
	}
}
?>