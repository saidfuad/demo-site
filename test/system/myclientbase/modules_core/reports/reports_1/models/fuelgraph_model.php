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

class Fuelgraph_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function fuelgraph_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "tbl_track";
    }
	public function get_fuel() 
	{
		//Select table name
		$stime = $this->input->post('stime');
		$etime = $this->input->post('etime');
		$device = $this->input->post('device');
				
		//Get Max fuel Limit
		$MaxQuery="select max_fuel_liters from assests_master where id=".$device." Limit 1";
			
		$result = $this->db->query($MaxQuery);
		$data_arr=$result->result_array();
		
		$max_fuel_limit = $data_arr[0]['max_fuel_liters'];
		$fuelLimit="";
		if($max_fuel_limit!="" && $max_fuel_limit!=null)
		{
			$fuelLimit="'".$max_fuel_limit."' as max_fuel,";
		}
		$sdate = date("Y-m-d H:i:s", strtotime($stime));
		$edate = date("Y-m-d H:i:s", strtotime($etime));
		
		$hours=floor((strtotime($edate)-strtotime($sdate))/3600); 
	
		$query=$this->db->query("select date_format(CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."'), '%Y-%m-%d %H:%i') as add_date, ".$max_fuel_limit." as max_fuel_limit, fuel_liters from fuel_log where CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "' AND assets_id=".$device);
		
		return $query->result();				
	}
	public function get_devices($user) 
	{
		$this->db->where('status',1);
		$this->db->where('del_date',null);
		$query = $this->db->query("select id, assets_name, device_id, assets_friendly_nm from assests_master where status=1 AND del_date is null and find_in_set('FUEL', sensor_type) AND find_in_set(id, (SELECT assets_ids FROM user_assets_map where user_id = $user))");

		return $query->result();
	}
}
?>