<?php
	$this->db->where("user_id",$this->session->userdata("user_id"));
	$data =array(
		"facebook_uid"=>$user_profile->identifier
	);
	$this->db->update("tbl_users",$data);
	redirect("home");
		
?>
