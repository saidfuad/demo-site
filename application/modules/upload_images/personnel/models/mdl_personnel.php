<?php

class Mdl_personnel extends CI_Model{


	function get_personnel ($company_id=null, $role_id=null, $user_id=null){
		$whereCompany ='';
		$whereUser = "";
		$whereRole = "";
		if ($company_id!=null) {
            $whereCompany = " AND itms_personnel_master.company_id = '".$company_id."' ";
        }

        if ($user_id!=null) {
            $whereUser = " AND itms_personnel_master.add_uid = '".$user_id."' ";
        }

        if ($role_id!=null) {
            $whereRole = " AND itms_personnel_master.role_id = '".$role_id."' ";
        }

		$SQL="SELECT itms_personnel_master.*, itms_roles.role_name from itms_personnel_master
				INNER JOIN
					itms_roles ON(itms_personnel_master.role_id = itms_roles.role_id)
					WHERE del_date IS NULL AND 1
						{$whereCompany}
						{$whereUser}
						{$whereRole}";

		$rarr=$this->db->query($SQL);
		return $rarr->result();
	}

     function edit_personnel($company_id=null){

        if ($this->session->userdata('protocal') <= 7) {
                $this->db->where('itms_personnel_master.company_id', $company_id);
           }
        $this->db->where('personnel_id', $this->uri->segment(3));
        $query = $this->db->get('itms_personnel_master');

        return $query->result();
    }


    function delete_personnel($personnel_id){
        $time = new DateTime();
        $t = $time->format('Y-m-d H:i:s');

        $sql="UPDATE itms_personnel_master SET del_date = '".$t."' WHERE personnel_id ='".$personnel_id."'";
        $this->db->query($sql);
    }

		function get_roles ($company_id = null){

			$this->db->select('itms_roles.*');
			$this->db->from('itms_roles');

			/*if ($company_id != null) {

			8	$this->db->where('company_id', 1);

			} else {
				
				$company_id = $this->session->userdata('itms_company_id');
				$this->db->where('company_id', 1);
				$this->db->or_where('company_id', $company_id);

			}
                */
			$query = $this->db->get();
			return $query->result();

		}

		function get_all_roles (){

			$SQL="SELECT itms_roles.*
					FROM
						itms_roles
					WHERE 1";

			$rarr=$this->db->query($SQL);
			return $rarr->result();
		}

	function save_personnel ($data) {

        if ($this->check_personnel_id ($data['id_no'])) {
            return 77;
            exit;
        }else

        if ($this->check_personnel_phone_no ($data['phone_no'])) {
            return 78;
            exit;
        }else

        if ($this->check_personnel_email ($data['email'])) {
            return 79;
            exit;
        }else{
            $query = $this->db->insert('itms_personnel_master', $data);

            if ($query) {
                return true;
            }
        }
        return false;

    }

    function save_user ($data) {
        if ($this->check_user_id ($data['id_no'])) {
            return 'id_exists';
            exit;
        }

        if ($this->check_user_phone_no ($data['phone_number'])) {
            return 'phone_exists';
            exit;
        }

        if ($this->check_user_email ($data['email_address'])) {
            return 'email_exists';
            exit;
        }

        $query = $this->db->insert('itms_users', $data);

        if ($query) {
            return $this->db->insert_id();
        }

        return false;

    }

    function check_personnel_id ($id_no) {
        $query = $this->db->get_where('itms_personnel_master', array('id_no'=>$id_no));

        if ($query->num_rows() !=0) {
            return true;
        }

        return false;
    }

    function check_personnel_phone_no ($phone_no) {
        $query = $this->db->get_where('itms_personnel_master', array('phone_no'=>$phone_no));

        if ($query->num_rows() !=0) {
            return true;
        }

        return false;
    }

    function check_personnel_email ($email) {
        $query = $this->db->get_where('itms_personnel_master', array('email'=>$email));

        if ($query->num_rows() !=0) {
            return true;
        }

        return false;
    }

    function check_user_id ($id_no) {
        $query = $this->db->get_where('itms_users', array('id_no'=>$id_no));

        if ($query->num_rows() !=0) {
            return true;
        }

        return false;
    }

    function check_user_phone_no ($phone_no) {
        $query = $this->db->get_where('itms_users', array('phone_number'=>$phone_no));

        if ($query->num_rows() !=0) {
            return true;
        }

        return false;
    }

    function check_user_email ($email) {
        $query = $this->db->get_where('itms_users', array('email_address'=>$email));

        if ($query->num_rows() !=0) {
            return true;
        }

        return false;
    }

    function update_personnel($data){
        $this->db->where('personnel_id', $data['personnel_id']);
        $this->db->update('itms_personnel_master', $data);
    }

    function update_users ($data){
        $this->db->where('user_id', $data['user_id']);
        $this->db->update('itms_users', $data);
    }
}

?>
