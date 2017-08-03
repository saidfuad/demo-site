<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

	var $widgets = array();

	function __construct() {

		parent::__construct();
		
		/*if ($this->mdl_mcb_data->dashboard_override) {

			redirect($this->mdl_mcb_data->dashboard_override);

		}*/

	}

	function index() {
		
		$this->load->library('table');
		$data["auto_reload"] = 0;
		
		$this->load->model('mdl_dashboard_reports', '', TRUE);
		
		if($this->input->post("interval")) {
			$data["interval"] = $this->input->post("interval");
//			$data["interval"] = rand(50, 100);
			$data["auto_reload"] = 1;
		}
		else {
			$data["interval"] = $this->mdl_dashboard_reports->get_refresh_rate();
		}
		
		$newColumn = $this->mdl_dashboard_reports->get_reports_order();
		// print_r($newColumn);
		 //exit;
		if(! $newColumn) {
			$this->load->view('no_report');
		}
		else {
			$ord = str_replace(";", ",", $newColumn);
			$ord = str_replace(",,", ",", $ord);
			
			$reports = $this->mdl_dashboard_reports->get_dashboard_reports($ord);
			
			// print_r($reports);
	
			//$data['reports'] = $reports;
			
			$newColumn = explode(";", $newColumn);
			$secondColumn = $newColumn[1];
			$newColumn = explode(",", $secondColumn);
			$data['newColumn'] = $newColumn;
			$data['secondColumn'] = $secondColumn;
			$data['total_reports'] = count($reports);
			$result['widgets'] = '';
			for($i=0; $i<count($reports); $i++){
				$command = $reports[$i]->report_file;
				$reports[$i]->count_rec = 0;
				$content = '';
				
				if($reports[$i]->id == 1 || $reports[$i]->id == 2){
						
						$tmp_rpt = $this->mdl_dashboard_reports->$command();
						$widgets_data['table'] = $tmp_rpt[0];
						$reports[$i]->count_rec = $tmp_rpt[1];
						$widgets_data['class'] = $reports[$i]->rpt_color;
						$content = $this->load->view('widgets_table', $widgets_data, true);
				}
				
				$data['widgets_data'] = $content;
				$data['command'] = $command;
				$data['reports'] = $reports[$i];			
				$data['loop'] = $i;
				$result['widgets'] .= $this->load->view('widgets', $data, true);
			}
			
			$this->load->view('dashboard', $result);
		}

	}

	function show_custom_menu() {

		foreach ($this->mdl_mcb_modules->custom_modules as $module) {

			if ($module->module_enabled and isset($module->module_config['dashboard_menu'])) {

				$this->load->view($module->module_config['dashboard_menu'], NULL, FALSE);

			}

		}

	}

	function show_widgets() {

		foreach ($this->mdl_mcb_modules->custom_modules as $module) {

			if ($module->module_enabled and isset($module->module_config['dashboard_widget'])) {

				echo modules::run($module->module_config['dashboard_widget']);

			}

		}

	}

	function record_not_found() {

		$this->load->view('record_not_found');

	}
	function _post_handler() {
/*
		if ($this->input->post('btn_add_invoice')) {

			redirect('invoices/create');

		}
*/
	}

}

?>