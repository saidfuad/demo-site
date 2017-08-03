<?php 
class Payment_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Payment_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_payment = "tbl_payment_master";
    }
	function getAllData(){
		
		$sdate = $this->input->get('commercial_sdate');
		$edate = $this->input->get('commercial_edate');
		
		$usr = $this->input->get('usr');
		
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-d",strtotime("-30 days"));
			$edate = date("Y-m-d");
		}
		
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:3; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'user_id'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';         
		$start = $limit*$page - $limit; 
		$start = ($start<0)?0:$start; 

		$where = ""; 
		$searchField = isset($_GET['searchField']) ? $_GET['searchField'] : false;
		$searchOper = isset($_GET['searchOper']) ? $_GET['searchOper']: false;
		$searchString = isset($_GET['searchString']) ? $_GET['searchString'] : false;

		if (isset($_GET['_search']) && $_GET['_search'] == 'true') {
			$ops = array(
			'eq'=>'=', 
			'ne'=>'<>',
			'lt'=>'<', 
			'le'=>'<=',
			'gt'=>'>', 
			'ge'=>'>=',
			'bw'=>'LIKE',
			'bn'=>'NOT LIKE',
			'in'=>'LIKE', 
			'ni'=>'NOT LIKE', 
			'ew'=>'LIKE', 
			'en'=>'NOT LIKE', 
			'cn'=>'LIKE', 
			'nc'=>'NOT LIKE' 
			);
			foreach ($ops as $key=>$value){
				if ($searchOper==$key) {
					$ops = $value;
				}
			}
			if($searchOper == 'eq' ) $searchString = $searchString;
			if($searchOper == 'bw' || $searchOper == 'bn') $searchString .= '%';
			if($searchOper == 'ew' || $searchOper == 'en' ) $searchString = '%'.$searchString;
			if($searchOper == 'cn' || $searchOper == 'nc' || $searchOper == 'in' || $searchOper == 'ni') $searchString = '%'.$searchString.'%';

			$where = "$searchField $ops '$searchString' "; 

		}

		if(!$sidx) 
			$sidx =1;
			
		
		$SQL = "SELECT um.first_name, um.last_name, pm.* FROM tbl_payment_master pm left join tbl_users um on um.user_id = pm.user_id WHERE pm.status=1 and pm.del_date is null and date(CONVERT_TZ(pm.add_date,'+00:00','".$this->session->userdata('timezone')."')) BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		if($this->session->userdata('usertype_id') != 1){
			$SQL .= " AND pm.user_id = ".$this->session->userdata('user_id');
		}
		if($usr != ""){
			$SQL .= " AND pm.user_id = ".$usr;
		}
		if($where != "")
			$SQL .= " AND $where";
		$res = $this->db->query($SQL);
		$result = $res->result();
		$net_total = 0;
		foreach($result as $row) {
			$net_total += $row->amount;
		}
		$count = $res->num_rows();
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);    
		} else {
			$total_pages = 0;
		}

		if ($page > $total_pages) 
			$page=$total_pages;
			
		
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		$query = $this->db->query($SQL);
		
		$data = array();
		$data['result'] = $query->result();
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;
		$data['net_total'] = "<font color='red'>".$net_total."</font>";
		return $data;
	}
	
	public function getPerDayAmount($usr)
	{
		$SQL = "SELECT pm.charges_per_day FROM mst_user_profile pm left join tbl_users um on um.profile_id = pm.id where um.user_id = '$usr'";
		$query = $this->db->query($SQL);
		$row = $query->row();
		if(count($row))
			return $row->charges_per_day;
		else
			return 0;
	}
	public function getExpiryDate($usr)
	{
		$SQL = "SELECT to_date FROM tbl_users where user_id = '$usr'";
		$query = $this->db->query($SQL);
		$row = $query->row();
		if(count($row))
			return $row->to_date;
		else
			return '';
	}
	public function updateExpiryDate($usr, $days)
	{
		$upd = $this->db->query("UPDATE tbl_users SET `to_date`= if(to_date < CURDATE(),  DATE_ADD(CURDATE(), INTERVAL $days), DATE_ADD(to_date, INTERVAL $days)) WHERE user_id = '$usr'");
		return TRUE;
	}	
	public function updatesmsBalance($usr, $amount)
	{
		$upd = $this->db->query("UPDATE tbl_users SET `sms_balance`= sms_balance + '".$amount."' WHERE user_id = '$usr'");
		return TRUE;
	}
	public function updateOutstanding($usr, $amount)
	{
		$upd = $this->db->query("UPDATE tbl_users SET `outstanding_amount`= outstanding_amount + '".$amount."' WHERE user_id = '$usr'");
		return TRUE;
	}		
	public function delete_payment()
	{
		$id = uri_assoc('id');
		$dt=gmdate("Y-m-d H:i:s");

		$delete_payment = $this->db->query("delete from tbl_payment_master WHERE id = ".$id);
		return TRUE;
	}	
	public function validate() {
		
		$this->form_validation->set_rules('payment_name', 'payment Name');
				
		return parent::validate();

	}
	
	function save($db_array, $id=NULL, $set_flashdata = TRUE) {

		$success = TRUE;
		$this->db->query("tbl_payment_master", $db_array);

		return $success;

	}
	
	public function prepare_users() 
	{		
		$user = $this->session->userdata('user_id');
		$this->db->select('*');
		$this->db->where('admin_id', $user);
		$this->db->where('status',1);
		$this->db->where('del_date',null);
		$this->order_by = 'user_id';
		$query = $this->db->get('tbl_users');
		return $query->result();
	}
	public function getPaymentRecord($id) 
	{		
		$SQL = "SELECT * FROM tbl_payment_master where id = '$id'";
		$query = $this->db->query($SQL);
		return $query->row();
	}	
}