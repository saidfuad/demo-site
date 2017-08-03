<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Privacy extends Admin_Controller {
	function index()
	{
		$data["message"] = "About US.";
		$this->load->view('privacy',$data);
	}
}
?>



