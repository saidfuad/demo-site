<?php 
class Commercial_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Commercial_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_group = "group_master";
		$this->icon_master = "icon_master";
    }
	function getAllData(){
		$sdate = $this->input->get('commercial_sdate');
		$edate = $this->input->get('commercial_edate');
		
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-d",strtotime("-2 days"));
			$edate = date("Y-m-d");
		}
		$sdate = $sdate." 00:00:00";
		$edate = $edate." 23:59:59";
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:3; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'smslog.user_id'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';         
		$start = $limit*$page - $limit; 
		$start = ($start<0)?0:$start; 
		$cmd=uri_assoc('cmd');

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
		  
		$SQL = "SELECT count(*) as total FROM smslog, tbl_users WHERE find_in_set(smslog.mobile, tbl_users.mobile_number) and (smslog.user_id = ".$this->session->userdata('user_id')." AND CONVERT_TZ(smslog.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "')";
		if($where != "")
			$SQL .= " AND $where";
		
		$result = $this->db->query($SQL);
		$data_arr=$result->result_array();
		
		$count = $data_arr[0]['total']; 

		//$count = $result->num_rows();
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);    
		} else {
			$total_pages = 0;
		}

		if ($page > $total_pages) 
			$page=$total_pages;
		
		$SQL1 = "SELECT data_value FROM tbl_settings WHERE data_key = 'sms_price'";
		$query1 = $this->db->query($SQL1);
		$row = $query1->row();
		$sms_price =$row->data_value;
		if($sms_price == ""){
			$sms_price = 0;
		}
		
		$SQL1 = "SELECT (COUNT(smslog.payment_status) * $sms_price) as count_pay FROM smslog WHERE (smslog.user_id = ".$this->session->userdata('user_id')." AND CONVERT_TZ(smslog.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "') AND smslog.payment_status = 0";
		$query1 = $this->db->query($SQL1);
		$i=0;
		$row = $query1->result();
		$count_pay =$row[0]->count_pay;
		
		$SQL = "SELECT smslog.mobile, tbl_users.first_name, tbl_users.last_name, smslog.sms_text, CONVERT_TZ(smslog.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, smslog.payment_status FROM smslog, tbl_users WHERE find_in_set(smslog.mobile, tbl_users.mobile_number) and (smslog.user_id = ".$this->session->userdata('user_id')." AND CONVERT_TZ(smslog.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "')";
	
		if($where != "")
			$SQL .= " AND $where";
		$excel_query = '';
		$excel_query = $SQL;
		
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		
		$query = $this->db->query($SQL); 
		
		if($cmd=="export")
		{
			$result = $this->db->query($excel_query);
			
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=commercial". date("s").".xls"); 
			
			$EXCEL = "";
			$fitr="";
			
			//session date & time format
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			if($this->session->userdata('id')==1)
			{
				$fitr.="<th>Owner</th>";
			}
			$fitr .="<tr>"; 
			$fitr.="<th>Mobile No</th>";
			$fitr.="<th>SMS Text</th>";
			$fitr.="<th>Date-Time</th>";
			$fitr.="<th>Payment Status</th>";
			$fitr .="</tr>"; 
			foreach($result->result_array() as $data)
				{
					$add_date = $data['add_date'];
					if($data['payment_status'] == 0)
						$data['payment_status'] = "Unpaid";
					else
						$data['payment_status'] = "Paid";
					$EXCEL .="<tr align='center'>";
					$data['mobile'] = $data['mobile']."(".$data['first_name']." ".$data['last_name'].")";
					$EXCEL.="<td>".$data['mobile']."</td>"; 
					$EXCEL.="<td>".$data['sms_text']."</td>";
					$EXCEL.="<td>&nbsp;".date("$date_format $time_format", strtotime($add_date))."</td>";
					$EXCEL.="<td>".$data['payment_status']."</td>";
					if($this->session->userdata('id')==1)
					{
						$EXCEL.="<td>".$data['Owner']."</td>";
					}
					$EXCEL .="</tr>";
				}
			if($this->session->userdata('id')==1)
				$count=3;
			else
				$count=2;

			echo "<table border='1'>";
			echo "<tr><th colspan='4'> Commercial</th></tr>";
			echo "<tr><th colspan='2'>Start Date</th><th colspan='2'>End Date</th></tr>";
			echo "<tr><th colspan='2'>&nbsp;".date("$date_format $time_format", strtotime($sdate))."</th><th colspan='2'>&nbsp;".date("$date_format $time_format", strtotime($edate))."</th></tr>";
			echo $fitr;
			echo $EXCEL;
			echo "<tr><th colspan=3> Total Unpaid Charges</th><th colspan=1>".$count_pay."</th></tr>";
			echo "</table>";
			die(); 
		}
		
		$data = array();
		$data['result'] = $query->result();
		//$data['count_pay'] = $query1->result();
		$data['count_pay'] = $count_pay;
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;
		return $data; 
		
	}
	
	public function validate() {
		$this->form_validation->set_rules('group_name', 'Group Name');
		return parent::validate();
	}
	function save($db_array, $id=NULL, $set_flashdata = TRUE) {
		$success = TRUE;
		$this->db->query("group_master", $db_array);
		return $success;
	}
}
?>