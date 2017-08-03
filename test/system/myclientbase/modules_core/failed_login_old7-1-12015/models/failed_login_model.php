<?php 
class Failed_login_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Failed_login_model() 
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
    }
	function getAllData(){
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-01");
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
			
		  
		/*$SQL = "SELECT * FROM failed_login WHERE user_id = ".$this->session->userdata('user_id');
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
			$page=$total_pages;*/
		
		$SQL = "SELECT id, ip_address, country_name, state_name, city_name, os_name, device, latitude, longitude, user_id, CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, add_uid, del_date, del_uid, status, comments FROM failed_login WHERE CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') between '$sdate' and '$edate' and  user_id = ".$this->session->userdata('user_id');
		
	//	die($SQL);
		if($where != "")   
			$SQL .= " AND $where";
		if($cmd!="export") 
		{
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		}
		$query = $this->db->query($SQL);
		$count = $query->num_rows;
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);    
		} else {
			$total_pages = 0;
		}

		if ($page > $total_pages) 
			$page=$total_pages;
		if($cmd=="export") 
		{
		
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=history". date("dmYhis").".xls"); 
			$EXCEL = "";
			$fitr="";
			
			//session date & time format
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			$fitr .="<tr>"; 
			$fitr.="<th>Ip Address</th>";
			$fitr.="<th>Date & Time</th>";
			$fitr.="<th>Country Name</th>";
			$fitr.="<th>State Name</th>";
			$fitr.="<th>City Name</th>";
			$fitr.="<th>Os Name</th>";
			$fitr.="<th>Device</th>";
			$fitr .="</tr>"; 
			foreach($query->result() as $data)     
				{
					$add_date = $data->add_date; 
					
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data->ip_address."</td>"; 
					//$EXCEL.="<td>".date('d.m.Y h:i a',strtotime($data->add_date))."</td>";
					$EXCEL.="<td>".date("$date_format $time_format", strtotime($add_date))."</td>"; 
					$EXCEL.="<td>".$data->country_name."</td>";
					$EXCEL.="<td>".$data->state_name."</td>";
					$EXCEL.="<td>".$data->city_name."</td>";
					$EXCEL.="<td>".$data->os_name."</td>";          
					$EXCEL.="<td>".$data->device."</td>"; 
					$EXCEL .="</tr>";
				}
			 
			echo "<table border='1'>";
			echo "<tr><th colspan='7'> history</th></tr>";
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
