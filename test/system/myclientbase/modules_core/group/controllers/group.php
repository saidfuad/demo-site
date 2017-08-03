<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Group extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('group_model','',TRUE);
		$this->load->model('form_model','',TRUE);
	}
	function index()
	{
		$this->load->view( 'group' );
	}
	function loadData(){
		$data = $this->group_model->getAllData(); 
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
		
		//echo $this->group_model->delete_group(); 
		$this->output->set_output($this->group_model->delete_group());
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
		$colModel['group_name'] = array('Group Name',150,TRUE,'center',1);
		$colModel['assets'] = array('Assets',700,TRUE,'center',1);
		
		
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
		'title' => 'Group List',
		'showTableToggleBtn' => false
		);
		
		$buttons[] = array('Add','add','actionGroup');
		$buttons[] = array('Edit','edit','actionGroup');
		$buttons[] = array('Delete','delete','actionGroup');
		$buttons[] = array('separator');
		$buttons[] = array('Select All','add','actionGroup');
		$buttons[] = array('DeSelect All','delete','actionGroup');
		$buttons[] = array('separator');
		
		$grid_js = build_grid_js('allgroup_list',site_url("/group/ajax/allgroup"),$colModel,'id','asc',$gridParams,$buttons);
		
		$data['headerjs'] = '';
		$data['js_grid'] = $grid_js;
//		print_r($data);
		$this->load->view('group',$data);
	}
	function form() {

		if (!$this->form_model->validate()) {

			$this->load->helper('form');

			if (!$_POST AND uri_assoc('id')) {

				$this->form_model->prep_validation(uri_assoc('id'));

			}
			
			$rows = $this->group_model->prepare_assets();
			$opt = '';
			$ast = $this->form_model->assets;
			$ast = explode(",", $ast);
			foreach ($rows as $row) {
				$opt .= '<option value="'.$row->id.'"';
				if(in_array($row->id, $ast))
					$opt .= ' selected="selected"';
				$opt .= '>'.$row->assets_name.' ('.$row->device_id.')</option>';
				
			}
			$this->form_model->assets = $opt;
			
			$this->form_model->user_id = $this->session->userdata('user_id');
			$this->load->view('form');

		}

		else {	
			$formdata = $this->form_model->db_array();
			$newData = array();
			foreach($formdata as $key=>$value){
				if(is_array($value)){
					if(count($value) > 0)
						$value = implode(",", $value);
					else
						$value = "";
				}
				$newData[$key] = $value;
			}
			/*if(!isset($newData['assets'])){
				$newData['assets']="";
			}*/
			$newData['add_uid'] = $this->session->userdata('user_id');
			$newData['add_date'] = gmdate("Y-m-d H:i:s");
			$this->form_model->save($newData, uri_assoc('id'));

		}

	}
	function export(){
		
		$this->load->plugin('to_excel'); 
		$this->form_model->export();
	}	
	function check_duplicates()
	{
		$groupName=uri_assoc("name");
		$id=uri_assoc("id");
		if(!$this->group_model->checkGroupDuplicate($groupName,$id))
		{
			//echo "Group Name Already Exist.";
			$this->output->set_output("Group Name Already Exist.");
		}
	}
}