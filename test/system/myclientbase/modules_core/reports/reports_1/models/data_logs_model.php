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

class data_logs_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function data_logs_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "tbl_raw_data";
		$this->tbl_assets = "assests_master";
		
		
    }
	function getAllData(){
		
		$cmd = $this->input->get('cmd');
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		$device=$this->input->get('device');
		
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
		
		$SQL = "SELECT * FROM ".$this->table_name." WHERE del_date is null and status = 1"; 
		$SQL .= " AND add_date BETWEEN '" . $sdate . "' AND '" . $edate . "'";

		if($device){	//search by device
			$SQL .= " AND device_id = '$device'";
		}else{
			return;
			die();
		}
		
		$result = $this->db->query($SQL);
		
		
		
		$count = count($result->result_array());
		
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);
			$start = ($limit*$page) - $limit; 
		} else {
			$total_pages = 0;
			$start = 0;
		}

		if ($page > $total_pages) 
			$page = $total_pages;

		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		
		$query = $this->db->query($SQL);
		
		if($cmd == 'export') {
			
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=data_logs". date("s").".xls"); 
			$EXCEL = "";
			$fitr="";
			
			//session date & time format
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			$fitr .="<tr>"; 
			$fitr.="<th>".$this->lang->line("Device ID")."</th>";
			$fitr.="<th>".$this->lang->line("Raw Data")."</th>";
			$fitr.="<th>".$this->lang->line("Date")."</th>";
			$fitr .="</tr>"; 
			 
			foreach($result->result_array() as $data)
				{
					$Date = $data['add_date'];
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data['device_id']."</td>"; 
					$EXCEL.="<td>".$data['raw_data']."</td>";
					$EXCEL.="<td>&nbsp;".date("$date_format $time_format", strtotime($Date))."</td>"; 
					$EXCEL .="</tr>";
					
				}
				if($this->session->userdata('id')==1)
					$count=3;
				else
					$count=2;
				
				echo "<table border='1'>";
				echo "<tr><th colspan='3'> ".$this->lang->line("Data Logs")."</th></tr>";
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
	public function groupAssets(){
		$user_id = $this->session->userdata('user_id');
		$group = $_POST['grp'];
		
		if($user_id != 1){
		  $SQL = "select id, device_id, assets_name from assests_master am where find_in_set(am.id, (SELECT assets_ids FROM user_assets_map um where user_id = $user_id)) AND am.status = 1 AND am.del_date is null";
		}else{
		  $SQL = "SELECT id, device_id, assets_name FROM assests_master am WHERE am.status = 1 AND am.del_date is null";
		}
		
		if($group != ""){
		  $SQL .= " and am.assets_group_id = $group";
		}
		
		$SQL .= " order by am.assets_name asc";
		
		$query = $this->db->query($SQL);
		return $query->result();
	}
	
}
?>