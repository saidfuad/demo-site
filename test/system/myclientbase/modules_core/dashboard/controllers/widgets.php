<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Widgets extends Admin_Controller {

	function __construct() {
		
		parent::__construct(TRUE);
		
		$this->load->model('mdl_dashboard_reports', '', TRUE);
	}

	function state() {
		if($this->input->is_ajax_request("")) {
			$widget = $this->input->post('widget');
			
			print $this->mdl_dashboard_reports->getWidgetStatus($widget);
			
		}
		// echo $widget;

	}

	function updateColor() {
		if($this->input->is_ajax_request("")) {
			
			$widget = $this->input->post('widget');
			$color  = $this->input->post('color');
			
			echo $this->mdl_dashboard_reports->setWidgetColor($widget, $color);
			
//			echo "Widget : $widget, Color : $color";
		}
	}

	function updateSize() {
		if($this->input->is_ajax_request("")) {
			$widget = $this->input->post('widget');
			$size   = $this->input->post('size');
			$this->mdl_dashboard_reports->setWidgetSize($widget, $size);
//			echo "Widget : $widget, Size : $size";
		}
	}

	function updateReportOrder() {
		if($this->input->is_ajax_request()) {
			$reports = $this->input->post("rpt");
			$this->mdl_dashboard_reports->setWidgetOrder($reports);
//			echo "Reports : $reports, $user";
		}
	}
	
	function removeWidget() {
		if($this->input->is_ajax_request()) {
			$widget = $this->input->post('widget');
			$result = $this->mdl_dashboard_reports->remove_widget($widget);
//			echo "Reports : $reports, $user";
		}
	}
	
}

?>