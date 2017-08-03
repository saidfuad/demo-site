<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback extends Base_Controller {

	function __construct() {

        parent::__construct();

        $this->load->library('encrypt');

        $this->load->model('mdl_feedback');
        $this->load->library('emailsend');
        $this->load->model('vehicles/mdl_vehicles');

    }

    public function index(){

       $data ['uid'] = $this->session->userdata('hawk_user_id');

        $data['content_url'] = 'Alerts';
        $data['fa'] = 'fa fa-sitemap';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'Hawk | Write Feedback';
        $data['content_title'] = 'Write Feedback';
        $data['content_subtitle'] = '';
        $data['content'] = 'feedback/view_feedback.php';
        $this->load->view('main/main.php', $data);
    }

    public function save_feedback(){
        $data['rate_us'] = $this->input->post('rate_us');

        if(!empty($this->input->post('comments'))){
        $data['comments'] = $this->input->post('comments');
        }
        
        if(!empty($this->input->post('suggestions'))){
        $data['suggestions'] = $this->input->post('suggestions');
        }

        $choic="";
        if(!empty($_POST['choices'])){
        foreach($_POST['choices'] as $selected){
        $choic=$choic.$selected.",";
        }
        $data['choices']=$choic;
        }
        
        $data['add_uid'] = $this->input->post('add_uid');
        $this->mdl_feedback->save($data);

        $mesofeb="<ol>";
        $choicee=explode(',',$choic);
        foreach($choicee as $newc){
            if(!empty($newc) && $newc!="Suggestions"){
                $mesofeb=$mesofeb."<li>".$newc."</li>";
            }
        }
        $mesofeb=$mesofeb."</ol>";
        if(!empty($this->input->post('comments'))){
            $mesocom=$this->input->post('comments');
        }else{
            $mesocom="None For Now";
        }

        if(!empty($this->input->post('suggestions'))){
            $mesosug=$this->input->post('suggestions');
        }else{
            $mesosug="None For Now";
        }


        $userdet=$this->mdl_feedback->get_user_by_id($this->input->post('add_uid'));
        $to = array("app@raindrops.co.ke");
        $subj = "Hawk - Feedback";
        $url = "http://40.68.162.157:9090/hawk/index.php";
        
        $message = '<div class="" style="margin-left:100px;width:500px; position:fixed; top:100px; left:30%;background:#f5f5f5;">
                        <div style="background:#101010;border-bottom:6px solid #18bc9c;padding:10px;text-align: center;">
                            <h1>Feedback Details</h1>
                        </div>
                        <div style="padding:20px;">
                            Dear Admin,<br><br>
                            A user of Hawk has sent the following feedback details.
                            <br>
                            <br>
                            <b>User Details</b><br>
                            Names : ' . $userdet['first_name'].' '.$userdet['last_name'] . '<br>
                            Account Name: ' . $userdet['account_name'] . ' <br>
                            <br>
                            <br>
                            <b><u>FeedBack Details</u></b><br><br>
                            <b><i>Rate Given :</i></b> ' . $this->input->post('rate_us') . '<br><br>
                            <b><u><i>Feedback:</i></u></b>' . $mesofeb . '
                            <b><i>Comments:</i></b>   ' . ucfirst($mesocom) . ' <br>
                            <b><i>Suggestions:</i></b>  ' . ucfirst($mesosug) . ' <br>
                            <br>
                            <br>
                            Verify carefully the Company information.
                            <br>
                            In case of any doubts, please feel free to contact HAWK Registrar.<br>
                            <a href="#">info@svs.com</a> on or <a href="#">+254 (0)729 220 777</a>
                            <br>                        
                        </div>
                    </div>';

        $rese=$this->emailsend->send_email_message ($to, $subj, $message);
    }

}
