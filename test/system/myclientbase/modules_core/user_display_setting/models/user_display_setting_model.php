<?php 
class User_display_setting_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function User_display_setting_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
    }
	function getAllUsers(){
		$user_id = $this->session->userdata('user_id');
		$opt="";
		$SQL="select user_id, first_name, last_name from tbl_users where del_date is null and del_uid is null and status=1 And admin_id = $user_id ORDER BY first_name";
		$query = $this->db->query($SQL);
		
		foreach($query->result() as $row)
		{
			$opt.="<option value='".$row->user_id."' ";
			$opt.=">".$row->first_name." ".$row->last_name."</option>";
		}
		$opt.="<option value='all' ";
		$opt.=">All</option>";
		return $opt;
	}
	function getAllUsers_ID(){
		$user_id = $this->session->userdata('user_id');
		$SQL="select user_id, first_name, last_name from tbl_users where del_date is null and del_uid is null and status=1 And admin_id = $user_id ORDER BY first_name";
		$query = $this->db->query($SQL);
		return $query->result();
	}
	
	function Users_Menu($uid){
		$chk="<table width='100%' style='margin-top:15px' id='tbl_".$uid."'><tr><td colspan='2'>";
		$chk.="<input type='hidden' value='$uid' name='userIds[]' /><input type='checkbox' id='users_check_all_".$uid."' value='all' onClick='checkAll_settings($uid)'/><label><strong>Check/Uncheck All</strong></label>";
		$chk.="</td></tr><tr>";
		$SQL="select link, link_title from tbl_display_values where del_date is null and del_uid is null";
		$query = $this->db->query($SQL);
		$link_menu=array();
		foreach($query->result() as $row)
		{
			$link_menu[$row->link]=$row->link_title;
		}
		
		$SQL_app="select link, status from tbl_users_display_settings where uid=$uid";
		$query_app = $this->db->query($SQL_app);
		$display_setting = array();
		$link_title = array();
		foreach($query_app->result() as $row)
		{
			$id = $row->link;
			$status = $row->status;
			$display_setting[$id] = $status;
		}
		
		$i=1;
		foreach($link_menu as $key=>$display) {
			$chk.="<td width='50%'><input type='checkbox' rel=".$uid." name='display_menu[]' placeholder='".$key."' value='".$key.";".$uid."'";
			
			if(isset($display_setting[$key])){
					if($display_setting[$key]==1){
					$chk.="checked='checked'";
			}
			}else if($key=='show_map_trip_button' || $key=='show_map_landmark_button'){
					$chk.="checked='checked'";
			}
			$chk.="/><label>".$link_menu[$key]."</label></td>";
			if($i%2==0 && count($link_menu)!=$i){
			$chk.="</tr><tr>";
			}else if(count($link_menu)==$i){
			$chk.="</tr></table>";
			}
			$i++;
		}
		return $chk;
	}
	function Users_Menu_all(){
		/*$chk="<table width='100%' style='margin-top:15px' id='tbl_all'><tr><td colspan='2'>";
		$chk.="<input type='checkbox' id='users_check_all_' value='all' onClick='checkAll_settings_all(\"all\")'/><label><strong>Check/Uncheck All</strong></label>";
		$chk.="</td></tr>";*/
		$chk="<table width='100%' style='margin-top:15px' id='tbl_0'><tr><td>";
		$chk.="<input type='checkbox' id='users_check_all_0' value='all' onClick='checkAll_settings(0)'/><label><strong>Check/Uncheck All</strong></label>";
		$chk.="</td></tr><tr>";
		$SQL="select link, link_title from tbl_display_values where del_date is null and del_uid is null";
		$query = $this->db->query($SQL);
		//$link_menu=array();
		$i=1;
		$cntArr=count($query->result());
		foreach($query->result() as $row)
		{
			$chk.="<td width='50%'><input type='checkbox' rel=".$row->link."  value='".$row->link."'";
			$chk.=" onClick='checkAll_with_rel(\"".$row->link."\")'/><label>".$row->link_title." (All)</label></td>";
			if($i%2==0 && $cntArr!=$i){
			$chk.="</tr><tr>";
			}else if($cntArr==$i){
			$chk.="</tr>";
			}
			$i++;
			
		}
		return $chk;
		
	}

	function update_user(){
		$usersIds=$_REQUEST['userIds'];
		if(isset($_REQUEST['display_menu'])){
		$data=$_REQUEST['display_menu'];
		
		$menus=array();
		for($i=0;$i<count($data);$i++){
			$menus[$i]=explode(";",$data[$i]);
		}
		$indexWiseData=array();
		for($i=0;$i<count($menus);$i++){
			//$arLink[]=$menus[$i][0];
			//$arId[]=$menus[$i][1];
			if(array_key_exists($menus[$i][1],$indexWiseData)){
				$indexWiseData[$menus[$i][1]].=",'".$menus[$i][0]."'";
			}else{
				$indexWiseData[$menus[$i][1]]="'".$menus[$i][0]."'";
			}
		}
		for($i=0;$i<count($usersIds);$i++){
			if(!array_key_exists($usersIds[$i],$indexWiseData)){
				$indexWiseData[$usersIds[$i]]="";
			}
		}
	
		//$uid=$_REQUEST['user_id'];
		$session_id=$this->session->userdata('user_id');
		$add_dt=date("Y-m-d H:i:s");
		if(count($indexWiseData)>0){
			foreach($indexWiseData as $uid=>$val){
				if($uid!=""){
					$qry_count=$this->db->query("select link from tbl_display_values where del_date is null and del_uid is null and status=1 and link not in (select link from tbl_users_display_settings where uid=$uid)");
					
					$insert_qry="INSERT INTO tbl_users_display_settings (id, uid, link, status) VALUES";
					$qry_res=$qry_count->result();
					if(count($qry_res)>0){
						foreach($qry_res as $row){			
							$insert_qry.="(NULL, $uid, '$row->link',0),";
						}
						$insert_q=substr_replace($insert_qry ,"",-1);
						$qry = $this->db->query($insert_q);
					}
				}
				$qry_updt1 = $this->db->query("UPDATE `tbl_users_display_settings` SET status=0 WHERE uid=$uid");
				if($val!=""){
					$qry_updt2 = $this->db->query("UPDATE `tbl_users_display_settings` SET status=1 WHERE uid=$uid and link in($val)");
				}
			}
		return true;
		}
		else{
			return false;
		}
		}else{
			$uid=$_REQUEST['user_id'];
			$uid_ids=implode(",",$usersIds);
			$qry_count=$this->db->query("select link from tbl_display_values where del_date is null and del_uid is null and status=1 and link not in (select link from tbl_users_display_settings where uid in ($uid_ids))");
					
			$insert_qry="INSERT INTO tbl_users_display_settings (id, uid, link, status) VALUES";
			$qry_res=$qry_count->result();
			if(count($qry_res)>0){
				foreach($qry_res as $row){			
					$insert_qry.="(NULL, $uid, '$row->link',0),";
				}
				$insert_q=substr_replace($insert_qry ,"",-1);
				$qry = $this->db->query($insert_q);
			}
			$qry_updt1 = $this->db->query("UPDATE `tbl_users_display_settings` SET status=0 WHERE uid in($uid_ids)");
			return true;
		}
	}
	
}
?>
