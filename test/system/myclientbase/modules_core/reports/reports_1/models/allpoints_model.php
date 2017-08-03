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

class Allpoints_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function allpoints_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "tbl_track";
		$this->tbl_assets = "assests_master";
    }
	function getAllData($cmd){
		
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		$device = $this->input->get('device');
		if($device == "")
			$device = -1;
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d H:i:s", strtotime($sdate));
			$edate = date("Y-m-d H:i:s", strtotime($edate));
		}else{
			$sdate = date("Y-m-d H:i:s");
			$edate = date("Y-m-d H:i:s");
		}
	//	die($sdate);
	//	die($sdate."->".$edate);
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:10; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'id'; 
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
		
		//$SQL = "SELECT count(*) FROM ".$this->table_name." tm left join ".$this->tbl_assets." am on am.device_id = tm.device_id WHERE am.status=1 AND am.del_date is null AND date(tm.add_date) BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		$SQL = "SELECT count(*) as total FROM ".$this->table_name." tm WHERE CONVERT_TZ(tm.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		
		if($device)	//search by device
			$SQL .= " AND tm.assets_id = $device";
			
		if($where != "")
			$SQL .= " AND $where";
	
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
		$sqlAsset="SELECT assets_name from ".$this->tbl_assets." where id=$device Limit 1";
		$query1 = $this->db->query($sqlAsset);
		$query12=$query1->result_array();
		
		$assets_nm = $query12[0]['assets_name'];
		//die($assets_nm);
		$SQL = "SELECT tm.id, CONVERT_TZ(tm.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, '$assets_nm' as assets_name, tm.ignition, tm.device_id, tm.lati, tm.longi, tm.speed, tm.address FROM ".$this->table_name." tm WHERE CONVERT_TZ(tm.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		//$SQL = "SELECT tm.id, tm.add_date, '' as assets_name, tm.device_id, tm.lati, tm.longi, tm.speed, tm.address FROM ".$this->table_name." tm WHERE tm.add_date BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		if($device)	//search by devices
			$SQL .= " AND tm.assets_id = $device";
			
		if($where != "")
			$SQL .= " AND $where";
			
			
		//die($SQL);
		//$result = $this->db->query($SQL);
		$export_sql="";
		$export_sql=$SQL;
		if($cmd=="export") 
		{
			$result = $this->db->query($export_sql);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=allpoints". date("s").".xls"); 
			$EXCEL = "";
			$fitr="";
			
			//session date & time format
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			$fitr .="<tr>";
			$fitr.="<th>".$this->lang->line("Datetime")."</th>";
			$fitr.="<th>".$this->lang->line("Assets Name")."</th>";
			$fitr.="<th>".$this->lang->line("Address")."</th>";
			$fitr.="<th>".$this->lang->line("Speed")."</th>";
			$fitr .="</tr>";
			foreach($result->result_array() as $data)
				{
					$add_date = $data['add_date'];
					
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>&nbsp;".date("$date_format $time_format", strtotime($add_date))."</td>"; 
					$EXCEL.="<td>".$data['assets_name']."(".$data['device_id'].")</td>"; 
					$EXCEL.="<td>".$data['address']."</td>";
					$EXCEL.="<td>".$data['speed']."</td>";
					
					if($this->session->userdata('id')==1)
					{
						$EXCEL.="<td>".$data['Owner']."</td>";
					}
					$EXCEL .="</tr>";
					
					$device_name=$data['assets_name']." (".$data['device_id'].")";
				}
			if($this->session->userdata('id')==1)
				$count=3; 
			else
				$count=2; 
			
			if($device == '')
				$device = $this->lang->line("ALL");
			else
				$device = $device_name;
			
			echo "<table border='1'>";
			echo "<tr><th colspan='4'> ".$this->lang->line("All points")."</th></tr>";
			echo "<tr><th colspan='1'>".$this->lang->line("Start Date")."</th><th colspan='1'>".$this->lang->line("End Date")."</th><th colspan='2'>".$this->lang->line("Assets Name")."</th></tr>";
			echo "<tr><th colspan='1'>&nbsp;".date("$date_format $time_format", strtotime($sdate))."</th><th colspan='1'>&nbsp;".date("$date_format $time_format", strtotime($edate))."</th><th colspan='2'>".$device."</th></tr>";
			echo $fitr;
			echo $EXCEL;
			echo "</table>";
			die(); 
		}
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		
		$query = $this->db->query($SQL);
		
		$data = array();
		$data['result'] = $query->result();
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;
		return $data;
	}
	//this function for data display in grid
	public function get_allpoints() 
	{
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
		 $user_tz=$this->session->userdata('timezone');
		$this->db->select("tm.id, CONVERT_TZ(tm.add_date,'+00:00','".$this->session->userdata('timezone')."'), am.assets_name, tm.device_id, tm.lati, tm.longi, tm.speed, tm.address")->from($this->table_name. " tm");
		$this->db->join($this->tbl_assets. " am", "am.device_id = tm.device_id", 'LEFT');
		$this->db->where("CONVERT_TZ(tm.add_date,'+00:00','$user_tz') BETWEEN '" . $sdate . "' AND '" . $edate . "'");
		if($device){	//search by device
			$this->db->where('tm.device_id',$device);
		}
		$this->CI->flexigrid->build_query();
		
		//Get contents
		$return['records'] = $this->db->get();
		
		//Build count query
		
		$this->db->select('count(id) as record_count')->from($this->table_name);
		$this->db->where("CONVERT_TZ(tm.add_date,'+00:00','$user_tz') BETWEEN '" . $sdate . "' AND '" . $edate . "'");
		if($device){	//search by device
			$this->db->where('device_id',$device);
		}
		$this->CI->flexigrid->build_query(FALSE);
		$record_count = $this->db->get();
		$row = $record_count->row();
		
		//Get Record Count
		$return['record_count'] = $row->record_count;
		
		//Return all
		return $return;
		
	}
	
	//this function for data display on map
	public function get_all_locations() 
	{
		//Select table name
		$device = $this->input->post('device');
		$sdate = $this->input->post('start_date');
		$edate = $this->input->post('end_date');
		$user_tz=$this->session->userdata('timezone');
		$qry_rs="SELECT id, lati, longi, phone_imei, CONVERT_TZ(add_date,'+00:00','$user_tz') as add_date, speed, device_id, dt, ignition, address, odometer FROM ".$this->table_name." WHERE ";
		//$this->db->select("id, lati, longi, phone_imei, CONVERT_TZ(add_date,'+00:00','$user_tz') as add_date, speed, device_id, dt, ignition, address, odometer");
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d H:i:s", strtotime($sdate));
			$edate = date("Y-m-d H:i:s", strtotime($edate));
		}else{
			$sdate = date("Y-m-d H:i:s");
			$edate = date("Y-m-d H:i:s");
		}
		$qry_rs.="CONVERT_TZ(add_date,'+00:00','$user_tz') BETWEEN '".$sdate."' AND '" . $edate . "'";
		if($device){
			//$this->db->where('assets_id', $device);
			$qry_rs.="AND assets_id=$device ";
		}
		$qry_rs.=" Order by id";
		//$this->db->where("CONVERT_TZ(add_date,'+00:00','$user_tz') BETWEEN '".$sdate."' AND '" . $edate . "'");
		if($device){
			//$this->db->where('assets_id', $device);
		}
		//$this->db->order_by('id');
		//$query = $this->db->get($this->table_name);
		$query = $this->db->query($qry_rs);
		return $query->result_array();
	}
	
	public function prepareCombo(){
		
		$user = $this->session->userdata('user_id');
		
		$this->db->select("assets_name, device_id", FALSE);
		//$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = '.$user.'))');
		$this->db->where('status',1);
		$this->db->where('del_date',null);
		$query = $this->db->get($this->tbl_assets);
		$option = '';
		if($query-> num_rows()!=1)
			$option = "<option value=''>".$this->lang->line("Please Select")."</option>";
		foreach ($query->result() as $row) {
              $option .= "<option value='".$row->device_id."'>".$row->assets_name." (".$row->device_id.")</option>";
        }
		return $option;
	}	
}