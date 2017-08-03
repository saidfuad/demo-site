<?php 
class Main_menu_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function main_menu_model() 
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_group = "group_master";
		$this->icon_master = "icon_master";
    }
	function getAllData(){
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-d");
			$edate = date("Y-m-d");
		}
		
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
		 	
		  
		$SQL = "select * from main_menu_master where del_date is NULL AND status = 1";
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
		
		$SQL = "select * from main_menu_master where del_date is NULL AND status = 1";
	
		if($where != "")   
			$SQL .= " AND $where";
		$SQL .= " ORDER BY $sidx $sord LIMIT $start, $limit";
		
		$query = $this->db->query($SQL);
		
		if($cmd=="export") 
		{
		
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=main_menu". date("s").".xls"); 
			$EXCEL = "";
			$fitr="";
					
			if($this->session->userdata('id')==1)
			{
				$fitr.="<th>Owner</th>";
			}
			$fitr .="<tr>"; 
			$fitr.="<th>menu Name</th>";
			$fitr.="<th>menu Link</th>";
			$fitr.="<th>Where To Show</th>";
			$fitr.="<th>menu Sound</th>";
			$fitr.="<th>Tab Title</th>";
			$fitr.="<th>menu Level</th>";
			$fitr.="<th>Parent main_menu</th>";
			$fitr.="<th>menu Image</th>";
			$fitr.="<th>Priority</th>";
			//$fitr.="<th>Comments</th>";
			
			$fitr .="</tr>"; 
			foreach($result->result_array() as $data) 
				{
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data['menu_name']."</td>"; 
					$EXCEL.="<td>".$data['menu_link']."</td>";
					$EXCEL.="<td>".$data['where_to_show']."</td>";
					$EXCEL.="<td>".$data['menu_sound']."</td>";
					$EXCEL.="<td>".$data['tab_title']."</td>";          
					$EXCEL.="<td>".$data['menu_level']."</td>"; 
					$EXCEL.="<td>".$data['parent_menu_id']."</td>";
					$EXCEL.="<td>".$data['menu_image']."</td>"; 
					$EXCEL.="<td>".$data['priority']."</td>"; 
					//$EXCEL.="<td>".$data['Comments']."</td>";
					$EXCEL .="</tr>";
				}
			if($this->session->userdata('id')==1)
				$count=3;
			else
				$count=2;

			echo "<table border='1'>";
			echo "<tr><th colspan='9'> Main Menu List</th></tr>";
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
	public function delete_menus()  
	{
		$ids = $_POST["id"];
		$date=gmdate("Y-m-d H:i:s");
		$delete_menus = $this->db->query("UPDATE `main_menu_master` SET status=0, del_uid=".$this->session->userdata('user_id').", del_date='".$date."' WHERE id in(".$ids.")");
		
		return TRUE; 
	} 
	public function validate() {
		
		$this->form_validation->set_rules('group_name', 'Group Name');
		
		return parent::validate();

	}
	
	// function save($db_array, $id=NULL, $set_flashdata = TRUE) {

		// $success = TRUE;
		// $this->db->query("group_master", $db_array);
		
		// return $success;

	// }
	function save($db_array, $id=NULL, $set_flashdata = TRUE) {

		$success = TRUE;
		
		$user = $this->session->userdata('user_id');
		$id = uri_assoc('id');
		$this->db->insert("main_menu_master", $db_array);
		$insert_id = $this->db->insert_id();
		
		return $success;

	}
	function save1($db_array, $checkdata, $id=NULL, $set_flashdata = TRUE) {
		
		$success = TRUE; 
		
		$user = $this->session->userdata('user_id');  
		$id = uri_assoc('id');
		if($checkdata['is_admin'] == 1){
			$this->db->set('user_id', $this->session->userdata('user_id'));
			$this->db->insert("app_menu_master", $db_array); 
			$insert_id = $this->db->insert_id();
		}
		else {  
			$SQL = "SELECT user_id, username FROM `tbl_users` "; 		
			$query = $this->db->query($SQL);
			if ($query->num_rows() > 0)
			{
			   foreach ($query->result() as $row) 
			   {
				  $user_id[] =  $row->user_id; 
				  //$db_array['user_id'] = $row->user_id;
				  $this->db->set('user_id', $row->user_id);
				  $this->db->insert("app_menu_master", $db_array); 
				  $insert_id = $this->db->insert_id(); 
				} 
			} 
		}
		return $success;

	}
}
?>  