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

class Battery_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function battery_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "tbl_last_point";
		$this->tbl_assets = "assests_master";
    }
	function getAllData($cmd){
		$user = $this->session->userdata('user_id');
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		//$device = $this->input->get('device');
		$device=trim($this->input->get('device'),",");
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
		
		$SQL = "SELECT count(*) as total FROM ".$this->table_name." tm left join assests_master am on am.device_id=tm.device_id WHERE am.id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";
		
		if($device!=""){	//search by device
			$SQL .= " AND find_in_set(am.id,'$device')";
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
		
		
		$SQL = "SELECT tm.id, tm.device_id, tm.in_batt, tm.ext_batt_volt, am.assets_name FROM ".$this->table_name." tm left join assests_master am on am.device_id=tm.device_id WHERE am.id in (select id from assests_master where find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user)))";
		
		if($device!=""){	//search by devices
			$SQL .= " AND find_in_set(am.id,'$device')";
		}
			
		if($where != "")
			$SQL .= " AND $where";
		
		$export_sql="";
		$export_sql=$SQL;
		if($cmd=="export") 
		{
			$result = $this->db->query($export_sql);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=battery". date("s").".xls");
			$EXCEL = "";
			$fitr="";
			
			//session date & time format
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			$fitr .="<tr>"; 
			$fitr.="<th>".$this->lang->line("Assets Name")."</th>";
			$fitr.="<th>".$this->lang->line("Device Battery")."</th>";
			$fitr.="<th>".$this->lang->line("Vehicle Battery")."</th>";
			$fitr .="</tr>";
			foreach($result->result_array() as $data)
			{
				$add_date = $data['add_date'];
				$data['in_batt'] = round($data['in_batt']/1000, 2);
				$data['ext_batt_volt'] = round($data['ext_batt_volt']/1000, 2);
				$EXCEL .="<tr align='center'>";
				$EXCEL.="<td>".$data['assets_name']."(".$data['device_id'].")</td>"; 
				$EXCEL.="<td>".$data['in_batt']."</td>";
				$EXCEL.="<td>".$data['ext_batt_volt']."</td>";
				
				$EXCEL .="</tr>";
				
				$device_name=$data['assets_name']." (".$data['device_id'].")";
			}
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
?>