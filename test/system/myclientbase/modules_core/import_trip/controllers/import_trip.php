<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Import_trip extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('import_trip_model','',TRUE);
	}
	function index()
	{
		$this->load->view( 'import_trip' );
	}
	function loadData(){
		
		$data = $this->trips_model->getAllData(); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		foreach($data['result'] as $row) {
			$responce->rows[$i] = $row;
			$i++;
		}
		echo json_encode($responce);
	}
}
?>