<?php
	if($this->session->userdata("id")==""){
		$query ="select * from users where linked_uid='".$user_profile->identifier."'";
		$res = $this->db->query($query);
		if($res->num_rows()==1){
			$row =$res -> result_Array();
			$row = $row[0];
			$this->load->model('sessions/mdl_auth');
			$user = $this->mdl_auth->auth('users', 'email_address', 'password', $row['email_address'], $row['password']);
			if ($user){
				if($user->verified==1){
					$object_vars = array('id', 'last_name','email_address', 'first_name','photo');
					$this->mdl_auth->set_session($user, $object_vars, array('is_admin'=>TRUE, 'username'=>$this->input->post('username')));
					redirect('user'); 
				}else{
					$this->messages->add('Please Verify Email First', 'error');
				}
			}else{
				$this->messages->add('Invalid Username or password!', 'error');
			}
			redirect("sessions/login");
		}else{
			redirect("signup/linkedsignup/".$user_profile->identifier.",".$user_profile->firstName.",".$user_profile->lastName.",".$user_profile->emailVerified.",".$user_profile->gender);
		}
	}else{
		$this->db->where("id",$this->session->userdata("id"));	
		$data =array(
			"linked_uid"=>$user_profile->identifier
		);
		$this->db->update("users",$data);
		redirect("home");
	}
?>
