<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reminders extends Base_Controller {

	function __construct() {
        parent::__construct();
        $this->account_id = $this->session->userdata('hawk_account_id');
        $this->user_id = $this->session->userdata('hawk_user_id');
       
        $this->load->library('encrypt');
        $this->load->model('mdl_reminders');
        $this->load->model('users/mdl_users');
        $this->load->model('accounting/mdl_accounting');
        $this->load->model('vehicles/mdl_vehicles');
        $this->load->library('sendmail');
        $this->load->library('smssend');

         $this->vehicles =  $this->mdl_vehicles->get_vehicles($this->account_id);
    }

    public function index(){
        $data['insurances'] = $this->mdl_reminders->fetch_reminders( $this->account_id,$this->config->item('insurances'));
        $data['licenses'] = $this->mdl_reminders->fetch_reminders( $this->account_id,$this->config->item('licenses'));
        $data['services'] =  $this->mdl_reminders->fetch_reminders( $this->account_id,$this->config->item('services'));
        $data['permit'] = $this->mdl_reminders->fetch_reminders( $this->account_id,$this->config->item('permit'));
 
        $data['content_url'] = 'Reminders';
        $data['fa'] = 'fa fa-sitemap';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'Hawk | View Reminders';
        $data['content_title'] = 'View Reminders';
        $data['content_subtitle'] = 'Click the tabs below to see the list of your set reminders';
        $data['content'] = 'reminders/view_reminders.php';
        $this->load->view('main/main.php', $data);
    }

   

    public function add_insurance() {

        $data ['accountid'] =  $this->account_id;
        $data ['uid'] = $this->user_id;
        $data ['vehicles'] = $this->vehicles;
        $data['content_url'] = 'reminders/add_insurance';
        $data['fa'] = 'fa fa-plus';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Add Insurance Reminder';
        $data['content_title'] = 'Add Insurance Reminder';
        $data['content_subtitle'] = '';
        $data['content'] = 'reminders/add_insurance.php';
        $this->load->view('main/main.php', $data);
    }

      public function add_license() {

        $data ['accountid'] =  $this->account_id;
        $data ['uid'] = $this->user_id;
        $data ['drivers'] = $this->mdl_users->get_users( $this->account_id);
        $data['content_url'] = 'reminders/add_license';
        $data['fa'] = 'fa fa-plus';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Add License Reminder';
        $data['content_title'] = 'Add License Reminder';
        $data['content_subtitle'] = '';
        $data['content'] = 'reminders/add_license.php';
        $this->load->view('main/main.php', $data);
    }

      public function add_service() {

        $data ['accountid'] =  $this->account_id;
        $data ['uid'] = $this->user_id;
        $data ['vehicles'] = $this->vehicles;
        $data['content_url'] = 'reminders/add_service';
        $data['fa'] = 'fa fa-plus';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Add Service Reminder';
        $data['content_title'] = 'Add Service Reminder';
        $data['content_subtitle'] = '';
        $data['content'] = 'reminders/add_service.php';
        $this->load->view('main/main.php', $data);
    }

        public function add_permit() {

        $data ['accountid'] =  $this->account_id;
        $data ['uid'] = $this->user_id;
        $data ['vehicles'] = $this->vehicles;
        $data['content_url'] = 'reminders/add_permit';
        $data['fa'] = 'fa fa-plus';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Add Permit Reminder';
        $data['content_title'] = 'Add Permit Reminder';
        $data['content_subtitle'] = '';
        $data['content'] = 'reminders/add_permit.php';
        $this->load->view('main/main.php', $data);
    }


    public function save_reminder(){
        $data=$this->input->post();
        $data['status']=1;
        
        $dat['vehicle_id']=$this->input->post('vehicle_id');
        $dat['expense_type_id']=$this->input->post('reminder_type_id');
        $dat['amount']=$this->input->post('amount_to_pay');
        $dat['account_id']=$this->input->post('account_id');
        $dat['add_uid']=$this->input->post('add_uid');

        $this->mdl_accounting->save_expense($dat);

        return $this->mdl_reminders->save_rem($data);
    }

    public function update_reminder(){
        $data=$this->input->post();

        return $this->mdl_reminders->update_rem($data);
    }

    public function edit_insurance($id) {

        $data ['accountid'] =  $this->account_id;
        $data ['uid'] = $this->user_id;
        $data ['vehicles'] = $this->vehicles;
        $data ['reminder'] = $this->mdl_reminders->fetch_reminder($id);
        $data['content_url'] = 'reminders/edit_insurance';
        $data['fa'] = 'fa fa-plus';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Edit Insurance Reminder';
        $data['content_title'] = 'Edit Insurance Reminder';
        $data['content_subtitle'] = '';
        $data['content'] = 'reminders/edit_insurance.php';
        $this->load->view('main/main.php', $data);
    }

      public function edit_license($id) {

        $data ['accountid'] =  $this->account_id;
        $data ['uid'] = $this->user_id;
        $data ['drivers'] = $this->mdl_users->get_users($this->account_id);
        $data ['reminder'] = $this->mdl_reminders->fetch_license($id);
        $data['content_url'] = 'reminders/edit_license';
        $data['fa'] = 'fa fa-plus';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Edit License Reminder';
        $data['content_title'] = 'Edit License Reminder';
        $data['content_subtitle'] = '';
        $data['content'] = 'reminders/edit_license.php';
        $this->load->view('main/main.php', $data);
    }

      public function edit_service($id) {
        $data ['accountid'] =  $this->account_id;
        $data ['uid'] = $this->user_id;
        $data ['vehicles'] = $this->vehicles;
        $data ['reminder'] = $this->mdl_reminders->fetch_reminder($id);
        $data['content_url'] = 'reminders/edit_service';
        $data['fa'] = 'fa fa-plus';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Edit Service Reminder';
        $data['content_title'] = 'Edit Service Reminder';
        $data['content_subtitle'] = '';
        $data['content'] = 'reminders/edit_service.php';
        $this->load->view('main/main.php', $data);
    }

    public function edit_permit($id) {
        $data ['accountid'] =  $this->account_id;
        $data ['uid'] = $this->user_id;
        $data ['vehicles'] = $this->vehicles;
        $data ['reminder'] = $this->mdl_reminders->fetch_reminder($id);
        $data['content_url'] = 'reminders/edit_permit';
        $data['fa'] = 'fa fa-plus';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Edit Permit Reminder';
        $data['content_title'] = 'Edit Permit Reminder';
        $data['content_subtitle'] = '';
        $data['content'] = 'reminders/edit_permit.php';
        $this->load->view('main/main.php', $data);
    }

    public function send_remsms($id){
        $userdet=$this->mdl_reminders->get_user_by_id($this->user_id);
        $reminder = $this->mdl_reminders->fetch_reminder($id);
        if(!empty($userdet['email'])){
        //$to = array($userdet['email']);
        $subj = "Hawk - Reminder";
        $url = "http://40.68.162.157:9090/hawk/index.php";
        
        $message = '<div class="" style="margin-left:100px;width:500px; position:fixed; top:100px; left:30%;background:#f5f5f5;">
            <div style="background:#101010;border-bottom:6px solid #18bc9c;padding:10px;text-align: center;">
                <h1>FeedBack Details</h1>
            </div>
            <div style="padding:20px;">
                Dear User,<br><br>
                Please Read The Reminder Below.
                <br>
                <br>
                Reminder Details<br>
                Reminder For : ' . $reminder['reminder_name'] . '<br>
                Vehicle Plate Number That The Reminder Applies To is: ' . $reminder['model'] .' - '.$reminder['plate_no'] . ' <br>
                Company Name To Pay : ' . $reminder['company'] . '<br>
                Amount To Pay : ' . $reminder['amount_to_pay'] . '<br>
                <br>
                <br>
                Verify carefully the Company information.
                <br>
                In case of any doubts, please feel free to contact HAWK Registrar.<br>
                <a href="#">info@svs.com</a> on or <a href="#">+254 (0)729 220 777</a>
                <br>                        
            </div>
        </div>';
        
        $this->sendmail->send_mail($userdet['email'],$subj,$message);

        }

        $recipient = array($userdet['phone_no']);
        $message = "HAWK Reminder.\r\nReminder For:".$reminder['reminder_name'].".\r\nApplies To Vehicle  : ". $reminder['model'] .' - '.$reminder['plate_no'].".\r\nCompany Name To Pay : " . $reminder['company'] . " \r\nAmount To Pay : " . $reminder['amount_to_pay'] . "";

        $res = $this->smssend->send_text_message($recipient, $message);
    }

}
