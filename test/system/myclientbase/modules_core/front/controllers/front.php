<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Front extends Controller {
	function index(){
		$this->load->helper('url');
		$data['nkonnect'] = 'http://www.nkonnect.com/';
		$this->load->view('index', $data);
	}
}
?>