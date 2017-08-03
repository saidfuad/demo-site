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

class Distance_vehicle_report_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function distance_vehicle_report_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "tbl_distance_inspection";
    }
	public function get_distancevehicle_report($cmd) 
	{
		$user = $this->session->userdata('user_id');
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		$device = $this->input->get('device');
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
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:10; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'id'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';
	//	$cmd=uri_assoc('cmd');
		
		$where = ""; 
		$searchField = isset($_GET['searchField']) ? $_GET['searchField'] : false;
		$searchOper = isset($_GET['searchOper']) ? $_GET['searchOper']: false;
		$searchString = isset($_GET['searchString']) ? $_GET['searchString'] : false;

		if (isset($_GET['_search']) && $_GET['_search'] == 'true'){
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
		//$SQL = "SELECT count(*) as total from tbl_distance_inspection dm WHERE date(dm.add_date) BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		$SQL = "SELECT count(*) as total from tbl_distance_inspection dm WHERE CONVERT_TZ(dm.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "' "; 
		if($device!="")	//search by device
			$SQL .= " AND (find_in_set(dm.asset_id1,'$device') or find_in_set(dm.asset_id2,'$device'))";
		else{
			$SQL .= " AND (dm.asset_id1 in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user))) or dm.asset_id2 in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user))))";
		}
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
		
		$SQL = "SELECT dm.id, (select assets_name from assests_master where id=dm.asset_id1) as asset_id1, (select assets_name from assests_master where id=dm.asset_id2) as asset_id2, dm.asset1_lat_lng, dm.asset2_lat_lng, dm.distance, dm.asset1_lat_lng, dm.asset2_lat_lng, CONVERT_TZ(dm.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date from tbl_distance_inspection dm WHERE CONVERT_TZ(dm.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "' "; 
		if($device!="")	//search by device
			//$SQL .= " AND (dm.asset_id1 = $device or dm.asset_id2 = $device)";
			$SQL .= " AND (find_in_set(dm.asset_id1,'$device') or find_in_set(dm.asset_id2,'$device'))";
		else{
			//$SQL .= " AND (dm.asset_id1 in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user))) or dm.asset_id2 in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user))))";
			return;
			die();
		}
		if($where != "")
			$SQL .= " AND $where";
			
		$export_sql="";
		$export_sql=$SQL;
		
		 
		if($cmd=="export")
		{
			$device_name='';
			$devName='';
			$result = $this->db->query($export_sql);
			if($device!=""){
				$SQLAssets = $this->db->query("select assets_name from assests_master where id=$device Limit 1");
				$rs=$SQLAssets->result_array();
				$devName='';
				if(count($rs)==1){
					$devName=$rs[0]['assets_name'];
				}
			}else{
				
			}
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=Distance_Between_Vehicle_Report.xls"); 
			$EXCEL = ""; 
			$fitr="";
			
			//session date & time format 
			$date_format = $this->session->userdata('date_format');  
			$date_format .= " ".$this->session->userdata('time_format'); 
			
			$fitr .="<tr>"; 
			$fitr.="<th>".$this->lang->line("Date time")."</th>";
			$fitr.="<th>".$this->lang->line("1st Vehicle")."</th>";
			$fitr.="<th>".$this->lang->line("2nd Vehicle")."</th>";
			$fitr.="<th>".$this->lang->line("Distance(KM)")."</th>";
			$fitr .="</tr>"; 
			//var_dump($result);
			
			foreach($result->result_array() as $data)
				{
					$add_date = $data['add_date'];
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td> &nbsp;".date($date_format,strtotime($add_date))." </td>"; 
					$EXCEL.="<td>".$data['asset_id1']."</td>";
					$EXCEL.="<td>".$data['asset_id2']."</td>";
					$EXCEL.="<td>".$data['distance']."</td>"; 
					$EXCEL .="</tr>";
					$device_name = $devName;
				}
			if($this->session->userdata('id')==1)
				$count=3;
			else
				$count=2;
			
			echo "<table border='1'>";
			$top="";
			$top .= "<tr><th colspan='4'>".$this->lang->line("Distance")."</th></tr>";
			$top .= "<tr><th colspan='1'>".$this->lang->line("Start Date")."</th><th colspan='1'>".$this->lang->line("End Date")."</th><th colspan='2'>".$this->lang->line("Assets Name")."</th></tr>";
			$top .= "<tr><th colspan='1'>&nbsp;".date("$date_format", strtotime($sdate))."</th><th colspan='1'>&nbsp;".date("$date_format", strtotime($edate))."</th><th colspan='2'></th></tr>";
			echo $top;
			echo $fitr;
			echo $EXCEL;
			echo "</table>";
			die();
		}
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		//die($SQL);
		$query = $this->db->query($SQL);
		$data = array();
		$data['result'] = $query->result();
		//$data['count_pay'] = $query1->result();
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;
		return $data; 
	}
	function get_map_data(){
		$id=uri_assoc('id');
		$SQL = "SELECT dm.id, asset_id1,(select assets_name from assests_master where id=dm.asset_id1) as assets_name1,  asset_id2, (select assets_name from assests_master where id=dm.asset_id2) as assets_name2, dm.asset1_lat_lng, dm.asset2_lat_lng, dm.distance, dm.asset1_lat_lng, dm.asset2_lat_lng, CONVERT_TZ(dm.add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date from tbl_distance_inspection dm WHERE id=$id Limit 1";
		$query = $this->db->query($SQL);
		return $query->result_array();
	}
	function get_map_data_html($id1,$id2){
	
		$SQL = "SELECT am.id, am.assets_name, am.device_id,am.assets_friendly_nm, am.assets_image_path, am.driver_name, am.driver_image, am.driver_mobile, im.icon_path from assests_master am left join icon_master im on im.id=am.icon_id where am.id in ($id1,$id2)";	
		$query = $this->db->query($SQL);
		return $query->result();
	}
	
}
?>