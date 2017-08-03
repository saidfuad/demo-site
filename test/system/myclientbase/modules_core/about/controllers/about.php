<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class About extends Admin_Controller {
	function index()
	{
		$data["message"] = "About US -- DevIndia Infoway.";
		$this->load->view('about',$data);
	}
}
?>