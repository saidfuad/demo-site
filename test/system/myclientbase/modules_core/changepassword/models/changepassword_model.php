<?php 
class changepassword_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function changepassword_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_users = "tbl_users";
    }
	public function change_password(){
		$oldPass=$_REQUEST['users_oldPass'];
		$newPass=$_REQUEST['users_newPass'];
		$confPass=$_REQUEST['users_confPass'];
		
		if($newPass==$confPass){
			$this->db->where("user_id",$this->session->userdata('user_id'));
			$this->db->where("Password",md5($oldPass));
			$this->db->from('tbl_users');
			if($this->db->count_all_results()<1)
			{
				return '{"status":"0"}';
			}
			else
			{
				$this->db->where("user_id",$this->session->userdata('user_id'));
				$data = array(
					   'Password' => md5($newPass)
					);
			$this->db->update('tbl_users',$data);
			return '{"status":"1"}';
			}
		}else{
			return '{"status":"-1"}';
		}
	}	
} 
?>