<?php 
class alerts_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function alerts_model() 
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_group = "group_master";
		$this->icon_master = "icon_master";
    }
	function getAllData(){
		$user = $this->session->userdata('user_id');
		$cmd=uri_assoc('cmd');
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		//$device = $this->input->get('device');
		$device=trim($this->input->get('assets_id'),",");
		$type=$this->input->get('type');
		if($cmd=="export"){
			$sdate=uri_assoc("sdate");
			$edate=uri_assoc("edate");
			$device=uri_assoc("assets_id");
		}
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-d");
			$edate = date("Y-m-d");
		}
	
		$sdate = $sdate." 00:00:00";
		$edate = $edate." 23:59:59";
		
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:3; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'id'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';         
		$start = $limit*$page - $limit; 
		$start = ($start<0)?0:$start; 
	

		$where = "del_date is null"; 
		$where = ""; 

		if(!$sidx) 
			$sidx =1;
				
		// $SQL = "SELECT count(*) as total from alert_master where CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') between '$sdate' and '$edate' and user_id=".$this->session->userdata('user_id');
		if($this->session->userdata('usertype_id') != 1){
			$SQL = "SELECT count(*) as total from alert_master where add_date between '$sdate' and '$edate' and user_id=".$this->session->userdata('user_id');
		}else{
			$SQL = "SELECT count(*) as total from alert_master where add_date between '$sdate' and '$edate'";
		}
		if($device){	//search by device
				$SQL .= " AND find_in_set(assets_id,'$device')";		
			}else{
				if($this->session->userdata('usertype_id') != 1){
					$SQL .= " AND assets_id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";		
				} else {
					$SQL .= " AND assets_id in ($device)";		
				}
			}
		
		
		
		
		
		if($type){
			$SQL .= " AND alert_header like '%$type%'";
		}
		if($where != "")   
			$SQL .= " AND $where";
		$this->output->set_output($SQL);
		
		
		$result = $this->db->query($SQL);
		$data_arr=$result->result_array();
		
		$count = $data_arr[0]['total'];
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);    
		} else {
			$total_pages = 0;
		}
		
		if ($page > $total_pages) 
			$page=$total_pages;
		
//		$SQL = "SELECT id, alert_header, alert_msg, alert_link, alert_type, user_id, assets_id, CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, CONVERT_TZ(del_date,'+00:00','".$this->session->userdata('timezone')."') as del_date from alert_master where CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') between '$sdate' and '$edate' and user_id=".$this->session->userdata('user_id');
		if($this->session->userdata('usertype_id') != 1){
			$SQL = "SELECT id, alert_header, alert_msg, alert_link, alert_type, user_id, assets_id, add_date, del_date from alert_master where add_date between '$sdate' and '$edate' and user_id=".$this->session->userdata('user_id');
		}else{
			$SQL = "SELECT id, alert_header, alert_msg, alert_link, alert_type, user_id, assets_id, add_date, del_date from alert_master where add_date between '$sdate' and '$edate'";
		}
		if($device){	//search by device
				$SQL .= " AND find_in_set(assets_id,'$device')";		
			}else{
				if($this->session->userdata('usertype_id') != 1){
					$SQL .= " AND assets_id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";		
				} else {
					$SQL .= " AND assets_id in ($device)";		
				}
			}
		if($where != "")      
			$SQL .= " AND $where";
		if($type){
			$SQL .= " AND alert_header like '%$type%'";
		}
		if($cmd=="export") 
		{
			$result = $this->db->query($SQL);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=alerts". date("s").".xls"); 
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
			$fitr.="<th>Alert Header</th>";
			$fitr.="<th>Message</th>";
			$fitr.="<th>Alert Link</th>";
			$fitr.="<th>Alert Type</th>";
			//$fitr.="<th>user_id</th>";
			$fitr.="<th>Date</th>";
			$fitr .="</tr>"; 
			foreach($result->result_array() as $data)  
				{
					$last_login_time = $data['last_login_time'];
					$last_logout_time = $data['last_logout_time'];
					
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data['alert_header']."</td>"; 
					$EXCEL.="<td>".$data['alert_msg']."</td>"; 
					$EXCEL.="<td>".$data['alert_link']."</td>";
					$EXCEL.="<td>".$data['alert_type']."</td>";
					//$EXCEL.="<td>".$data['user_id']."</td>";
					$EXCEL.="<td>".$data['add_date']."</td>";
					$EXCEL .="</tr>";
				}
			if($this->session->userdata('id')==1)
				$count=3;
			else
				$count=2;

			echo "<table border='1'>";
			echo "<tr><th colspan='4'> Alerts</th></tr>";
			echo $fitr;
			echo $EXCEL;
			echo "</table>";
			die();
		}
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		$query = $this->db->query($SQL);
		$data = array();
		$data['result'] = $query->result();
		$data['sql'] = $SQL;
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