<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Media_center extends Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->load->database();
	}
	
	function index(){
		$this->load->view('media_center');
	}
	
}
?>