<?php 

class Mdl_admin extends CI_Model {

	function get_company_details ($company_id) {
		$this->db->join('itms_users', 'itms_users.company_id=itms_companies.company_id');
		$q = $this->db->get_where('itms_companies', array('itms_companies.company_id'=>$company_id, 'itms_users.protocal'=>7));

		return $q->row_array();

	}

	public function get_company_services_subscriptions ($company_id)
	{
        
        $query = $this->db->query("SELECT itms_services.* , itms_services_subscriptions.start_date, 												itms_services_subscriptions.expiry_date FROM 																itms_services_subscriptions
        							JOIN  itms_services ON (itms_services_subscriptions.service_id = itms_services.service_id)
        							WHERE 1
        							AND
        							itms_services_subscriptions.company_id = '$company_id'");
        

        return $query->result();
	}

    function update_service_subscription($services) {

        $this->db->trans_start();

        foreach ($services as $key => $service) {
            $data = array('company_id'=>$service['company_id'], 'service_id'=>$service['service_id']);
            $this->db->where($data);
            $this->db->update('itms_services_subscriptions', $service);
        }
        
        $this->db->trans_complete();

        if ($this->db->trans_status() === true)
        {
            return true;
        }

        return false;
    }

	function save_company ($data) {
        
        if ($this->check_company_phone_number($data['company_phone_no_1'])) {
            return 'phone_exists';
            exit;
        }

        if ($this->check_company_email($data['company_email'])) {
            return 'email_exists';
            exit;
        }

        $query = $this->db->insert('itms_companies', $data);

        if ($query) {
            //$this->session->set_userdata('vehicle_image', '');
            return $this->db->insert_id();
        }
        
        return false;
        
    }


    function create_services_subscriptions ($company_id, $services) {

    	$this->db->trans_start();

    	foreach ($services as $key => $value) {
    		$data['company_id'] = $company_id;
    		$data['service_id'] = $value;
    		$q = $this->db->get_where('itms_services_subscriptions', $data);
    		if ($q->num_rows()==0){
    			$this->db->insert('itms_services_subscriptions', $data);
    		}
    	}

		$this->db->trans_complete();

		if ($this->db->trans_status() === true)
		{
		    return true;
		}

    	return false;
    }

    function create_company_admin ($data) {
    	$q = $this->db->get_where('itms_users', array('username', $data['username']));

    	if ($q->num_rows()==0){
			$query = $this->db->insert('itms_users', $data);
		}

    	if ($query) {
            //$this->session->set_userdata('vehicle_image', '');
            return $this->db->insert_id();;
        }
        
        return false;

    }

    function create_menus($admin_id, $company_id, $services) {
    	$menus = $this->mdl_admin->get_menus();

    	$menu_array = array ();

    	foreach ($services as $key => $service) {
    		foreach ($menus as $k => $menu) {
    			if (in_array($service, explode(',', $menu->related_service_id))) {
    				array_push($menu_array, $menu->menu_id);
    			}
    		}
    	}

    	$now = date('Y-m-d H:i:s');

    	$this->db->trans_start();

    	foreach ($menu_array as $key => $value) {
    		$data = array();
    		$data['user_id'] = $admin_id;
    		$data['company_id'] = $company_id;
    		$data['menu_id'] = $value;
    		
    		$q = $this->db->get_where('itms_menu_permissions', $data);
    		$data ['date_created'] = $now;

            //print_r('<pre>');
            //print_r($data);


    		if ($q->num_rows()==0){
    			$this->db->insert('itms_menu_permissions', $data);
    		}
		}

         //exit;

    	$this->db->trans_complete();

		if ($this->db->trans_status() === true)
		{
		    return true;
		}

    	return false;
    }

    function create_reports($admin_id, $company_id, $services) {
    	$reports = $this->mdl_admin->get_reports();

    	$report_array = array ();

    	foreach ($services as $key => $service) {
    		foreach ($reports as $k => $report) {
    			if (in_array($service, explode(',', $report->service_id)) || $report->service_id == 'all') {
    				array_push($report_array, $report->report_id);
    			}
    		}
    	}

    	$now = date('Y-m-d H:i:s');

    	$this->db->trans_start();

    	foreach ($report_array as $key => $value) {
    		$data = array();
    		$data['user_id'] = $admin_id;
    		$data['company_id'] = $company_id;
    		$data['report_id'] = $value;
    		
    		$q = $this->db->get_where('itms_report_permissions', $data);
    		$data ['date_created'] = $now;

    		if ($q->num_rows()==0){
    			$this->db->insert('itms_report_permissions', $data);
    		}
		}

    	$this->db->trans_complete();

		if ($this->db->trans_status() === true)
		{
		    return true;
		}

    	return false;
    }


    function get_menus() {
    	$q = $this->db->get('itms_menus');

    	return $q->result();
    }

    function get_reports() {
    	$q = $this->db->get('itms_reports');

    	return $q->result();
    }

    function check_company_phone_number($phone_no) {
        $query = $this->db->get_where('itms_companies', array('company_phone_no_1'=>$phone_no));
        $q_user = $this->db->get_where('itms_users', array('phone_number'=>$phone_no));
        
        if ($query->num_rows() > 0 || $q_user->num_rows() > 0) {
            return true;
        }

        return false;
    }

    function check_company_email($email) {
        $query = $this->db->get_where('itms_companies', array('company_email'=>$email));
        $q_user = $this->db->get_where('itms_users', array('email_address'=>$email));
        
        if ($query->num_rows() > 0 || $q_user->num_rows() > 0) {
            return true;
        }

        return false;
    }
	
}

?>