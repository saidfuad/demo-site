<?php 
class Mdl_dashboard_reports extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Mdl_dashboard_reports()
    {
        parent::Model();
		
		$this->CI =& get_instance();
		
		$this->load->database();
		
		$this->table_name  = "tbl_report_mst";
		
		$this->table_order = "tbl_report_order";
		
		$this->table_dash  = "tbl_dashboard_mst";
		
		$this->user_settings = "tbl_user_settings";
		
		$this->table_track = "tbl_track";
		
		$this->tbl_assets = "assests_master";
		
		$this->user 	   = $this->session->userdata('user_id');
		
		$this->current_time= date("Y-m-d H:i:s");
		
    }
	
	public function get_reports_order() 
	{
		
		$this->db->select('*');
		$this->db->where('add_uid', $this->user);
		$query = $this->db->get($this->table_order);
		
		
		//echo $this->db->last_query();
		//exit;
		// return $query->result();
		
		if ($query->num_rows() > 0) {
			$row = $query->result();
			return $row[0]->rpt_order;
		}
		else {
			return false;
		}
	}
	
	public function get_dashboard_reports($ord) 
	{
		$ids 		= array();
		$rpt 		= array();
		$newIds		= array();
		
		$this->db->select('*');
		
		$this->db->where('userid', $this->user);
			
		$query = $this->db->get($this->table_dash);
		if ($this->db->count_all_results() > 0) {
			foreach ($query->result() as $row) {
				array_push($ids,$row->report_ids);
			}
		}
		if(trim($ord) != "") {
			$ids = explode(",",$ord);
		}
		//print_r($ord);
		//exit; 
		foreach ($ids as $id) {	
			if($id){
				
				$this->db->select("rm.*, dm.rpt_color, dm.rpt_status");
				
				$this->db->from($this->table_name." rm");
				
				$this->db->join($this->table_dash. " dm", "dm.report_ids = rm.id", 'LEFT');
				
				$this->db->where("rm.id", $id);
				
				$this->db->where('dm.userid', $this->user);
				
				$query = $this->db->get();
				//die($this->db->last_query());
//				$query = $this->db->query('select rm.*, dm.rpt_color, dm.rpt_status from track.tbl_report_mst rm LEFT JOIN track.tbl_dashboard_mst dm ON (dm.report_ids=rm.id) where rm.id='.$id);
				// echo $this->db->last_query();
				array_push($rpt,$query->row());
			}
		}
		return $rpt;
	}
	
	public function get_reports() 
	{
		//Select table name
		// $table_name = "tbl_track";
		$id 	= $this->input->post('id');
		
		$device = uri_assoc('id');
		
		$query = $this->db->select()->from($this->table_track)->where('phone_imei',$device)->where('id >',$id)->order_by('id');
		
		return $query->result();
	}
	
	public function get_links() 
	{
		//Select table name
		// $table_name = "tbl_track";
		
		$this->db->select('phone_imei');
		
		$this->db->distinct();
		
		$query = $this->db->get($this->table_track);
		
		// $this->db->last_query();
		
		return $query->result();
	}
	
	public function getWidgetStatus($widget) {
		
		$this->db->select('rpt_status');
		
		$this->db->where('report_ids', $widget);
		
		$this->db->where('userid', $this->user);
		
		$query = $this->db->get($this->table_dash);
		
		return $query->row()->rpt_status;
		
	}
	
	public function setWidgetColor($widget, $color) {
		
		$data 	= array('rpt_color' => $color);
		
		$where 	= "userid = ".$this->user." AND report_ids = '$widget'"; 
		
		$str 	= $this->db->update_string($this->table_dash, $data, $where);
		
		$res	= $this->db->query($str);
		
		return $this->db->affected_rows();
	}
	
	public function setWidgetSize($widget, $size) {
		
		$data 	= array('rpt_status' => $size);
		
		$where 	= "userid = ".$this->user." AND report_ids = '$widget'"; 
		
		$str 	= $this->db->update_string($this->table_dash, $data, $where);
		
		$res	= $this->db->query($str);
		
		return $this->db->affected_rows();
	}
	
	public function setWidgetOrder($reports){
		
		$data 	= array('rpt_order' => $reports);
		
		$where 	= "add_uid = ".$this->user; 
		
		$str 	= $this->db->update_string($this->table_order, $data, $where);
		
		$res	= $this->db->query($str);
		
		return $this->db->affected_rows();
		
	}
	
	public function last_location($limit = 10){
		
		$last_loc = array("headers" => array("Date", "Device", "Speed", "Address", "Asset", "SIM"), "body" => array());
		
		$base = base_url();
		
		//CONCAT('<a href=\"".$base."index.php/live/',tr.device_id,'\">',tr.device_id,'</a>') as device_id
		
		$query = $this->db->query("SELECT DATE_FORMAT( tr.add_date,  '%d.%m.%Y %H:%i' ) AS add_date, tr.device_id, tr.speed, tr.address, (

SELECT assets_name
FROM assests_master
WHERE device_id = tr.device_id
LIMIT 1
) AS assets_name, (

SELECT sim_number
FROM assests_master
WHERE device_id = tr.device_id
LIMIT 1
) AS sim_number FROM (

SELECT dm.device_id, MAX( dm.id ) AS max_id, am.assets_name
FROM tbl_track dm
LEFT JOIN assests_master am ON am.device_id = dm.device_id
WHERE find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = ".$this->session->userdata('user_id')."))
GROUP BY dm.device_id
) AS x
INNER JOIN tbl_track AS tr ON tr.device_id = x.device_id
AND tr.id = x.max_id WHERE TIMESTAMPDIFF(HOUR, add_date, '".date("Y-m-d H:i:s")."') < 1", FALSE);

		//echo $this->db->last_query();
		//exit;
		
		if($query->num_rows() > 0) {
			
			foreach ($query->result_array() as $row)
			{
				$last_loc["body"][] = $row;
			}			
		}
		
		return array($last_loc, count($last_loc["body"]));
	}
	
	public function inactive_devices(){
		$inactive = array("headers" => array("Date", "Device", "Address", "Asset", "SIM"), "body" => array());
		
		$base = base_url();
		//CONCAT('<a href=\"".$base."index.php/live/',tr.device_id,'\">',tr.device_id,'</a>') as 
$query = $this->db->query("SELECT DATE_FORMAT( tr.add_date,  '%d.%m.%Y %H:%i' ) AS add_date, tr.device_id, tr.address, (

SELECT assets_name
FROM assests_master
WHERE device_id = tr.device_id
LIMIT 1
) AS assets_name, (

SELECT sim_number
FROM assests_master
WHERE device_id = tr.device_id
LIMIT 1
) AS sim_number FROM (

SELECT dm.device_id, MAX( dm.id ) AS max_id, am.assets_name
FROM tbl_track dm
LEFT JOIN assests_master am ON am.device_id = dm.device_id
WHERE find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id = ".$this->session->userdata('user_id')."))
GROUP BY dm.device_id
) AS x
INNER JOIN tbl_track AS tr ON tr.device_id = x.device_id
AND tr.id = x.max_id WHERE TIMESTAMPDIFF(HOUR, add_date, '".date("Y-m-d H:i:s")."') >= 1", FALSE);		
/*		
		
		$base = base_url();
		
		foreach($query->result() as $row) {
			
			$device_id = $row->device_id;
			
			$this->db->select_max('add_date');
			
			$this->db->where('device_id', $device_id);
			
			$this->db->get($this->table_track);
			
			$subQuery = $this->db->last_query();
			
			$this->db->_compile_select();
			
			$this->db->_reset_select();
			
			$this->db->select("date_format(add_date, '%d.%m.%Y %H:%i') as add_date, CONCAT('<a href=\"".$base."index.php/live/',device_id,'\">',device_id,'</a>') as device_id, lati, longi, speed, address", FALSE);
			
			$this->db->where('device_id', $device_id);
			
			$this->db->where("TIMESTAMPDIFF(HOUR, ($subQuery), '".date("Y-m-d H:i:s")."') > 1");
			
			$this->db->order_by('dt', "DESC");
			
			$this->db->limit(1);
			
			$query = $this->db->get($this->table_track);
			
			echo $this->db->last_query();
			if($query->num_rows() > 0) {
				
				$inactive["body"][] = $query->row_array();
			}
		}
		
*/
		if($query->num_rows() > 0) {
			
			foreach ($query->result_array() as $row)
			{
				$inactive["body"][] = $row;
			}			
		}
		
		return array($inactive, count($inactive["body"]));
	}
	
	function get_refresh_rate() {
			
		$this->db->select();
		
		$this->db->where('add_uid', $this->user);
		
		$query = $this->db->get($this->user_settings);
		
		// echo $this->db->last_query();
		
		return $query->row()->refresh_time;
		
	}
	
	function remove_widget($widget) {
		
		$reports = $this->get_reports_order();
		
		$orders = explode(";", $reports);
		
		$new_order = array();
		
		foreach($orders as $order) {
			$rpt = explode(",", $order);
			foreach($rpt as $key => $val) {
				if($val == $widget) {
					unset($rpt[$key]);
				}
			}
			$new_order[] = implode(",", $rpt);
		}
		
		$reports = implode(";", $new_order);
		
		$this->db->trans_begin();
		// Updating the Dashboard, and deleting the Report id
		$data = array('del_uid' => $this->user, 'del_date' => $this->current_time);
		
		$this->db->where("add_uid", $this->user);
		
		$this->db->where("report_ids", $widget);
		
		$this->db->update($this->table_dash, $data);
		
		// Updating the Reports Order and removing the report id from the report orders.
		$data = array('rpt_order' => $reports);
		
		$this->db->where("add_uid", $this->user);
		
		$this->db->update($this->table_order, $data);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			
			return false;
		}
		else
		{
			$this->db->trans_commit();
			
			return $this->db->affected_rows();
		}		
	}
}
?>