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

class over_speed_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function over_speed_model()
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
		$speed = $this->input->get('speed');
		$group = $this->input->get('group');
		
		if($sdate != "" && $edate != ""){	//search by date
			$sdate = date("Y-m-d H:i:s", strtotime($sdate));
			$edate = date("Y-m-d H:i:s", strtotime($edate));
		}else{
			$sdate = date("Y-m-d H:i:s");
			$edate = date("Y-m-d H:i:s");
		}
		
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
		$user = $this->session->userdata('user_id');   
		
		$SQL = "SELECT count(distinct(tm.id)) as total FROM ".$this->table_name." as tm left join ".$this->tbl_assets." am on am.id = tm.device_id WHERE am.add_uid = $user"; 
		$SQL .= " AND CONVERT_TZ(tm.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";		
		
		if($speed != "")
		{
			$SQL .=" AND tm.speed > '$speed'";
		}else{
			return;
			die();
		}
		if($group != ""){
			$SQL .=" AND find_in_set(tm.assets_id, (select assets from group_master where id = $group))";
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

		
		$SQL = "SELECT tm.id, CONVERT_TZ(tm.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date, tm.speed, am.assets_name FROM ".$this->table_name." as tm left join ".$this->tbl_assets." am on am.id = tm.assets_id WHERE am.add_uid = $user"; 
		$SQL .= " AND CONVERT_TZ(tm.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";		
		
		if($speed != "")
		{
			$SQL .=" AND tm.speed > '$speed'";
		}else{
			return;
			die();
		}
		if($group != ""){
			$SQL .=" AND find_in_set(tm.assets_id, (select assets from group_master where id = $group))";
		}
		
		$export_sql="";
		$export_sql=$SQL;
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		
		$query = $this->db->query($SQL);
		
		if($cmd=="export") 
		{
			$result = $this->db->query($export_sql);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=over_speed". date("s").".xls"); 
			$EXCEL = "";
			$fitr="";
			
			//session date & time format
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			$fitr .="<tr>"; 
			$fitr.="<th>".$this->lang->line("Assets")."</th>";
			$fitr.="<th>".$this->lang->line("Area")."</th>";
			$fitr.="<th>".$this->lang->line("Date")."</th>";
			$fitr.="<th>".$this->lang->line("Status")."</th>";
			$fitr .="</tr>"; 
			 
			foreach($result->result_array() as $data)
				{
					$Date = $data['date'];
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data['device']."</td>"; 
					$EXCEL.="<td>".$data['area']."</td>";
					//$EXCEL.="<td>".$data['add_date']."</td>";
					$EXCEL.="<td>&nbsp;".date("$date_format $time_format", strtotime($Date))."</td>"; 
					$EXCEL.="<td>".$data['status']."</td>";
					if($this->session->userdata('id')==1)
					{
						$EXCEL.="<td>".$data['Owner']."</td>";
					}
					$EXCEL .="</tr>";
					$device_name = $data['device'];
				}
				if($this->session->userdata('id')==1)
					$count=3;
				else
					$count=2;
				
				echo "<table border='1'>";
				echo "<tr><th colspan='4'> ".$this->lang->line("Area In Out")."</th></tr>";
				echo "<tr><th colspan='1'>".$this->lang->line("Start Date")."</th><th colspan='1'>".$this->lang->line("End Date")."</th><th colspan='2'>".$this->lang->line("Assets Name")."</th></tr>";
				echo "<tr><th colspan='1'>&nbsp;".date("$date_format $time_format", strtotime($sdate))."</th><th colspan='1'>&nbsp;".date("$date_format $time_format", strtotime($edate))."</th><th colspan='2'></th></tr>";
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
	function get_map_data(){
			$id=uri_assoc('id');
			
			 $squery="select id, device_id, address, lati, longi, speed, CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date from ".$this->table_name." where id=$id Limit 1";
			$query = $this->db->query($squery);
			return $query->row();	
	
	}
}
?>