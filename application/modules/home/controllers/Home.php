<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MX_Controller {

	function __construct() {

        parent::__construct();

        if ($this->session->userdata('itms_protocal') == "") {
            redirect('login');
        }
		
		if ($this->session->userdata('itms_protocal') == 71) {
            redirect('admin');
        }

		if ($this->session->userdata('itms_user_id') != "") {
           redirect(home);
        }


        $this->load->model('mdl_fetch');
        $this->load->model('main/mdl_main');
        $this->load->model('settings/mdl_landmarks');

       
    }
	
	public function index() {
        $company_id =$this->session->userdata('itms_company_id');
        $data['vehicles'] = $this->mdl_fetch->get_vehicles($company_id);
        $data['alerts'] = $this->get_alerts_updates();
        $data['count_groups'] = $this->mdl_fetch->count_groups();
        $data['count_users'] = $this->mdl_fetch->count_users();
        $data['count_devices'] = $this->mdl_fetch->count_devices();

        $data['count_untracked'] = $this->mdl_fetch->count_untracked_assets ($company_id);
        $data['count_available_devices'] = $this->mdl_fetch->count_available_devices ($company_id);
        $data['count_unassigned_groups'] = $this->mdl_fetch->count_unassigned_groups($company_id);
        $data['count_unassigned_users'] = $this->mdl_fetch->count_unassigned_users($company_id);


       
        $data['a_v_num'] = $this->mdl_fetch->veh($company_id);
        $data['a_d_num'] = $this->mdl_fetch->dri($company_id);

        $data['content_url'] = 'home';
        $data['fa'] = 'fa fa-dashboard';
        $data['title'] = 'ITMS Africa | Dashboard';
        $data['content_title'] = 'Dashboard';
        $data['content_subtitle'] = 'Fleet Management';
        $data['content'] = 'home/dashboard.php';
        
        $this->load->view('main/main.php', $data);
        $this->load->model('settings/mdl_landmarks');
		
	}

    public function trips(){

        $sql = $this->db->query('SELECT 
        COUNT(CASE WHEN is_complete = 0 THEN 1
                   ELSE NULL
              END) AS not_started,
        COUNT(CASE WHEN is_complete = 1 THEN 1
                    ELSE NULL
               END) AS started,
        COUNT(CASE WHEN is_complete = 2 THEN 1
                    ELSE NULL
               END) AS completed

     FROM itms_trips');
        $data= $sql->result_array();

        $result = array();
        foreach($data as $value){
            if (array_key_exists("not_started",$value)) {
                array_push($result, array('label'=>'Not Started','value' => $value['not_started'] ));
            } 
            if (array_key_exists("started", $value)) {
                array_push($result, array('label'=>'Started','value' => $value['started'] ));
            }
            if (array_key_exists("completed", $value)) {
                array_push($result, array('label'=>'Completed','value' => $value['completed'] ));
            }
        }
        echo json_encode($result);
    }

    public function graphs(){
        $company_id =$this->session->userdata('itms_company_id');
        $data['untracked_vehicles'] = $this->mdl_fetch->count_untracked_assets ($company_id);
        $data['available_devices'] = $this->mdl_fetch->count_available_devices ($company_id);
        $data['unassigned_groups'] = $this->mdl_fetch->count_unassigned_groups($company_id);
        $data['unassigned_users'] = $this->mdl_fetch->count_unassigned_users($company_id);

        $result1 = array('label'=>"Untracked Vehicles",'data'=>$data['untracked_vehicles']);
        $result2 = array('label'=>"Available Devices",'data'=>$data['available_devices']);
        $result3 = array('label'=>"Unassigned Groups",'data'=>$data['unassigned_groups']);
        $result4 = array('label'=>"Unassigned Users",'data'=>$data['unassigned_users']);

        $result = array($result1,$result2,$result3,$result4);
        
        echo json_encode($result);
    }

    public function vehicle(){

        $sql = $this->db->query('SELECT
        COUNT(CASE WHEN ignition = 0 AND speed = 0 THEN 1
                   ELSE NULL
              END) AS stopped,
        COUNT(CASE WHEN ignition = 0 AND speed > 0 THEN 1
                    ELSE NULL
               END) AS idle,
        COUNT(CASE WHEN ignition = 1 AND speed > 0 THEN 1
                    ELSE NULL
               END) AS moving

     FROM itms_last_gps_point');
        $data= $sql->result_array();
        
                $result = array();
        foreach($data as $value){
            if (array_key_exists("moving", $value)) {
                array_push($result, array('label'=>'Moving','data' => $value['moving'] ));

            } 
            
            if (array_key_exists("idle", $value)) {
                array_push($result, array('label'=>'Idle','data' => $value['idle'] ));

            }
            if (array_key_exists("stopped",$value)) {
                array_push($result, array('label'=>'Stopped','data' => $value['stopped'] ));
            }

        }
        echo json_encode($result);
    }

    function get_alerts_updates () {
        $viewed = 0;
        $limit = 10;
        $results = $this->mdl_main->fetch_alerts($this->session->userdata('itms_company_id'), $viewed, $limit);

        $this->load->library('timefactory');
        //echo json_encode($results);
        //$data = array('data'=>$results, 'views_not'=>);
        $count = 0;
        $popstu = array();

        //print_r('<pre>');
        //print_r($results);
        $now = strtotime(date('Y-m-d H:i:s'));

        foreach ($results as $key=>$alert) {
            if ($alert->viewed == 0) {
                $count++;
            }

            if($alert->pop_shown == 0) {
                array_push($popstu, $alert->id);
            }


            $add_date = $alert->add_date;
            $add_unix_date = strtotime($add_date);

            $diff = $now - $add_unix_date;

            if ($diff > 86400) {
                $at = date('jS M Y H:i:s', $add_unix_date);
            } else {
                $at = $this->timefactory->secs_to_time ($diff) ;
                $at = $at . 'ago';
            }
 
            $alert->add_date = $at;

        }

        if (sizeof($popstu)) {
            $this->mdl_main->update_pops($popstu);
        }
        

        $data = $results;
        return $data;
    }


    function get_company_landmarks () {
        $rows = $this->mdl_landmarks->get_landmarks($this->session->userdata('itms_company_id'));

        echo json_encode($rows);
    }

    public function get_company_zones () {
        $zones = $this->mdl_fetch->get_zones($this->session->userdata('itms_company_id'));
        $vertices = $this->mdl_fetch->get_vertices($this->session->userdata('itms_company_id'));

        $data['zones'] = $zones;
        $data['vertices'] = $vertices;

        echo json_encode($data);
    }

    public function test(){
        $data['vehicles'] = $this->mdl_fetch->get_vehicles($this->session->userdata('itms_company_id'));

        var_dump($data);
        print_r($data);
        die();
    }

    public function total_vehicles(){
        $data['vehicles'] = $this->mdl_fetch->get_vehicles($this->session->userdata('itms_company_id'));

        $data['content_url'] = 'home';
        $data['fa'] = 'fa fa-dashboard';
        $data['title'] = 'ITMS Africa | Total Vehicles';
        $data['content_title'] = 'Total Vehicles';
        $data['content_subtitle'] = 'Fleet Management';
        $data['content'] = 'home/vehicles.php';
        $this->load->view('main/main.php', $data);
    }

    public function total_drivers(){
        $data['personnel'] = $this->mdl_fetch->total_drivers();

        $data['content_url'] = 'home';
        $data['fa'] = 'fa fa-dashboard';
        $data['title'] = 'ITMS Africa | Total Drivers';
        $data['content_title'] = 'Total Drivers';
        $data['content_subtitle'] = 'Fleet Management';
        $data['content'] = 'home/drivers.php';
        $this->load->view('main/main.php', $data);
    }
}
