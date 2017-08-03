<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class In_landmark_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function in_landmark_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "tbl_track";
    }
	
	public function get_data($cmd) 
	{
		$user = $this->session->userdata('user_id');
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		$landmark = $this->input->get('landmark');
		if($landmark == ''){
			return;
			die();
		}		
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d H:i:s", strtotime($sdate));
			$edate = date("Y-m-d H:i:s", strtotime($edate));
		}else{
			$sdate = date("Y-m-d H:i:s");
			$edate = date("Y-m-d H:i:s");
		}
		
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:10; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'tm.id'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';
		

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
			$sidx = 1;
		
		$SQL = "SELECT count( DISTINCT lm.assets_id ) AS total FROM ".$this->table_name." lm left join assests_master am on am.id = lm.assets_id WHERE 
			CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '".$edate."' and lm.current_landmark_id = '$landmark' group by lm.assets_id";
		
		if($where != "")
			$SQL .= " AND $where";
		
		$result = $this->db->query($SQL);
		$data_arr=$result->result_array();
		
		$count = $data_arr['total'];
	
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);
			$start = ($limit*$page) - $limit;  
		} else {
			$total_pages = 0;
			$start = 0;
		}

		if ($page > $total_pages) 
			$page = $total_pages;

		$SQL = "SELECT distinct lm.assets_id as assets_id, am.assets_name FROM ".$this->table_name." lm left join assests_master am on am.id = lm.assets_id WHERE 
			CONVERT_TZ(lm.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '".$edate."' and lm.current_landmark_id = '$landmark'";
		
		
		if($where != "")
			$SQL .= " AND $where";
		$export_sql="";
		$export_sql=$SQL;
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		
		$query = $this->db->query($SQL);
		
		
		if($cmd=="export") 
		{
			$result = $this->db->query($export_sql);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=landmark_in". date("s").".xls"); 
			$EXCEL = "";
			$fitr="";
			
			//session date & time format
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			$date="";
			//$device="";
			$fitr .="<tr>";
			$fitr.="<th>Assets Name</th>";	
			$fitr .="</tr>";
			
			foreach($result->result_array() as $data)
				{
					$EXCEL.="<td>".$data['assets_name']."(".$data['device_id'].")</td>"; 
				}
				$sdt = date("$date_format $time_format", strtotime($sdate));
				$edt = date("$date_format $time_format", strtotime($edate));
			if($this->session->userdata('id')==1)
				$count=3; 
			else
				$count=2; 
			
			if($device == '')
				$device = "ALL";
			else
				$device = $device_name;
				
 			echo "<table border='1'>";
			echo "<tr><th colspan='5'>Stop Report</th></tr>";
			echo "<tr><th colspan='2'>Start Date</th><th colspan='2'>Stop Date</th><th>Assets Name</th></tr>";
			echo "<tr><th colspan='2'>&nbsp;$sdt</th><th colspan='2'>&nbsp;$edt</th><th>&nbsp;</th></tr>";
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
	public function get_landmark(){
		$user = $this->session->userdata('user_id');
		$SQL = "select id, name from landmark where del_date is null and status = 1 and add_uid = $user";		
		$query = $this->db->query($SQL);
		return $query->result();
	}
}
?>