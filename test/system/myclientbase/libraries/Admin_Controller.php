<?php (defined('BASEPATH')) OR die('No direct script access allowed');

class Admin_Controller extends Controller {

	public static $is_loaded;

	function __construct($global_admin = FALSE) {

		parent::Controller();

		$this->load->library('session');
		
		
		if (!isset(self::$is_loaded)) {

			self::$is_loaded = TRUE;

			$this->load->helper('url');

		/*	if (!$global_admin) {
*/
				if (!$this->session->userdata('is_admin')) {
					
					$this->session->set_userdata('last_url', current_url());

					
					
				/*	echo "window.location='".site_url("sessions/login")."'";
					die();*/
					
					redirect('sessions/login');

				}

		/*	}

			else {

				if (!$this->session->userdata('global_admin')) {

					redirect('dashboard');

				}

			}
*/
			$this->load->database();

			$this->load->helper(array('uri', 'mcb_currency', 'mcb_invoice', 'mcb_date', 'mcb_icon', 'mcb_custom'));

			// $this->load->model(array('mcb_modules/mdl_mcb_modules', 'mcb_data/mdl_mcb_data'));

			$this->load->model(array('mcb_data/mdl_mcb_data'));

			// $this->mdl_mcb_modules->set_module_data();

			// $this->mdl_mcb_modules->load_custom_languages();

			$this->mdl_mcb_data->set_session_data();
			//var_dump($this->session->userdata);
			$this->load->language($this->session->userdata('language'), $this->mdl_mcb_data->default_language);
			
			date_default_timezone_set($this->session->userdata('time_zone'));
			
			// $this->load->model(array('fields/mdl_fields'));

			$this->load->library(array('form_validation', 'redir'));

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		}

	}

}

?>