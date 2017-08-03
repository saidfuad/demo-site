<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounting extends Base_Controller {

	function __construct() {

        parent::__construct();

        $this->load->library('encrypt');

        $this->load->model('mdl_accounting');
        $this->load->model('vehicles/mdl_vehicles');
        $this->load->library('emailsend');
        $this->load->library('smssend');

    }

    public function index(){

        $data['expenses'] = $this->mdl_accounting->fetch_expenses($this->session->userdata('hawk_account_id'));

        $data['content_url'] = 'Accounting';
        $data['fa'] = 'fa fa-sitemap';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'Hawk | View Expenses';
        $data['content_title'] = 'View Expenses';
        $data['content_subtitle'] = '';
        $data['content'] = 'accounting/view_expenses.php';
        $this->load->view('main/main.php', $data);
    }

    public function add_expense() {

        $data ['accountid'] = $this->session->userdata('hawk_account_id');
        $data ['uid'] = $this->session->userdata('hawk_user_id');
        $data ['vehicles'] = $this->mdl_accounting->pickvehicles($this->session->userdata('hawk_account_id'));
        $data ['extype'] = $this->mdl_accounting->pickextype();
        $data['content_url'] = 'reminders/add_expense';
        $data['fa'] = 'fa fa-plus';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Add Expense Incurred';
        $data['content_title'] = 'Add Expense Incurred';
        $data['content_subtitle'] = '';
        $data['content'] = 'accounting/add_expense.php';
        $this->load->view('main/main.php', $data);
    }

    public function save_expense(){
        $data=$this->input->post();        

        return $this->mdl_accounting->save_expense($data);
    }

    public function update_expense(){
        $data=$this->input->post();

        return $this->mdl_accounting->update_expense($data);
    }

    public function edit_expense($id) {

        $data ['accountid'] = $this->session->userdata('hawk_account_id');
        $data ['uid'] = $this->session->userdata('hawk_user_id');
        $data ['vehicles'] = $this->mdl_accounting->pickvehicles($this->session->userdata('hawk_account_id'));
        $data ['expense'] = $this->mdl_accounting->fetch_expense($id);
        $data ['extype'] = $this->mdl_accounting->pickextype();
        $data['content_url'] = 'accounting/edit_expense';
        $data['fa'] = 'fa fa-plus';
        $data['fa1'] = '';
        $data['fa2'] = '';
        $data['fa3'] = '';
        $data['fa4'] = '';
        $data['fa5'] = '';
        $data['title'] = 'HAWK | Edit Expense Details';
        $data['content_title'] = 'Edit Expense Details';
        $data['content_subtitle'] = '';
        $data['content'] = 'accounting/edit_expense.php';
        $this->load->view('main/main.php', $data);
    }
}
