	<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class User_menu extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('user_menu_model','',TRUE);
		$this->load->model('form_model','',TRUE);
	}
	function index()
	{
	
		$menu_name = array();
		$column_menu_name = array();
		$this->db->where("del_date" ,Null);
		$this->db->where("status" ,1);
		$query = $this->db->get("main_menu_master");
		foreach ($query->result() as $row)
		{
		   $menu_name[] = "'".$row->menu_name."'";
		   $column_menu_name[] = "{name:'".$row->menu_name."',editable:true, index:'".$row->menu_name."', width:80, align:'center', jsonmap:'".$row->menu_name."',formatter:formatter_yes_no}";
		}
		$data['menu_name'] = implode(",",$menu_name);
		$data['column_menu_name'] = implode(",",$column_menu_name);
		$this->load->view('user_menu',$data );
	}
	function loadData(){
		
		$data = $this->user_menu_model->getAllData(); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		
		
		$i=0;  
		foreach($data['result'] as $row) {  
			$SQL = "select menu_name from main_menu_master where id =". $row->menu_id;
		
			$query = $this->db->query($SQL);
			foreach($query->result() as $row1)
			{
				
				//$row->{$row1->menu_name} = $row1->menu_name;
				$row->menu_name  = $row1->menu_name;
			}	

			$responce->rows[$i] = $row;
			$i++; 
		}
		//die(print_r($responce));	
			
		echo json_encode($responce);
	}
	function form() {

		if (!$this->form_model->validate()) {
			
			$this->load->helper('form');

			if (!$_POST AND uri_assoc('id')) {

				$this->form_model->prep_validation(uri_assoc('id'));

			}
			$this->load->view('form');

		}

		else {
			$formdata = $this->form_model->db_array();			
			$formdata['add_date'] = gmdate('Y-m-d H:i:s');
			$formdata['add_uid'] = $this->session->userdata('user_id');
			$formdata['status'] = 1;
			
			if(uri_assoc('id')){
				$this->user_menu_model->save($formdata, uri_assoc('id'));
			}
		}
	}
}
?>