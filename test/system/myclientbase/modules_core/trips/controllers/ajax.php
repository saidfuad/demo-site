<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax extends Controller {

	function Ajax ()
	{
		parent::Controller();	
		$this->load->model('group_model');
		$this->load->library('flexigrid');
	}
	
	function allgroup() {
		
		//List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('id', 'group_name', 'assets');
		
		$this->flexigrid->validate_post('id','asc',$valid_fields);

		$records = $this->group_model->get_group();
		
		$this->output->set_header($this->config->item('json_header'));
		
		/*
		 * Json build WITH json_encode. If you do not have this function please read
		 * http://flexigrid.eyeviewdesign.com/index.php/flexigrid/example#s3 to know how to use the alternative
		 */
		$record_items = array();
		foreach ($records['records']->result() as $row)
		{
			$record_items[] = array($row->id,
			$row->id,
			$row->group_name,
			$row->assets
			);
			
		}
		//Print please
		$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
	}
	function deletec()
	{
		$post_array = explode(",",$this->input->post('items'));
		
		foreach($post_array as $index => $group_id){
			if (is_numeric($group_id) && $group_id > 1) 
				$this->group_model->delete_group($group_id);
		}				
			
		$error = "Selected Group (id's: ".$this->input->post('items').") deleted with success";

		$this->output->set_header($this->config->item('ajax_header'));
		$this->output->set_output($error);
	}
}
?>