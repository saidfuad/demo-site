	<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Top_menu_master extends Admin_Controller {
	
	function __construct() {
		parent::__construct(TRUE);
		$this->load->model('top_menu_master_model','',TRUE);
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
		$this->load->view('top_menu_master',$data );
	}
	function loadData(){
		
		$data = $this->top_menu_master_model->getAllData(); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		
		
		$i=0;  
		foreach($data['result'] as $row) {  
			$SQL = "select Text from top_main_menu_master where id =". $row->menu_id;
		
			$query = $this->db->query($SQL);
			foreach($query->result() as $row1)
			{
				
				//$row->{$row1->menu_name} = $row1->menu_name;
				$row->Text  = $row1->Text;
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
		
			if(!empty($_REQUEST['menu_status']))
			{
				$status=$_REQUEST['menu_status'];
				$formdata['status'] = 1;
				//$formdata['user_id'];
			}else{
				$formdata['status'] = 0;
				
			}
			if(uri_assoc('id')){
			
				$this->top_menu_master_model->save($formdata, uri_assoc('id'));
			}
		}
	}
	public function checkstatus(){
		$this->top_menu_master_model->check_status();
	}
}
?>