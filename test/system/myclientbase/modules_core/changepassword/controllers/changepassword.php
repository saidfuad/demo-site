<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Changepassword extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('changepassword_model','',TRUE);
		$this->load->helper('uri');
	}
	
	function index()
	{
		if($this->session->userdata('user_id')!=21){
			$this->load->view( 'form' );		
		}else{
			$this->load->view( 'not_authorised' );
		}
	}
	function change_password_submit(){
		if($this->session->userdata('user_id')!=21){
			$this->output->set_output($this->changepassword_model->change_password());	
		}
		//echo $this->changepassword_model->change_password();
	
	}	
}
?>