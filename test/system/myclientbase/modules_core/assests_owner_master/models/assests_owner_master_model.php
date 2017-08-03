<?php 
class assests_owner_master_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function assests_owner_master_model() 
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
		$user = $this->session->userdata('user_id');
		$SQL = "select id,`owner`,`comments` from assests_owner_master where del_date is NULL AND status = 1 AND add_uid = $user";
		$result = $this->db->query($SQL);
			
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
		if($cmd =="export")
		{
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=AssestsOwner.xls"); 
			$EXCEL = "";
			$fitr="";
			$fitr .="<tr>"; 
			$fitr.="<th>Assets Owner</th>";
			$fitr .="</tr>";
			foreach($result->result_array() as $data){
			
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data["owner"]."</td>";
					$EXCEL .="</tr>";
			}
			
			echo "<table border='1'>";
			echo $fitr;
			echo $EXCEL;
			echo "</table>";
			die();
		}
	
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
		 $this->db->insert("assests_owner_master", $db_array);
		 return $success;

	 }
	public function delete_assests_owner_master()  
	{
		$ids = $_POST["id"];
		$date=date("Y-m-d H:i:s");
		$delete_assests_owner_master = $this->db->query("UPDATE `assests_owner_master` SET status=0, del_uid=".$this->session->userdata("user_id").", del_date='".$date."' WHERE id in(".$ids.")");
		
		return TRUE;
	}
}
?>