<?php 
class Subscription_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Subscription_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_group = "group_master";
		$this->icon_master = "icon_master";
    }
	function getAllData(){
		$sdate = $this->input->get('subscription_sdate');
		$edate = $this->input->get('subscription_edate');
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
		$limit = isset($_GET["rows"])?$_GET["rows"]:3; 
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:'id'; 
		$sord = isset($_GET['sord'])?$_GET['sord']:'';         
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
			
		$cur_date = date("Y-m-d");
		$SQL = "SELECT * FROM subscription WHERE CONVERT_TZ(sub_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate."'";
		if($where != "")
			$SQL .= " AND $where";
		$result = $this->db->query($SQL);
		$count = $result->num_rows();
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);    
		} else {
			$total_pages = 0;
		}

		if ($page > $total_pages)
			$page=$total_pages;
			
		$SQL = "SELECT  id, CONVERT_TZ(sub_date,'+00:00','".$this->session->userdata('timezone')."') as sub_date, sub_valide_from, sub_valide_to, sub_charges, payment_status FROM subscription WHERE CONVERT_TZ(sub_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "'";
		
		if($where != "")      
			$SQL .= " AND $where";
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		//die($SQL);
		$query = $this->db->query($SQL);
		if($cmd=="export") 
		{
		
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=subscription". date("s").".xls"); 
			$EXCEL = "";
			$fitr="";
			
			//session date & time format
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format');  

			if($this->session->userdata('id')==1)
			{
				$fitr.="<th>Owner</th>";
			}
			$fitr .="<tr>"; 
			$fitr.="<th>Sub Date</th>";
			$fitr.="<th>Sub Valide From</th>";
			$fitr.="<th>Sub Valide To</th>";
			$fitr.="<th>Sub Charges</th>";
			$fitr.="<th>Payment Status</th>";
			$fitr .="</tr>"; 
			foreach($result->result_array() as $data)  
				{
					$sub_date = $data['sub_date'];
					$sub_valide_from = $data['sub_valide_from'];
					$sub_valide_to = $data['sub_valide_to'];
					if($data['payment_status'] == 0)
						$data['payment_status'] = "Unpaid";
					else
						$data['payment_status'] = "Paid";
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>&nbsp;".date("$date_format $time_format", strtotime($sub_date))."</td>";
					$EXCEL.="<td>".date("$date_format $time_format", strtotime($sub_valide_from))."</td>";
					$EXCEL.="<td>".date("$date_format $time_format", strtotime($sub_valide_to))."</td>";
					$EXCEL.="<td>".$data['sub_charges']."</td>";
					$EXCEL.="<td>".$data['payment_status']."</td>";
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
			echo "<tr><th colspan='5'> subscription</th></tr>";
			echo "<tr><th colspan='3'>Start Date</th><th colspan='2'>End Date</th></tr>";
			echo "<tr><th colspan='2'>&nbsp;".date("$date_format $time_format", strtotime($sdate))."</th><th colspan='3'>&nbsp;".date("$date_format $time_format", strtotime($edate))."</th></tr>";
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
	
	public function validate() {
		
		$this->form_validation->set_rules('group_name', 'Group Name');
		
		return parent::validate();

	}
	
	function save($db_array, $id=NULL, $set_flashdata = TRUE) {

		$success = TRUE;
		$this->db->query("group_master", $db_array);
		
		return $success;

	}
}