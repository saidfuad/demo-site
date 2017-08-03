<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Payment extends Admin_Controller {
	
	function __construct() {

		parent::__construct(TRUE);

		$this->load->model('payment_model','',TRUE);
		$this->load->model('form_model','',TRUE);
	}
	function index()
	{
		$rows = $this->payment_model->prepare_users();
		$UserOpt = '';
		if(count($rows)) {
			foreach ($rows as $row) {
				$UserOpt .= "<option value='".$row->user_id."'>".addslashes($row->first_name)." ".addslashes($row->last_name)."</option>";
			}
		}
		$data['users'] = $UserOpt;
		$this->load->view( 'payment', $data );
	}
	function loadData(){
		$data = $this->payment_model->getAllData(); 
		$responce->page = $data['page'];
		$responce->total = $data['total_pages'];
		$responce->records = $data['count'];
		$i=0;
		$page_total = 0;
		foreach($data['result'] as $row) {
			$row->user = $row->first_name." ".$row->last_name;
			$responce->rows[$i] = $row;
			$page_total += $row->amount;
			$i++;
		}
		$responce->userdata['user'] = "Net Total :";
		$responce->userdata['add_date'] = $data['net_total']; 
		$responce->userdata['payment_type'] = 'Page Total :'; 
		$responce->userdata['amount'] = $page_total; 
		$this->output->set_output(json_encode($responce));
	}
	function deleteData(){
		
		
		$row = $this->payment_model->getPaymentRecord(uri_assoc('id'));
		$last_amount = $row->amount;
		$payment_for = $row->payment_for;
		$user_id = $row->user_id;
		if($payment_for == 'Server charges'){
			$per_day = $this->payment_model->getPerDayAmount($user_id);
			$days_plus = "-".intval($last_amount / $per_day)." DAY";
			$this->payment_model->updateExpiryDate($user_id, $days_plus);
			$this->payment_model->updateOutstanding($user_id, -$last_amount);
		}else if($payment_for == 'Sms Balance'){
				$this->payment_model->updatesmsBalance($user_id, -$last_amount);
		}
		$this->output->set_output($this->payment_model->delete_payment());
	}

	function form() {

		if (!$this->form_model->validate()) {

			$this->load->helper('form');

			if (!$_POST AND uri_assoc('id')) {

				$this->form_model->prep_validation(uri_assoc('id'));

			}			
			$rows = $this->payment_model->prepare_users();
			$opt = '';
			$user_id = $this->form_model->user_id;
			foreach ($rows as $row) {
				$opt .= '<option value="'.$row->user_id.'"';
				if($row->user_id == $user_id)
					$opt .= ' selected="selected"';
				$opt .= '>'.$row->first_name.' '.$row->last_name.'</option>';
				
			}
			$this->form_model->users = $opt;
			
			$this->form_model->add_uid = $this->session->userdata('user_id');
			$this->load->view('form');

		}

		else {	
			$formdata = $this->form_model->db_array();
			$newData = array();
			foreach($formdata as $key=>$value){
				if(is_array($value)){
					if(count($value) > 0)
						$value = implode(",", $value);
					else
						$value = "";
				}
				$newData[$key] = $value;
			}
			if($newData['cheque_date'] == '')
				$newData['cheque_date'] = NULL;
			else
				$newData['cheque_date'] = date('y-m-d H:i:s', strtotime($newData['cheque_date']));
			$newData['add_uid'] = $this->session->userdata('user_id');
			$newData['add_date'] = gmdate("Y-m-d H:i:s");
			
			$per_day = $this->payment_model->getPerDayAmount($newData['user_id']);
			if(uri_assoc('id')){
				$row = $this->payment_model->getPaymentRecord(uri_assoc('id'));
				$last_amount = $row->amount;
				$payment_for = $row->payment_for;
				$user_id = $row->user_id;
				if($payment_for == 'Server charges'){
					$days_plus = "-".intval($last_amount / $per_day)." DAY";
					$this->payment_model->updateExpiryDate($user_id, $days_plus);
					$this->payment_model->updateOutstanding($user_id, -$last_amount);
				}else if($payment_for == 'Sms Balance'){
					$this->payment_model->updatesmsBalance($user_id, -$last_amount);
				}
				
			}
			
			if($newData['payment_for'] == 'Installation'){
				/*$timestamp = strtotime("+1 month");
				$expiry_date = date('Y-m-d H:i:s',$timestamp);
				$this->payment_model->updateExpiryDate($newData['user_id'], "1 MONTH");
				*/
			}else if($newData['payment_for'] == 'Server charges'){
				
				
				$days_plus = intval($newData['amount'] / $per_day)." DAY";
				$this->payment_model->updateExpiryDate($newData['user_id'], $days_plus);
				$this->payment_model->updateOutstanding($newData['user_id'], $newData['amount']);
				
			}else if($newData['payment_for'] == 'Sms Balance'){
				$this->payment_model->updatesmsBalance($newData['user_id'], $newData['amount']);
			}
			
			
			$this->form_model->save($newData, uri_assoc('id'));

		}

	}
	function export(){
		
		$this->load->plugin('to_excel'); 
		$this->form_model->export();
	}	
}