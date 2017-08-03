<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class AboutiHound extends Admin_Controller {
	function index()
	{
		$data["message"] = "About US -- DevIndia Infoway.";
		$this->load->view('aboutiHound',$data);
	}
}
?>