<?php 
class tbl_geodata_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function tbl_geodata_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
    }
	function getAllData(){
		$type = isset($_REQUEST["type"])?$_REQUEST["type"]:'All'; 
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:3; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'id'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';         
		$start = $limit*$page - $limit; 
		$start = ($start<0)?0:$start; 
		$where = ""; 
		$searchField = isset($_GET['searchField']) ? $_GET['searchField'] : false;
		$searchOper = isset($_GET['searchOper']) ? $_GET['searchOper']: false;
		$searchString = isset($_GET['searchString']) ? $_GET['searchString'] : false;
		$cmd=uri_assoc('cmd');
		
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
		
		$SQL = "SELECT count(*) as total FROM tbl_geodata where del_date is null and status = 1";
	
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
	
		$SQL = "select * from tbl_geodata where del_date is null and status = 1";
		if($where != "")
			$SQL .= " AND $where";
		$result = $this->db->query($SQL);
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		$query = $this->db->query($SQL);
		
		if($cmd =='export')
		{
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=tbl_geodata". date("s").".xls"); 
			$EXCEL = "";
			$fitr="";
			$fitr .="<tr>"; 
			$fitr.="<th>Cell</th>";
			$fitr.="<th>Lac</th>";
			$fitr.="<th>Latitude</th>";
			$fitr.="<th>Longitude</th>";
			$fitr.="<th>Address</th>";
			$fitr .="</tr>";
			foreach($result->result_array() as $data) 
			{
				$EXCEL .="<tr align='center' style='color:green'>";
				$EXCEL.="<td>".$data['cell_id']."</td>"; 
				$EXCEL.="<td>".$data['lac']."</td>"; 
				$EXCEL.="<td>".$data['latitude']."</td>"; 
				$EXCEL.="<td>".$data['longitude']."</td>"; 
				$EXCEL.="<td>".$data['address']."</td>"; 
				$EXCEL .="</tr>";
			}
			$EXCEL .="<tr><td colspan='5'></td></tr>";
			if($EXCEL=="")
			{
				$EXCEL = "<tr align='center'><td colspan='4'> No Record Found</td></tr>";
			}
			echo "<table border='1'>";
			echo "<tr><th colspan='5'> Geo Data Details on ".date('d.m.Y h:i:s A')."</th></tr>";
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

	public function delete_tbl_geodata() 
	{
		$ids = $_POST["id"];
		$dt=date("Y-m-d H:i:s");
		$tblUsr="delete from `tbl_geodata` WHERE id in(".$ids.")";
		$this->db->query($tblUsr) or die("error");
		return TRUE;
	}
		
	public function validate() {
		
		$this->form_validation->set_rules('type', 'Type');
		return parent::validate();

	}
	function save($db_array, $id=NULL, $set_flashdata = TRUE) {
		$success = TRUE;
		
		$db_array['add_uid'] = $this->session->userdata('user_id');
		
		$db_array['add_date'] = date('Y-m-d H:i:s');
			
		$this->db->insert("tbl_geodata", $db_array);
		return $success;

	}	
	function checkDuplicate($type,$id)
	{
		$id = $id?$id:-1;
	
		$this->db->where("type",$type);
		$this->db->where_not_in("id",$id);
		$this->db->where("status",1);
		$this->db->from('tbl_geodata');
		if($this->db->count_all_results()<1)
		{
			return "true";
		}
		else
		{
			return "false";
		}
		
	}
}
?>