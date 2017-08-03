<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * My_Controller: A class to put reusable functions in e.g. logged in etc
 *
 * @author Benson
 */
class Base_Controller extends CI_Controller {

    private $ntsa_allowed_urls;
    private $ntsa_allowed_functions;

    function __construct() {

        parent::__construct();
        $this->is_logged_in();
        $this->ntsa_allowed_urls = array('gps_tracking','vehicles','gps_history');
        $this->ntsa_allowed_functions = array('index','refresh_grid','filter_grid','history','fetch_vehicle','view_history','view_playback');
        $this->check_allowed_urls();
        $this->check_allowed_functions();
        
    }

    /**
     * Function to check if a user is logged in
     */
    function is_logged_in() {
        if ($this->session->userdata('hawk_user_type_id') == "") {
            redirect('login');
        }
    }
    
    function check_allowed_urls(){
        if($this->session->userdata('hawk_user_type_id') == '7'){
            if(!in_array($this->uri->segment(1),$this->ntsa_allowed_urls)){
                redirect('gps_tracking');
            }
        }
    }
    
    function check_allowed_functions(){
        if($this->session->userdata('hawk_user_type_id') == '7'){
            if(!in_array($this->router->method,$this->ntsa_allowed_functions)){
                redirect('gps_tracking');
            }
        }
    }

}
