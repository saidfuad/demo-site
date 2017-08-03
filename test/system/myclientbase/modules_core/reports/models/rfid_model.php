<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Eye View Design CMS module Ajax Model
 *
 * PHP version 5
 *
 * @category  CodeIgniter
 * @package   EVD CMS
 * @author    Frederico Carvalho
 * @copyright 2008 Mentes 100Limites
 * @version   0.1
*/

class Rfid_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function rfid_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_rfid = "tbl_rfid";
		$this->table_name = "rfid_log";
		$this->tbl_assets = "assests_master";
    }
	
	function getAllData($cmd){
		
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		//$device = $this->input->get('device');
		$device=trim($this->input->get('device'),",");
		
		if($sdate != "" && $edate != ""){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-d");
			$edate = date("Y-m-d");
		}
		$sdate = $sdate." 00:00:00";
		$edate = $edate." 23:59:59";
		
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:10; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'id'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';
		
		
		$where = ""; 
		$searchField = isset($_GET['searchField']) ? $_GET['searchField'] : false;
		$searchOper = isset($_GET['searchOper']) ? $_GET['searchOper']: false;
		$searchString = isset($_GET['searchString']) ? $_GET['searchString'] : false;
		if($searchField=="tm.date_time")
			{
				$searchString=date("Y-m-d",strtotime($searchString));
			//	echo $searchString;
				//exit(0);
			}
			
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
			/*echo $searchField;
			exit(0);*/
				$where = "$searchField $ops '$searchString' "; 
			

		}
		$user = $this->session->userdata('user_id');
		
		
		$SQL = "SELECT count(rf.id) as total FROM ".$this->table_name." as rf LEFT JOIN ".$this->tbl_assets." am on am.id = rf.assets_id_boarding WHERE am.id IN (SELECT id FROM assests_master WHERE find_in_set(id, (SELECT assets_ids FROM user_assets_map WHERE user_id = $user)))";
		
		$SQL .= " AND CONVERT_TZ(rf.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";

		if($device != "")
		{
			$SQL .=" AND find_in_set(rf.assets_id_boarding,'$device')";
		}else{
			return;
			die();
		}
		
		$result = $this->db->query($SQL);
		$data_arr=$result->result_array();
		
		$count = $data_arr[0]['total'];
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);
			$start = ($limit*$page) - $limit; 
		} else {
			$total_pages = 0;
			$start = 0;
		}

		if ($page > $total_pages) 
			$page = $total_pages;

				
		$SQL = "SELECT rf.id, tr.person, CONVERT_TZ(rf.boarding_time,'+00:00','".$this->session->userdata('timezone')."') as b_time, rf.b_address, am.assets_name as device, am1.assets_name as device1, CONVERT_TZ(rf.leaving_time ,'+00:00','".$this->session->userdata('timezone')."') as l_time, rf.l_address FROM ".$this->table_name." as rf LEFT JOIN ".$this->tbl_assets." am ON am.id = rf.assets_id_boarding LEFT JOIN ".$this->tbl_assets." am1 ON am1.id = rf.assets_id_leaving LEFT JOIN ".$this->table_rfid." tr ON tr.id = rf.rfid_id WHERE am.id IN (SELECT id FROM assests_master WHERE find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))"; 
		
		$SQL .= " AND CONVERT_TZ(rf.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";

		if($device != "")
		{
			$SQL .=" AND find_in_set(rf.assets_id_boarding,'$device')";
		}else{
			return;
			die();
		}
		$export_sql="";
		$export_sql=$SQL;
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		
		$query = $this->db->query($SQL);
		
		if($cmd=="export") 
		{
			$result = $this->db->query($export_sql);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=RFID_Report". date("s").".xls"); 
			$EXCEL = "";
			$fitr="";
			
			//session date & time format
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			$fitr .="<tr>"; 
			$fitr.="<th>Person</th>";
			$fitr.="<th>Boarding Assets</th>";
			$fitr.="<th>Boarding Time</th>";
			$fitr.="<th>Address</th>";
			$fitr.="<th>Leaving Assets</th>";
			$fitr.="<th>Leaving Time</th>";
			$fitr.="<th>Address</th>";
			$fitr .="</tr>"; 
			 
			foreach($result->result_array() as $data)
				{
					$b_time = $data['b_time'];
					$l_time = $data['l_time'];
					
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data['person']."</td>"; 
					$EXCEL.="<td>".$data['device']."</td>"; 
					$EXCEL.="<td>&nbsp;".date("$date_format $time_format", strtotime($b_time))."</td>"; 
					$EXCEL.="<td>".$data['b_address']."</td>";
					$EXCEL.="<td>".$data['device1']."</td>"; 
					$EXCEL.="<td>&nbsp;".date("$date_format $time_format", strtotime($l_time))."</td>"; 
					$EXCEL.="<td>".$data['l_address']."</td>";
					$EXCEL .="</tr>";
					$device_name = $data['device'];
				}
			if($this->session->userdata('id')==1)
				$count=3;
			else
				$count=2;
			
			echo "<table border='1'>";
			echo "<tr><th colspan='7'>RFID Log</th></tr>";
			echo "<tr><th colspan='1'>Start Date</th><th colspan='1'>End Date</th><th colspan='5'>Assets Name</th></tr>";
			echo "<tr><th colspan='1'>&nbsp;".date("$date_format $time_format", strtotime($sdate))."</th><th colspan='1'>&nbsp;".date("$date_format $time_format", strtotime($edate))."</th><th colspan='5'></th></tr>";
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
		$data['sql'] = $SQL;
		return $data;
	}
}
?>