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

class Speedgraph_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function speedgraph_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->table_name = "tbl_track";
    }
	public function get_speed() 
	{
		//Select table name
		$date = $this->input->post('date');
		$stime = $this->input->post('stime');
		$etime = $this->input->post('etime');
		//$device = $this->input->post('device');
		$device=trim($this->input->post('device'),",");
		//Get Max Speed Limit
		/*$MaxQuery="select max_speed_limit from assests_master where find_in_set(id,'$device') Limit 1";
		
		$result = $this->db->query($MaxQuery);
		$data_arr=$result->result_array();
		
		$max_speed_limit = $data_arr[0]['max_speed_limit'];
		$speedLimit="";
		if($max_speed_limit!="" && $max_speed_limit!=null)
		{
			$speedLimit="'".$max_speed_limit."' as max_speed,";
		}
		*/
		$sdate = date("Y-m-d H:i:s", strtotime($date." ".$stime));
		$edate = date("Y-m-d H:i:s", strtotime($date." ".$etime));
		
		//$hours=floor((strtotime($edate)-strtotime($sdate))/3600);
	
		/*$query=$this->db->query("select distinct(CONVERT_TZ(date_format(add_date, '%Y-%m-%d %H:%i'),'+00:00','".$this->session->userdata('timezone')."')) as add_date, ".$speedLimit." max(speed) as speed from tbl_track where CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "' AND find_in_set(assets_id,'$device') group by date_format(add_date, '%Y-%m-%d %H:%i')");*/
		//$query=$this->db->query("select am.assets_name, group_concat(CONVERT_TZ(date_format(tr.add_date, '%Y-%m-%d %H:%i'),'+00:00','".$this->session->userdata('timezone')."')) as add_date, group_concat(tr.speed) as speed from tbl_track tr left join assests_master am on tr.assets_id=am.id where CONVERT_TZ(tr.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "' AND find_in_set(assets_id,'$device') group by assets_id");
		if($device==""){
			return;
			die();
		}
		$query=$this->db->query("select am.assets_name, CONVERT_TZ(date_format(tr.add_date, '%Y-%m-%d %H:%i'),'+00:00','".$this->session->userdata('timezone')."') as add_date, tr.speed as speed from tbl_track tr left join assests_master am on tr.assets_id=am.id where CONVERT_TZ(tr.add_date,'+00:00','".$this->session->userdata('timezone')."') BETWEEN '" . $sdate . "' AND '" . $edate . "' AND find_in_set(assets_id,'$device')");
	
		return $query->result_array();
	}
}
?>