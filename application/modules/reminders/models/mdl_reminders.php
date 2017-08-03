<?php

class Mdl_reminders extends CI_Model{

    function __construct () {
        parent::__construct();
        $this->load->library('sendmail');
        $this->load->library('smssend');
    }

    public function fetch_reminders($account_id, $reminder_type_id){
        $this->db->select('reminders.*');
        $this->db->from('reminders');
         if($this->config->item('licenses') == $reminder_type_id)
        {
            $this->db->select('first_name,last_name, company_name');
            $this->db->join('users', 'users.user_id = reminders.driver_id');
        }
        else
        {    $this->db->select('vehicles.plate_no');
             $this->db->join('vehicles', 'vehicles.vehicle_id = reminders.vehicle_id');
        }
        $this->db->join('reminder_types', 'reminder_types.reminder_type_id = reminders.reminder_type_id');
        $this->db->where('reminders.account_id',$account_id);
        $this->db->where('reminders.reminder_type_id',$reminder_type_id);
        $this->db->where('reminders.status',1);

        $this->db->order_by('reminders.date_created', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    public function fetch_reminder($id){
        $this->db->select('reminders.*,vehicles.model, vehicles.plate_no,reminder_types.reminder_name');
        $this->db->from('reminders');
        $this->db->join('vehicles', 'vehicles.vehicle_id = reminders.vehicle_id');
        $this->db->join('reminder_types', 'reminder_types.reminder_type_id = reminders.reminder_type_id');
        $this->db->where('reminders.reminder_id',$id);
        $this->db->where('reminders.status',1);

        $query = $this->db->get();
        return $query->row_array();
    }

    public function fetch_license($id){
        $this->db->select('reminders.*,users.first_name,users.last_name,users.user_id');
        $this->db->from('reminders');
        $this->db->join('users', 'users.user_id = reminders.driver_id');
        $this->db->join('reminder_types', 'reminder_types.reminder_type_id = reminders.reminder_type_id');
        $this->db->where('reminders.reminder_id',$id);
        $this->db->where('reminders.status',1);

        $query = $this->db->get();
        return $query->row_array();
    }

    public function update_rem($data){
        $this->db->where('reminder_id', $data['reminder_id']);
        $query = $this->db->update('reminders', $data);

        if ($query) {
            return true;
        }

        return false;
    }

    public function update_remdate($reminder_id){
        $this->db->where('reminder_id', $reminder_id);
        $this->db->set('status',0);
        $query = $this->db->update('reminders');
    }

    public function save_rem($data){
        $query = $this->db->insert('reminders', $data);
        if ($query) {
            return true;
        }
       return false;
    }
    
    function sendMail($emeil,$subj,$message)
    {
        $config = Array(
      'protocol' => 'smtp',
      'smtp_host' => 'ssl://smtp.googlemail.com',
      'smtp_port' => 465,
      'smtp_user' => 'rheinadrops@gmail.com', // change it to yours
      'smtp_pass' => 'try12345678', // change it to yours
      'mailtype' => 'html',
      'charset' => 'iso-8859-1',
      'wordwrap' => TRUE
    );
          $this->load->library('email', $config);
          $this->email->set_newline("\r\n");
          $this->email->from('rheinadrops@gmail.com', 'HAWK System'); // change it to yours
          $this->email->to($emeil);// change it to yours
          $this->email->subject($subj);
          $this->email->message($message);
          $this->email->send();
        //   if()
        //  {
        //   echo true;
        //  }
        //  else
        // {
        //     echo false;
        //  show_error($this->email->print_debugger());
        // }
    }

    public function send_remsms($id){
        $user_data=$this->mdl_users->get_user_by_id($this->session->userdata('hawk_user_id'));
        $reminder = $this->fetch_reminder($id);

        if(!empty($reminder)){

        if(!empty($user_data['email'])){
        //$to = array($user_data['email']);
        $subj = "Hawk - Reminder";
        $url = base_url();
        
        $message = '<div class="" style="margin-left:100px;width:500px; position:fixed; top:100px; left:30%;background:#f5f5f5;">
            <div style="background:#101010;border-bottom:6px solid #18bc9c;padding:10px;text-align: center;">
                <h1>Reminder Details</h1>
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

        $this->sendmail->send_mail($user_data['email'],$subj,$message);

        }


        $recipient = array($user_data['phone_no']);
        $message = "HAWK Reminder.\r\nReminder For:".$reminder['reminder_name'].".\r\nApplies To Vehicle  : ". $reminder['model'] .' - '.$reminder['plate_no'].".\r\nCompany Name To Pay : " . $reminder['company'] . " \r\nAmount To Pay : " . $reminder['amount_to_pay'] . "";

        $res = $this->smssend->send_text_message($recipient, $message);
    }
    }

    public function get_reminder_types()
    {
        $this->db->select('reminder_type_id','reminder_name');
        $query = $this->db->get('reminder_types');
        return $query->result();
    }

}

?>
