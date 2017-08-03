<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax extends Controller {

	function Ajax ()
	{
		parent::Controller();	
		$this->load->model('users_model');
		$this->load->library('flexigrid');
	}
	
	function allusers() {
		
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('user_id', 'first_name', 'last_name');
		
		$this->flexigrid->validate_post('user_id','asc',$valid_fields);

		$records = $this->users_model->get_users();
		
		$this->output->set_header($this->config->item('json_header'));
		
		/*
		 * Json build WITH json_encode. If you do not have this function please read
		 * http://flexigrid.eyeviewdesign.com/index.php/flexigrid/example#s3 to know how to use the alternative
		 */
		$record_items = array();
		foreach ($records['records']->result() as $row)
		{
			$record_items[] = array($row->user_id,
			$row->user_id,
			$row->first_name,
			$row->last_name,
			$row->username,
			$row->address,
			$row->city,
			$row->state,
			$row->country,
			$row->zip,
			$row->phone_number,
			$row->fax_number,
			$row->mobile_number,
			$row->email_address,
			$row->web_address,
			$row->company_name,
			date('d.m.Y h:i a', strtotime($row->from_date)),
			date('d.m.Y h:i a', strtotime($row->to_date))
			);
												
		}
		//Print please
		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
	}
	function deletec()
	{
		$post_array = explode(",",$this->input->post('items'));
		
		foreach($post_array as $index => $users_id){
			if (is_numeric($users_id) && $users_id > 1) 
				$this->users_model->delete_users($users_id);
		}				
			
		$error = "Selected Users (id's: ".$this->input->post('items').") deleted with success";

		$this->output->set_header($this->config->item('ajax_header'));
		$this->output->set_output($error);
	}
}
?>