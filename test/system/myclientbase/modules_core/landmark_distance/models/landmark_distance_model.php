<?php 
class landmark_distance_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function landmark_distance_model()
    {
       parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->tbl_assets_type = "landmark_log";
		$this->icon_master = "icon_master";
    }
	function get_map_data(){
		if($_REQUEST['cmd']=='landmark_log')
		{
			$id = $this->input->get('id');
			$query = $this->db->query("SELECT lg.*,concat(am.assets_name,'(',am.device_id,')') as device_name, lm.name as landmark_name from landmark_log as lg LEFT JOIN landmark as lm ON lg.landmark_id = lm.id LEFT JOIN assests_master as am ON am.id = lg.device_id where lg.id=".$id);
			return $query->result();	
		} 
		
	}
	function getAllData(){
		
		$session_data = $this->session->all_userdata();
		$user = $this->session->userdata('user_id');
		
		$landmark = $this->input->get('landmark');
		$distance = $this->input->get('distance');
		
		$whereSearch = "";
	
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
		
		$user = $this->session->userdata('user_id');		
		
		$sub = '';
		
		$query = $this->db->query("select lat, lng, name from landmark where id = $landmark");
		$row = $query->row();
		$lat = $row->lat;
		$lng = $row->lng;
		$landmark_name = $row->name;
			
		if($this->session->userdata('usertype_id') != 1){	
			$sub = " and find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = $user))";
		}

		//SELECT am.assets_name, ( 3959 * acos( cos( radians(-37.592789) ) * cos( radians( lp.lati ) ) * cos( radians( lp.longi ) - radians(145.128199) ) + sin( radians(-37.592789) ) * sin( radians( lp.lati ) ) ) ) as distance from assests_master am LEFT JOIN tbl_last_point lp ON lp.device_id = am.device_id  where 1  HAVING distance <= '20'
		
		$SQL = "SELECT am.assets_name, ((3959 * acos( cos( radians($lat) ) * cos( radians( lati ) ) * cos( radians( longi ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lati ) ) ) ) * 1.6) as distance from assests_master am LEFT JOIN tbl_last_point lp ON lp.device_id = am.device_id where 1 $sub";
		$SQL .= " HAVING distance <= '$distance'";
		
		if($whereSearch != "")
		{
			$SQL .= " $whereSearch ";
		}
		
		if($where != "")
			$SQL .= " AND $where";
		$result = $this->db->query($SQL);
		$data_arr=$result->result_array();
		
		$count = count($data_arr);
		
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);    
		} else {
			$total_pages = 0;
		}

		if ($page > $total_pages) 
			$page=$total_pages;
			
		
		$SQL = "SELECT am.id, am.assets_name, (( 3959 * acos( cos( radians($lat) ) * cos( radians( lp.lati ) ) * cos( radians( lp.longi ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lp.lati ) ) ) ) * 1.6) as distance from assests_master am LEFT JOIN tbl_last_point lp ON lp.device_id = am.device_id  where 1 $sub";
		
		$SQL .= " HAVING distance <= '$distance'";
		
		
		if($whereSearch != "")
		{
			$SQL .= " $whereSearch ";
		}
		
		if($where != "")
			$SQL .= " AND $where";
		
		$SQL .= " ORDER BY $sidx $sord";

		$export_sql=$SQL;

		$SQL .= " LIMIT $start, $limit";
		
		if($cmd=="export") 
		{  
			$result = $this->db->query($export_sql);
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=landmark_distance.xls"); 
			$EXCEL = "";
			$fitr="";

			//session date & time format
			$date_format = $this->session->userdata('date_format');  
			$time_format = $this->session->userdata('time_format'); 
			
			$fitr .="<tr>"; 
			$fitr.="<th>Assests Name</th>";
			$fitr.="<th>Distance</th>";
			$fitr .="</tr>"; 
			foreach($result->result_array() as $data)
			{
				
				$EXCEL .="<tr align='center'>";
				$EXCEL.="<td>".$data['assets_name']."</td>"; 
				$EXCEL.="<td>".number_format($data['distance'],2)."</td>";
				$EXCEL .="</tr>"; 
			} 
			echo "<table border='1'>";
			echo "<tr><th colspan='2'>Distance From Landmark - $landmark_name</th></tr>";
			echo $fitr;
			echo $EXCEL;
			die(); 
		} 
		
		$query = $this->db->query($SQL);  
		
		$data = array();
		$data['query'] = $SQL;
		$data['result'] = $query->result();
		$data['page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['count'] = $count;
		return $data;
		
	}
	public function get_landmark() 
	{
		$user = $this->session->userdata('user_id');
		$options ="";
		$query = $this->db->query("select id, name from landmark where del_date is null and status=1 and add_uid = $user");
		foreach($query->result() as $row)
		{
			$options .="<option value='".$row->id."'>".$row->name ."</option>";
		}
		return $options;
	}
}