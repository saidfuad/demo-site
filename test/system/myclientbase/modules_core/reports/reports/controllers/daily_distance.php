<?php
class Daily_distance extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);
		
	}
	function index()
	{	
		$this->load->view('daily_distance');
	}	
}
?>