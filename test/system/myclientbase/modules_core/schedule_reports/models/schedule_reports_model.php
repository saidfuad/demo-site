<?php 
class schedule_reports_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function schedule_reports_model() 
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library("session");
    }
	function getAllData(){
	
		
		$page = isset($_GET["page"])?$_GET["page"]:1; 
		$limit = isset($_GET["rows"])?$_GET["rows"]:3; 
		$sidx = isset($_GET["sidx"])?$_GET["sidx"]:"id"; 
		$sord = isset($_GET["sord"])?$_GET["sord"]:"";         
		$start = $limit*$page - $limit; 
		$start = ($start<0)?0:$start; 
		$cmd=uri_assoc("cmd");

		if(!$sidx) 
			$sidx =1;
		$SQL = "select reports,id,(select group_concat(concat(assets_name,'(',device_id,')')) from assests_master where FIND_IN_SET(id,assets_ids))  as `assets_ids`,`email_addresses`,`daily_monthly_weekly`,`excel_pdf` from schedule_reports where del_date is NULL AND status = 1 and add_uid =" .$this->session->userdata("user_id");
		$result = $this->db->query($SQL);
		$count = $result->num_rows();
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		
		$query = $this->db->query($SQL);
		
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);    
		} else {
			$total_pages = 0;
		}

		if ($page > $total_pages)  
			$page=$total_pages;
		/*if($cmd =="export")
		{
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=schedule_reports.xls"); 
			$EXCEL = "";
			$fitr="";
			$fitr .="<tr>"; 
			$fitr.="<th>assets_ids</th>";
			$fitr.="<th>email_addresses</th>";
			$fitr.="<th>daily_monthly_weekly</th>";
			$fitr.="<th>excel_pdf</th>";
			
			$fitr .="</tr>";
			foreach($result->result_array() as $data){
			
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data["assets_ids"]."</td>";
					$EXCEL.="<td>".$data["email_addresses"]."</td>";
					$EXCEL.="<td>".$data["daily_monthly_weekly"]."</td>";
					$EXCEL.="<td>".$data["excel_pdf"]."</td>";
					
					$EXCEL .="</tr>";
			}
			
			echo "<table border='1'>";
			echo "<tr><th colspan='3'> schedule_reports  on ".date("d.m.Y h:i:s A")."</th></tr>";
			echo $fitr;
			echo $EXCEL;
			echo "</table>";
			die();
		}*/
	
		$data = array();
		$data["result"] = $query->result();
		$data["page"] = $page;
		$data["total_pages"] = $total_pages;
		$data["count"] = $count;
		return $data; 
		
	}
	public function validate() {
		
		$this->form_validation->set_rules("group_name", "Group Name");
		
		return parent::validate();

	}
	 function save($db_array, $id=NULL, $set_flashdata = TRUE) {
		 $success = TRUE;
		 $this->db->insert("schedule_reports", $db_array);
		 return $success;

	 }
	public function delete_schedule_reports()  
	{
		$ids = $_POST["id"];
		$date=date("Y-m-d H:i:s");
		$delete_schedule_reports = $this->db->query("UPDATE `schedule_reports` SET status=0, del_uid=".$this->session->userdata("user_id").", del_date='".$date."' WHERE id in(".$ids.")");
		
		return TRUE;
	}
}
?>