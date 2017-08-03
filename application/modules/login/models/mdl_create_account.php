<?php

class Mdl_Create_Account extends CI_Model{

      public function open_account($account){

        $query = $this->db->insert('accounts', $account);

        if($query){
            $insertaid= $this->db->insert_id();
            $account_name="Hawk_$insertaid";

            $sql="UPDATE accounts SET account_name = '".$account_name."' WHERE account_id ='".$insertaid."'";
            $this->db->query($sql);

            return $insertaid;
        }

        return false;

    }

    public function save_account($select, $data){

        if($select == 1){

            if ($this->check_user_phone_no($data['phone_no'])) {
                return 2;
                exit;
            }

            if ($this->check_user_email($data['email'])) {
                return 3;
                exit;
            }

        }else{

            if ($this->check_company_phone_no($data['company_phone_no'])) {
                return 4;
                exit;
            }

            if ($this->check_company_email($data['company_email'])) {
                return 5;
                exit;
            }

        }

        $query = $this->db->insert('users', $data);
        $inserted = $this->db->insert_id();

        $sql="UPDATE users SET add_uid = '".$this->db->insert_id()."' WHERE user_id ='".$this->db->insert_id()."'";
        $this->db->query($sql);

        if ($query) {
            return $inserted;
        }

        return false;

    }

    public function check_user_phone_no($phone_no){

        $query = $this->db->get_where('users', array('phone_no'=>$phone_no));

        if ($query->num_rows() > 0) {
            return true;
        }

        return false;
    }

    public function check_user_email($email){

        $query = $this->db->get_where('users', array('email'=>$email));

        if ($query->num_rows() > 0) {
            return true;
        }

        return false;

    }

    public function check_company_phone_no($phone_no){

        $query = $this->db->get_where('users', array('company_phone_no'=>$phone_no));

        if ($query->num_rows() > 0) {
            return true;
        }

        return false;

    }

    public function check_company_email($email){

        $query = $this->db->get_where('users', array('company_email'=>$email));

        if ($query->num_rows() > 0) {
            return true;
        }

        return false;
    }

    public function save_logins($logins){

        if ($this->check_login_email($logins['email'])) {
            return 3;
            exit;
        }

        $query = $this->db->insert('logins', $logins);
        if($query){
            return 1;
        }

        return false;

    }

    public function check_login_email($email){

        $query = $this->db->get_where('logins', array('email'=>$email));

        if ($query->num_rows() > 0) {
            return true;
        }

        return false;

    }


}

?>
