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

class Distancereport_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function distancereport_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "tbl_track";
    }
	
	public function get_distancereport($cmd) 
	{
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		$device = $this->input->get('device');
		if($device == "")
			$device = -1;
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-d");
			$edate = date("Y-m-d");
		}
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:10; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'id'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';
	//	$cmd=uri_assoc('cmd');

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
			
		/*$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		$device = $this->input->get('device');
		*/
		$SQL = "SELECT dm.id,CONCAT(am.assets_name,'(',dm.assets_id,')') as assets_id,dm.add_date,dm.distance from distance_master dm left join assests_master am on am.device_id=dm.assets_id WHERE date(dm.add_date) BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		if($device)	//search by device
			$SQL .= " AND dm.assets_id = $device";
		if($where != "")
			$SQL .= " AND $where";
		
		$result = $this->db->query($SQL);
		$count = $result->num_rows();
		
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);
			$start = ($limit*$page) - $limit;  
		} else {
			$total_pages = 0;
			$start = 0;
		}

		if ($page > $total_pages) 
			$page = $total_pages;
		
		$SQL = "SELECT dm.id,CONCAT(am.assets_name,'(',dm.assets_id,')') as assets_id,dm.add_date,dm.distance from distance_master dm left join assests_master am on am.device_id=dm.assets_id WHERE date(dm.add_date) BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		if($device)	//search by device
			$SQL .= " AND dm.assets_id = $device";
		if($where != "")   
			$SQL .= " AND $where";
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		//die($SQL);
		$query = $this->db->query($SQL);
		 
		if($cmd=="export")   
		{
			
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=distance". date("s").".xls"); 
			$EXCEL = ""; 
			$fitr="";
			
			//session date & time format 
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			$fitr .="<tr>"; 
			$fitr.="<th>Datetime</th>";
			$fitr.="<th>Distance(KM)</th>";
			$fitr.="<th>Device</th>";
			$fitr .="</tr>"; 
			//var_dump($result);
			foreach($result->result_array() as $data)  
				{
					$add_date = $data['add_date'];
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".date("$date_format $time_format", strtotime($add_date))."</td>"; 
					$EXCEL.="<td>".$data['distance']."</td>";
					$EXCEL.="<td>".$data['assets_id']."</td>"; 
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
			echo "<tr><th colspan='3'> Distance</th></tr>";
			echo $fitr;
			echo $EXCEL;
			echo "</table>";
			die(); 
		}
		
		$data = array();
		$data['result'] = $query->result();
		//$data['count_pay'] = $query1->result();
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;
		return $data; 
		
		
		/*
		//Select table name
		
		$sdate = $this->input->post('sdate');
		$edate = $this->input->post('edate');
		$device = $this->input->post('device');
		
		if($device == "")
			$device = -1;	
		
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-d");
			$edate = date("Y-m-d");
		}
		
		$this->db->select('id,add_date,phone_imei,device_id,lati,longi,speed')->from($this->table_name);
		$this->db->where("date(add_date) BETWEEN '" . $sdate . "' AND '" . $edate . "'");
		if($device){	//search by device
			$this->db->where('device_id',$device);
		}
		$this->CI->flexigrid->build_query(FALSE);
		
		//Get contents
		$return['records'] = $this->db->get();
		//echo $this->db->last_query();
		//exit;
		
		//Build count query
		$this->db->select('count(id) as record_count')->from($this->table_name);
		$this->db->where("date(add_date) BETWEEN '" . $sdate . "' AND '" . $edate . "'");
		if($device){	//search by device
			$this->db->where('device_id',$device);
		}
		$this->CI->flexigrid->build_query(FALSE);
		$record_count = $this->db->get();
		$row = $record_count->row();
		
		//Get Record Count
		$return['record_count'] = $row->record_count;
		//Return all
		return $return;*/
	}
	//this function for data display in grid
	public function get_distancegraph() 
	{
		//Select table name
		
		$sdate = $this->input->post('sdate');
		$edate = $this->input->post('edate');
		$device = $this->input->post('device');
		
		$this->db->select('id,add_date,phone_imei,device_id,lati,longi,speed');
		
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-d");
			$edate = date("Y-m-d");
		}
		$this->db->where("date(add_date) BETWEEN '" . $sdate . "' AND '" . $edate . "'");
			
		if($device == ''){	//search by device
			$device = '0';
			
		}
		$this->db->where('device_id',$device);
		$this->db->from($this->table_name)->orderBy('add_date');
		//$this->CI->flexigrid->build_query();
		
		//Get contents
		$return['records'] = $this->db->get();
		
		//$this->CI->flexigrid->build_query(FALSE);
		
		//Return all
		return $return;
	}
		
}
?>