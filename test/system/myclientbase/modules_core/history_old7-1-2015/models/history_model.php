<?php 
class History_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function History_model() 
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_group = "group_master";
		$this->icon_master = "icon_master";
    }
	function get_map_data(){
		//die(uri_assoc('id'));
		if($_REQUEST['cmd']=='history')
		{
			$id = $this->input->get('id');
			$query = $this->db->query("select id, ip_address, country_name, state_name, city_name, os_name, device, latitude, longitude, CONVERT_TZ(last_login_time,'+00:00','".$this->session->userdata('timezone')."') as last_login_time, CONVERT_TZ(last_logout_time,'+00:00','".$this->session->userdata('timezone')."') as last_logout_time, duration_of_stay, user_id, add_date, add_uid, del_date, del_uid, status, comments from sys_information where id = ".$id);
			return $query->result();	
		 
		} 
		
	}
	function getAllData(){
		$sdate = $this->input->get('sdate_history');
		$edate = $this->input->get('edate_history');
		
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-1");
			$edate = date("Y-m-d");
		}
		$sdate = $sdate." 00:00:00";
		$edate = $edate." 23:59:59";
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:3; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'user_id'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';         
		$start = $limit*$page - $limit; 
		$start = ($start<0)?0:$start; 
		$cmd=uri_assoc('cmd');

		$where = ""; 

		if(!$sidx) 
			$sidx =1;
		
		$SQL = "SELECT * FROM sys_information WHERE CONVERT_TZ(last_login_time,'+00:00','".$this->session->userdata('timezone')."') between '$sdate' and '$edate' and user_id = ".$this->session->userdata('user_id');
	
		if($where != "")   
			$SQL .= " AND $where";
		$result = $this->db->query($SQL);
		$count = $result->num_rows();
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);    
		} else {
			$total_pages = 0;
		}

		if ($page > $total_pages) 
			$page=$total_pages;
		

		$SQL = "SELECT id, ip_address, country_name, state_name, city_name, os_name, device, latitude, longitude, CONVERT_TZ(last_login_time,'+00:00','".$this->session->userdata('timezone')."') as last_login_time, CONVERT_TZ(last_logout_time,'+00:00','".$this->session->userdata('timezone')."') as last_logout_time, duration_of_stay, user_id, CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, add_uid, del_date, del_uid, status, comments FROM sys_information WHERE CONVERT_TZ(last_login_time,'+00:00','".$this->session->userdata('timezone')."') between '$sdate' and '$edate' and user_id = ".$this->session->userdata('user_id');
		if($where != "")      
			$SQL .= " AND $where";
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		$query = $this->db->query($SQL);
		
		if($cmd=="export") 
		{
		
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=history". date("s").".xls"); 
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
			$fitr.="<th>Ip Address</th>";
			$fitr.="<th>Country Name</th>";
			$fitr.="<th>State Name</th>";
			$fitr.="<th>City Name</th>";
			$fitr.="<th>Os Name</th>";
			$fitr.="<th>Device</th>";
			$fitr.="<th>Last Login Time</th>";
			$fitr.="<th>Last Logout Time</th>";
			$fitr.="<th>Duration Of Stay</th>";
			$fitr .="</tr>"; 
			foreach($result->result_array() as $data)  
				{
					$last_login_time = $data['last_login_time'];
					$last_logout_time = $data['last_logout_time'];
					
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data['ip_address']."</td>"; 
					$EXCEL.="<td>".$data['country_name']."</td>";
					$EXCEL.="<td>".$data['state_name']."</td>";
					$EXCEL.="<td>".$data['city_name']."</td>";
					$EXCEL.="<td>".$data['os_name']."</td>";          
					$EXCEL.="<td>".$data['device']."</td>"; 
					//$EXCEL.="<td>".$data['last_login_time']."</td>";
					//$EXCEL.="<td>".$data['last_logout_time']."</td>"; 
					$EXCEL.="<td>".date("$date_format $time_format", strtotime($last_login_time))."</td>"; 
					$EXCEL.="<td>".date("$date_format $time_format", strtotime($last_logout_time))."</td>"; 
					$EXCEL.="<td>".$data['duration_of_stay']."</td>";
					$EXCEL .="</tr>";
				}
			if($this->session->userdata('id')==1)
				$count=3;
			else
				$count=2;

			echo "<table border='1'>";
			echo "<tr><th colspan='4'> history</th></tr>";
			echo $fitr;
			echo $EXCEL;
			echo "</table>";
			die();
		}
		
		$data = array();
		$data['result'] = $query->result();
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