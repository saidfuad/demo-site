<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class command extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('command_model','',TRUE);
		$this->load->model('form_model','',TRUE);
	}
	function index()
	{
		$this->load->view('command');
	}
	function loadData(){
		
		$data = $this->command_model->getAllData(); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		foreach($data['result'] as $row) {
			$responce->rows[$i] = $row;
			$i++;
		}
		//echo json_encode($responce);
		$this->output->set_output(json_encode($responce));
	}
	function deleteData(){
		//echo $this->command_model->delete_command(); 
		$this->output->set_output($this->command_model->delete_command()); 
	}

	
	function index1()
	{
		
		$this->load->helper('flexigrid');
		/*
		 * 0 - display name
		 * 1 - width
		 * 2 - sortable
		 * 3 - align
		 * 4 - searchable (2 -> yes and default, 1 -> yes, 0 -> no.)
		 */
		$colModel['id'] = array('ID',40,TRUE,'center',2);
		$colModel['assets_name'] = array('Asset Name',150,TRUE,'center',1);
		$colModel['device_id'] = array('Device',100,TRUE,'center',1);
		$colModel['icon_id'] = array('Icon',100,TRUE,'center',1);
		$colModel['sim_number'] = array('Sim Number',150, TRUE,'center',1);
		
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 'auto',
		'height' => 'auto',
		'rp' => 10,
		'rpOptions' => '[10,15,20,25,40]',
		'pagestat' => 'Displaying: {from} to {to} of {total} items.',
		'blockOpacity' => 0.5,
		'title' => 'My Assets List',
		'showTableToggleBtn' => false
		);
		
		$buttons[] = array('Add','add','actionAssets');
		$buttons[] = array('Edit','edit','actionAssets');
		$buttons[] = array('Delete','delete','actionAssets');
		$buttons[] = array('separator');
		$buttons[] = array('Select All','add','actionAssets');
		$buttons[] = array('DeSelect All','delete','actionAssets');
		$buttons[] = array('separator');
		
		
		
		$data['headerjs'] = '';
		$data['js_grid'] = $grid_js;
//		print_r($data);
		$this->load->view('assets',$data);
	}

		function form() {

			if (!$this->form_model->validate()) {
				
				$rows_type = $this->command_model->prepare_assets_class();
				
				$typeOpt = '';
				foreach ($rows_type as $row) {
					$typeOpt .= '<option value="'.$row->id.'"';
					if($row->id == $this->form_model->assets_class_id)
						$typeOpt .= ' selected="selected"';
					$typeOpt .= '>'.$row->assets_class_name.'</option>';
				}
				$this->form_model->assets_class_opt = $typeOpt;
				
				$this->load->helper('form');

				if (!$_POST AND uri_assoc('id')) {

					$this->form_model->prep_validation(uri_assoc('id'));

				}
			
				$this->load->view('form');

			}

			else {
				$formdata = $this->form_model->db_array();
				
				if(uri_assoc('id')){
					$this->form_model->save($formdata, uri_assoc('id'));
				}else{
					$formdata['add_date'] = gmdate('Y-m-d H:i:s');
					$formdata['add_uid'] = $this->session->userdata('user_id');
					$this->command_model->save($formdata, uri_assoc('id'));
				}
			}

		}
	
}
?>