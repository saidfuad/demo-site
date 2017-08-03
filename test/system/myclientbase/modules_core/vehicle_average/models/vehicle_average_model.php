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

class Vehicle_average_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function vehicle_average_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "fuel_report";
    }
	
	public function get_data($cmd) 
	{
		$user = $this->session->userdata('user_id');
		$sdate = $this->input->get('sdate');
		$edate = $this->input->get('edate');
		$device = $this->input->get('device');
		
		if($sdate && $edate){	//search by date
			$sdate = date("Y-m-d", strtotime($sdate));
			$edate = date("Y-m-d", strtotime($edate));
		}else{
			$sdate = date("Y-m-d");
			$edate = date("Y-m-d");
		}
		
		if($device == ""){
			$vSQL = "SELECT id from assests_master where FIND_IN_SET('FUEL', sensor_type ) and add_uid = '$user' limit 1";
			$query = $this->db->query($vSQL);
			$row = $query->row();
			$device = $row->id;
		}
		$startTime = strtotime($sdate);
		$endTime = strtotime($edate);
		$resultData = array();
		for ($i = $startTime; $i <= $endTime; $i = $i + 86400) {
			$thisDate = date('Y-m-d', $i); // 2010-05-01, 2010-05-02, etc
			/*			
			$SQL = "(SELECT max(f1.fuel_liters) as fuel_liters FROM `fuel_log` f1 where assets_id = '$device' AND date(CONVERT_TZ(f1.add_date,'+00:00','".$this->session->userdata('timezone')."')) = '$thisDate') union (SELECT f2.fuel_liters FROM `fuel_log` f2 where assets_id = '$device' AND date(CONVERT_TZ(f2.add_date,'+00:00','".$this->session->userdata('timezone')."')) = '$thisDate' order by f2.id desc limit 1)";
			$query = $this->db->query($SQL);
			$rows = $query->result();			
			
			$max = intval($rows[0]->fuel_liters);
			$last = intval($rows[1]->fuel_liters);
			$fuel_used = $max - $last;
			*/
			
			$SQL = "SELECT sum(f1.fuel_litters) as fuel_liters FROM `fuel_report_new` f1 where assets_id = '$device' AND date(CONVERT_TZ(f1.add_date,'+00:00','".$this->session->userdata('timezone')."')) = '$thisDate' AND f1.fuel_litters > 0 and f1.fuel_litters < 100";
			$query = $this->db->query($SQL);
			$rows = $query->result();			
			
			$fuel_used = $rows[0]->fuel_liters;
			/*$fuel_used_3 = number_format($fuel_used / 3, 2);
			$fuel_used = 2 * $fuel_used_3;
			*/
			$SQL = "SELECT dm.distance, am.assets_name FROM `distance_master` dm left join assests_master am on am.id = dm.assets_id where date(CONVERT_TZ(dm.add_date,'+00:00','".$this->session->userdata('timezone')."')) = '$thisDate' AND assets_id = '$device'";
			$query = $this->db->query($SQL);
			$row = $query->row();
			$distance = $row->distance;
			$assets_name = $row->assets_name;
			
			$resultData[] = array('date_time'=>$thisDate, 'assets_name'=>$assets_name, 'km_run'=>$distance, 'fuel_litters'=>$fuel_used);
		}
	
		$data = array();
		$data['result'] = $resultData;
		$data['page'] = 1;
		$data['total_pages'] = 1;
		$data['count'] = 1;
		return $data;
	}
}
?>