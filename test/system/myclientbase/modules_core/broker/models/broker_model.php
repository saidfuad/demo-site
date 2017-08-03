<?php 
class Broker_model extends Model 
{
  /**
   * Instanciar o CI
   */
  public function Broker_model()
  {
    parent::Model();
    $this->CI =& get_instance();
    $this->load->database();
    $this->load->library('session');
    $this->tbl_broker = "tbl_broker";
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
    
    /*$this->db->select('count(id) as record_count')->from($this->tbl_broker);
      $this->db->where('admin_id', $this->session->userdata('user_id'));
      $record_count = $this->db->get();
      $row = $record_count->row();
      $count = $row->record_count;
    */
    
    $SQL = "SELECT * FROM tbl_broker WHERE del_date is null";

    if($where != "")
      $SQL .= " AND $where";
    $result = $this->db->query($SQL);
    $count = $result->num_rows();
    //$count = $this->db->count_all_results('tbl_broker'); 
    if( $count > 0 ) {
      $total_pages = ceil($count/$limit);    
    } else {
      $total_pages = 0;
    }

    if ($page > $total_pages) 
      $page=$total_pages;
    
   
    $SQL = "SELECT us.id, us.first_name, us.last_name, us.address, us.city, us.state, us.zip, us.country, us.phone_number, us.fax_number, us.mobile_number, us.email_address,  us.company_name, us.status, mc.name as city,ms.name as country,mst.name as state FROM tbl_broker as us left join mst_city as mc on mc.id = us.city left join mst_country as ms on ms.id = us.country left join mst_state as mst on mst.id = us.state WHERE us.del_date is null";
    
    if($where != "")
      $SQL .= " AND $where";

    $export_sql = $SQL;
    
    if($cmd=="export")   
      {
	$result = $this->db->query($export_sql);
	header("Content-Type: application/vnd.ms-excel"); 
	header("Content-Disposition: attachment; filename=Broker.xls"); 
	$EXCEL = ""; 
	$fitr="";
	
	//session date & time format 
	$date_format = $this->session->userdata('date_format');  
	$time_format = $this->session->userdata('time_format'); 
	
	$fitr .="<tr>";
	$fitr.="<th>".$this->lang->line('Id')."</th>";
	$fitr.="<th>".$this->lang->line('First Name')."</th>";
	$fitr.="<th>".$this->lang->line('Last Name')."</th>";
	$fitr.="<th>".$this->lang->line('Address')."</th>";
	$fitr.="<th>".$this->lang->line('City')."</th>";
	$fitr.="<th>".$this->lang->line('State')."</th>";
	$fitr.="<th>".$this->lang->line('Country')."</th>";
	$fitr.="<th>".$this->lang->line('Zip')."</th>";
	$fitr.="<th>".$this->lang->line('Phone No')."</th>";
	$fitr.="<th>".$this->lang->line('Mobile No')."</th>";
	$fitr.="<th>".$this->lang->line('Email')."</th>";
	$fitr.="<th>".$this->lang->line('Company Name')."</th>";
	$fitr.="<th>".$this->lang->line('Status')."</th>";
	$fitr .="</tr>"; 
	//var_dump($result);
	foreach($result->result_array() as $data)
	  {
	    if($data['from_date'] != ""){
	      $data['from_date'] = date("Y-m-d H:i:s", strtotime($data['from_date']));
	    }
	    if($data['to_date'] != ""){
	      $data['to_date'] = date("Y-m-d H:i:s", strtotime($data['to_date']));
	    }
	    $EXCEL .="<tr align='center'>";
	    $EXCEL.="<td>".$data['id']." </td>"; 
	    $EXCEL.="<td>".$data['first_name']." </td>"; 
	    $EXCEL.="<td>".$data['last_name']."</td>";
	    $EXCEL.="<td>".$data['address']."</td>";
	    $EXCEL.="<td>".$data['city']."</td>";
	    $EXCEL.="<td>".$data['state']."</td>";
	    $EXCEL.="<td>".$data['country']."</td>";
	    $EXCEL.="<td>".$data['zip']."</td>";
	    $EXCEL.="<td>".$data['phone_number']."</td>";
	    $EXCEL.="<td>".$data['mobile_number']."</td>";
	    $EXCEL.="<td>".$data['email_address']."</td>";
	    $EXCEL.="<td>".$data['company_name']."</td>";
	    $EXCEL.="<td>".$data['status']."</td>";
	    $EXCEL .="</tr>";
	  }
	
	echo "<table border='1'>";
	
	echo "<tr><th colspan='13'>".$this->lang->line('Users')."</th></tr>";
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
  public function delete_users() 
  {
    $ids = $_POST["id"];
    $dt = gmdate('Y-m-d H:i:s');
    $tblUsr="UPDATE `tbl_broker` SET `status`=0, `del_date`='".$dt."', `del_uid`=".$this->session->userdata('user_id')."  WHERE id in(".$ids.")";
    $this->db->query($tblUsr) or die("error");
    return TRUE;
  }
  public function validate() {
    
    $this->form_validation->set_rules('first_name', 'First Name');
    $this->form_validation->set_rules('last_name', 'Last Name');
    return parent::validate();

  }
  function save($db_array, $id=NULL, $set_flashdata = TRUE) {
     
    $success = TRUE;
   
    $this->db->insert("tbl_broker", $db_array);
    $insert_id = $this->db->insert_id();
    
    return $success;

  }
  function getCountries()
  {
    $SQL = "SELECT id, name FROM mst_country";
    $query = $this->db->query($SQL);
    return $query->result_array();
  }
  function getState($id)
  {
    if($id!="")
      {
	$SQL = "SELECT id,name FROM mst_state where FK_mst_country_p_id =".$id;
	$query = $this->db->query($SQL);
	return $query->result_array();
      }
  }
  function getCurrent($id)
  {
    if($id!="")
      {
	$SQL = "SELECT country FROM tbl_broker where id=".$id;
	$query = $this->db->query($SQL);
	return $query->result_array();
      }
  }
  public function state(){
    $id=uri_assoc('id');
    $state=uri_assoc('state');
    if($id == 0 OR $id == "")
      {
	return "<option value='' >".$this->lang->line('Select State')."</option>";}else
      {
	$query="select id, name from mst_state where FK_mst_country_p_id='$id' AND  status= '1' AND del_uid is Null AND del_date is Null Order by name";
	$data = $this->db->query($query);

	$opts="<option value='' >".$this->lang->line('Select State')."</option>";

	foreach ($data->result() as $row)
	  {
	    if($state!="" && $state==$row->id)
	      $opts.="<option value='".$row->id."' selected='selected' >".$row->name."</option>";
	    else
	      $opts.="<option value='".$row->id."' >".$row->name."</option>";
	  }
	return $opts;
      }
  }
  public function city(){
    $id=uri_assoc('id');
    $city=uri_assoc('city');
    
    if($id == 0 OR $id == "")
      {
	return "<option value='' >".$this->lang->line('Select City')."</option>";
      }else
      {
	$query="select id, name from mst_city where FK_mst_state_p_id='$id' AND  status= '1' AND del_uid is Null AND del_date is Null Order by name";
	$data = $this->db->query($query);

	$opts="<option value='' >".$this->lang->line('Select City')."</option>";
	
	foreach ($data->result() as $row)
	  {
	    if($city!="" && $city==$row->id)
	      $opts.="<option value='".$row->id."' selected='selected' >".$row->name."</option>";
	    else
	      $opts.="<option value='".$row->id."' >".$row->name."</option>";
	  }
	return $opts;
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
    $qry="select * from tbl_broker where ";

    if($id!="")
      {
	$qry.=" id!=".$id." AND ";
      } 
    $qry.=" status=1 and del_date is null";
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
  
}
?>