<?php 
class ignition_motion_report_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function ignition_motion_report_model() 
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library("session");
    }
	function getAllData(){
		$session_data = $this->session->all_userdata();
		$user = $this->session->userdata('user_id');
		
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		$device=trim($this->input->get('device'),",");
		
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-d");
			$edate = date("Y-m-d");
		}
		$sdate = $sdate." 00:00:00";
		$edate = $edate." 23:59:59";
		$whereSearch = "";
		
		//$whereSearch .=" AND CONVERT_TZ(lg.date_time,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'"; 
		$whereSearch .=" AND im.re_date BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		if(!empty($device) || $device != "")
		{
			$whereSearch .=" AND find_in_set(im.device_id,'$device')";
		}else{
			return;
			die();
		}
		
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:3; 
		$sidx = isset($_GET["sidx"])?$_GET["sidx"]:"id"; 
		$sord = isset($_GET["sord"])?$_GET["sord"]:"";         
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
			
		$SQL = "SELECT im.id, im.device_id, im.re_date, im.motion_hour, im.ignition_hour, concat(am.assets_name,'(',am.device_id,')') as device_name FROM ignition_motion_report im left join assests_master am on am.id=im.device_id where 1 ";
		if($whereSearch != "")
		{
			$SQL .= " $whereSearch ";
		}
		if($where != "")
			$SQL .= " AND $where";
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
			
		$SQL = "SELECT im.id, im.device_id, im.re_date, im.motion_hour, im.ignition_hour, concat(am.assets_name,'(',am.device_id,')') as device_name FROM ignition_motion_report im left join assests_master am on am.id=im.device_id where 1";
			
		if($whereSearch != "")
		{
			$SQL .= " $whereSearch ";
		}
		
		if($where != "")
			$SQL .= " AND $where";
		
		$SQL .= " ORDER BY $sidx $sord";

		$export_sql=$SQL;
		
		$SQL .= " LIMIT $start, $limit";
		
		if($cmd =="excel"){
			$result = $this->db->query($export_sql);			
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=operating_hours.xls"); 			
			$EXCEL = "";
			$fitr="";
			$fitr .="<tr>"; 
			$fitr.="<th>Assets Name(Device)</th>";
			$fitr.="<th>Date</th>";
			$fitr.="<th>Motion Hour</th>";
			$fitr.="<th>Ignition Hour</th>";
			$fitr .="</tr>";
			foreach($result->result_array() as $data){
					$re_date = $data['re_date'];
					//$num = number_format($data["device_id"],0, "," , " ");
					
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data["device_name"]."</td>";
					$EXCEL.="<td> &nbsp;".date("$date_format",strtotime($re_date))."</td>";
					$EXCEL.="<td>".gmdate("H:i:s", $data["motion_hour"])."</td>";
					$EXCEL.="<td>".gmdate("H:i:s", $data["ignition_hour"])."</td>";
					$EXCEL .="</tr>";
			}
			echo "<table border='1'>";
			echo "<tr><th colspan='4'> Operating Hours  on ".date("d.m.Y h:i:s A")."</th></tr>";
			echo $fitr;
			echo $EXCEL;
			echo "</table>";
			die();
		}
		if($cmd =="pdf" )
		{
			$data1 = $this->db->query($export_sql);
			return $data1;
		}
		$query = $this->db->query($SQL);
		$data = array();
		$data["result"] = $query->result();
		$data["page"] = $page;
		$data["total_pages"] = $total_pages;
		$data["count"] = $count;
		return $data; 
	}
	function save($db_array, $id=NULL, $set_flashdata = TRUE) {
		 $success = TRUE;
		 $this->db->insert("ignition_motion_report", $db_array);
		 return $success;
	}
	public function delete_ignition_motion_report(){
		$ids = $_POST["id"];
		$date=date("Y-m-d H:i:s");
		$delete_ignition_motion_report = $this->db->query("UPDATE `ignition_motion_report` SET status=0, del_uid=".$this->session->userdata("id").", del_date='".$date."' WHERE id in(".$ids.")");
		return TRUE;
	}
}
?>