<?php 
class Zone_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Zone_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
    }
	function getAllData($cmd){
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
			
		/*$this->db->select('count(user_id) as record_count')->from($this->tbl_users);
		$this->db->where('admin_id', $this->session->userdata('user_id'));
		$record_count = $this->db->get();
		$row = $record_count->row();
		$count = $row->record_count;
		*/
	
		$SQL = "SELECT count(*) as total FROM landmark_areas as ar where ar.Audit_Status=1 and ar.Audit_Del_Dt is null and ar.Audit_Enter_uid = ".$this->session->userdata('user_id');
	
		if($where != "")
			$SQL .= " AND $where";
		$SQL .= " group by polyid";
		$result = $this->db->query($SQL);
		$data_arr=$result->result_array();
		$count = count($data_arr);
		//$count = $this->db->count_all_results('tbl_users'); 
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
	
		if ($page > $total_pages) 
			$page=$total_pages;
			
		/*$this->db->select('user_id,first_name,last_name');
		$this->db->limit($limit);
		if($where != "")
			$this->db->where($where,NULL,FALSE);
		$this->db->order_by($sidx,$sord);
		$query = $this->db->get($this->tbl_users,$limit,$start);
		*/

		$SQL = "SELECT ar.id, (SELECT GROUP_CONCAT(`assets_name`) FROM assests_master WHERE find_in_set(id,ar.deviceid)) as deviceid, ar.polyid, ar.polyname, ar.color, ar.in_alert, ar.out_alert, ar.sms_alert, ar.email_alert, ar.area_type_opt FROM landmark_areas ar where Audit_Status=1 and ar.Audit_Enter_uid = ".$this->session->userdata('user_id');
		if($where != "")
			$SQL .= " AND $where";
		$export_sql = $SQL." group by ar.polyid";
		$SQL .= " group by ar.polyid ORDER BY $sidx $sord LIMIT $start, $limit";
		$query = $this->db->query($SQL);
		
		if($cmd=="export")   
		{
			$result = $this->db->query($export_sql);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=zones.xls"); 
			$EXCEL = ""; 
			$fitr="";
			
			//session date & time format 
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			$fitr .="<tr>"; 
			$fitr.="<th>Area Name</th>";
			$fitr.="<th>In Alert</th>";
			$fitr.="<th>Out Alert</th>";
			$fitr.="<th>Sms Alert</th>";
			$fitr.="<th>Email Alert</th>";
			$fitr .="</tr>"; 
			//var_dump($result);
			foreach($result->result_array() as $data)
				{
					
					$EXCEL .="<tr align='center'>";
					$EXCEL.="<td>".$data['polyname']." </td>"; 
					$EXCEL.="<td>".$data['in_alert']."</td>";
					$EXCEL.="<td>".$data['out_alert']."</td>";
					
					if($data['sms_alert'] == 0)
						$data['sms_alert'] = 'No';
					else
						$data['sms_alert'] = 'Yes';
					$EXCEL.="<td>".$data['sms_alert']."</td>";
					
					if($data['email_alert'] == 0)
						$data['email_alert'] = 'No';
					else
						$data['email_alert'] = 'Yes';
					$EXCEL.="<td>".$data['email_alert']."</td>";
					$EXCEL .="</tr>";
				}
			if($this->session->userdata('id')==1)
				$count=3;
			else
				$count=2;
			
			echo "<table border='1'>";
			
			echo "<tr><th colspan='5'>Area</th></tr>";
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

	public function delete_zone() 
	{
		$ids = $_POST["id"];
		$qry="SELECT group_concat(polyid) as polyids from  landmark_areas where find_in_set(id,'".$ids."')";
		$rs=$this->db->query($qry);
		$polyids=$rs->result_array();
		$polyid=$polyids[0]['polyids'];
		$dt=gmdate("Y-m-d H:i:s");
		$tblUsr="UPDATE `landmark_areas` SET `Audit_Status`=0, `Audit_Del_Dt`='".$dt."', `Audit_Del_uid`=".$this->session->userdata('user_id')."  WHERE polyid in(".$polyid.")";
		$this->db->query($tblUsr) or die("error");
		return TRUE;
	}
		
	public function validate() {
		
		$this->form_validation->set_rules('first_name', 'First Name');
		$this->form_validation->set_rules('last_name', 'Last Name');
		return parent::validate();

	}
	public function menu_entery_user($user_id,$nm){
		$query=$this->db->query("select user_id from tbl_users where username = '".$nm."'");
		$result=$query->result_array();
		$newuser_id=$result[0]['user_id'];
		$menu_query=$this->db->query("select * from app_menu_master where user_id = $user_id and del_date is null");
		$i=0;
		$colget=$this->db->query("SHOW COLUMNS FROM app_menu_master");
		$colresult=$colget->result_array();
		$allcolumn="";
		foreach($colresult as $row)
		{
			if($colget->num_rows() - 1== $i)
			{
				$allcolumn .=$colresult[$i]['Field'];
			}else{
				$allcolumn .=$colresult[$i]['Field'].",";
			}
			$i++;
			
		}
		
		foreach($menu_query->result_array() as $row)
		{			
			$row['id']."<br>";
			$menu_id=$row['menu_id'];
			$priority=$row['priority'];
			$where_slow=$row['where_to_show'];
			$suser_id=$row['user_id'];
			$admin_id=$row['add_uid'];
			$add_date=$row['add_date'];
			$status=$row['status'];
			$comments=$row['Comments'];	
			$inserquery=$this->db->query("insert into app_menu_master(".$allcolumn.") values('',$menu_id,$priority,'$where_slow',$newuser_id,$user_id,'$add_date',null,null,$status,'$comments')");
		}

		
	}	
	function save($db_array, $id=NULL, $set_flashdata = TRUE) {

		$success = TRUE;
		$this->db->where('polyid',$db_array['polyid']);
		$this->db->update("landmark_areas", $db_array);

		return $success;

	}
	function getCountries()
	{
		$SQL = "SELECT id, name FROM mst_country";
		$query = $this->db->query($SQL);
		return $query->result_array();
	}
	function getCurrent($id)
	{
		if($id!="")
		{
			$SQL = "SELECT country FROM tbl_users where user_id=".$id;
			$query = $this->db->query($SQL);
			return $query->result_array();
		}
	}
	public function state(){
		$id=uri_assoc('id');
		$state=uri_assoc('state');
		if($id == 0 OR $id == "")
		{echo "<option value='' >Select State</option>";}else
		{
		$query="select id, name from mst_state where FK_mst_country_p_id='$id' AND  status= '1' AND del_uid is Null AND del_date is Null";
		$data = $this->db->query($query);

			echo "<option value='' >Select State</option>";

		foreach ($data->result() as $row)
			{
				if($state!="" && $state==$row->id)
					echo "<option value='".$row->id."' selected='selected' >".$row->name."</option>";
				else
					echo "<option value='".$row->id."' >".$row->name."</option>";
			}
		}
	}
	public function city(){
		$id=uri_assoc('id');
		$city=uri_assoc('city');
	
		if($id == 0 OR $id == "")
		{echo "<option value='' >Select City</option>";}else
		{
		$query="select id, name from mst_city where FK_mst_state_p_id='$id' AND  status= '1' AND del_uid is Null AND del_date is Null";
		$data = $this->db->query($query);

			echo "<option value='' >Select City</option>";

		foreach ($data->result() as $row)
			{
				if($city!="" && $city==$row->id)
					echo "<option value='".$row->id."' selected='selected' >".$row->name."</option>";
				else
					echo "<option value='".$row->id."' >".$row->name."</option>";
			}
		}
	}
	public function get_json()
	{
		$query="select * from mst_country as cn left join mst_state as st on cn.id=st.FK_mst_country_p_id left join mst_city as ct on st.id=ct.FK_mst_state_p_id";
		$data = $this->db->query($query);
		return $data->result();
	}
	public function checkUserDuplicate($user,$id)
	{
		$qry="select * from tbl_users where ";

		if($id!="")
		{
			$qry.=" user_id!=".$id." AND ";
		} 
		$qry.=" username='".$user."' AND status=1 and del_date is null";
		$rarr=$this->db->query($qry);
		if($rarr->num_rows()<1)
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	public function prepare_assets() 
	{		
		$user = $this->session->userdata('user_id');
		$this->db->select('*');
		$this->db->where('find_in_set(id, (select assets_ids from user_assets_map where user_id = '.$user.'))');
		$this->db->where('status',1);
		$this->db->where('del_date',null);
		$this->order_by = 'id';
		$query = $this->db->get('assests_master');
		return $query->result();
	}
	public function getAddressBookGroupList()
	{
		$user = $this->session->userdata('user_id');
		$this->db->select('id, group_name');
		$this->db->where('add_uid',$user);
		$this->db->where('status',1);
		$this->db->where('del_date',null);
		$this->order_by = 'id';
		$query = $this->db->get('addressbook_group');
		return $query->result();
	}
	public function prepare_LandmarkGroups_1()
	{
		$user = $this->session->userdata('user_id');
		$this->db->select('id, landmark_group_name');
		$this->db->where('user_id',$user);
		$this->order_by = 'id';
		$query = $this->db->get('landmark_group');
		return $query->result();
	}
	
}
?>