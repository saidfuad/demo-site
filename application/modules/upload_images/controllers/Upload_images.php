<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_images extends Base_Controller {

	function __construct() {

        parent::__construct();
		
		$this->load->model('mdl_upload_images');
        if ($this->session->userdata('itms_protocal') == "") {
           redirect('login');
        }

		if ($this->session->userdata('itms_protocal') == 71) {
            redirect('admin');
        }

		if ($this->session->userdata('itms_user_id') != "") {
           redirect('home');
        }

       
    }

    function upload_vehicle_image () {
        $this->session->set_userdata('vehicle_image', '');

        $dir = "./uploads/vehicles/";
        $formElement='file';
                
        $save_image = $this->mdl_upload_images->openImage($dir, $formElement);
        $imgName = $this->mdl_upload_images->NewImageName;
        $imgMime = ltrim($Obj->imageMimeType, ".");
        
        $this->session->set_userdata('vehicle_image', $imgName);
    }

    function upload_personnel_image () {
        $this->session->set_userdata('personnel_pic', '');

        $dir = "./uploads/personnel/";
        $formElement='file';
                
        $save_image = $this->mdl_upload_images->openImage($dir, $formElement);
        $imgName = $this->mdl_upload_images->NewImageName;
        $imgMime = ltrim($Obj->imageMimeType, ".");
        
        $this->session->set_userdata('personnel_pic', $imgName);
    }

    function upload_user_image () {
        $this->session->set_userdata('user_image', '');

        $dir = "./uploads/users/";
        $formElement='file';
                
        $save_image = $this->mdl_upload_images->openImage($dir, $formElement);
        $imgName = $this->mdl_upload_images->NewImageName;
        $imgMime = ltrim($Obj->imageMimeType, ".");
        
        $this->session->set_userdata('user_image', $imgName);
    }
    
    function upload_company_logo () {
        $this->session->set_userdata('company_logo', '');

        $dir = "./uploads/companies/";
        $formElement='file';
                
        $save_image = $this->mdl_upload_images->openImage($dir, $formElement);
        $imgName = $this->mdl_upload_images->NewImageName;
        $imgMime = ltrim($Obj->imageMimeType, ".");
        
        $this->session->set_userdata('company_logo', $imgName);
    }

	
}
