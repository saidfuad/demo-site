<?php 
class Profile_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Profile_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
    }
	public function get_country($country)
	{
		$options="<option value=''>Please Select</option>";
		$query = $this->db->get("mst_country");
		
		foreach ($query->result() as $row)
		{
			$options .= "<option value='".$row->id."' ";
			if($country==$row->id)
				$options .= "selected='selected'";
			$options .= " >".$row->name."</options>";
		}
		return $options;
	}
	public function get_dashboard_view($dashboard_view)
	{
		$options = "<option value='dashboard' ";
		if($dashboard_view=="dashboard")
			$options .= "selected='selected'>Default Dashboard</option>";
		else
			$options .= ">Default Dashboard</option>";
		$options .= "<option value='tree_view' ";
		if($dashboard_view=="tree_view")
			$options .= "selected='selected'>Tree View</option>";
		else
			$options .= ">Tree View</option>";
			
		
		return $options;
	}
	public function get_state($country, $state=-1)
	{
		$this->db->where("id = $country");
		$query = $this->db->get("mst_country");
		
		foreach ($query->result() as $row)
		{
			$country_name = $row->name;
		}
		$lat = '';
		$lng = '';
		if($country_name != "")
		{
			$geocode_stats = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=".$country_name."&sensor=false");
			
			$output_deals = json_decode($geocode_stats);

			$latLng = $output_deals->results[0]->geometry->location;

			$lat = $latLng->lat;
			$lng = $latLng->lng;
		} else {
			$lat = '21.0000';
			$lng = '78.0000';
		}	
		
		$options="<option value=''>Please Select</option>";
		if($country!=""){
			$this->db->where("FK_mst_country_p_id = $country");
			$query = $this->db->get("mst_state");
			foreach ($query->result() as $row)
			{
				$options .= "<option value='".$row->id."' ";
				if($state!=-1 and $state==$row->id)
					$options .= "selected='selected'";
				$options .= " >".$row->name."</option>";
			}
		}
		return $options.'@#$%'.$lat.'@#$%'.$lng;
	}
	public function get_city($state,$city=-1)
	{
		$options="<option value=''>Please Select</option>";
		if($state!=""){
			$query = $this->db->query("select * from mst_city where FK_mst_state_p_id = '$state' ORDER BY name ASC");
			foreach ($query->result() as $row)
			{
				$options .= "<option value='".$row->id."' ";
				if($city!=-1 and $city==$row->id)
					$options .= "selected='selected'";
				$options .= " >".$row->name."</option>";
			}
		}
		return $options;
	}
	public function get_date_format($date_format)
	{
		$options="";
		$query = $this->db->get("date_formats");
		foreach ($query->result() as $row)
		{
			$options .= "<option value='".$row->format."' ";
			if($date_format==$row->format)
				$options .= "selected='selected'";
			$options .= " >".date($row->format)."</options>";
		}
		return $options;
	}
	public function get_currency_format($currency_format)
	{
		$options="";
		$query = $this->db->get("currency_formats");
		foreach ($query->result() as $row)
		{
			$options .= "<option value='".$row->currency_format."' ";
			if($currency_format==$row->currency_format)
				$options .= "selected='selected'";
			$options .= " >".$row->currency_example."</options>";
		}
		return $options;
	}
	public function get_language($language)
	{
		$options="";
		$query = $this->db->get("language_master");
		foreach ($query->result() as $row)
		{
			$options .= "<option value='".$row->language_name."' ";
			if($language==$row->language_name)
				$options .= "selected='selected'";
			$options .= " >".ucwords($row->language_name)."</options>";
		}
		return $options;
	}
	public function get_time_format($time_format)
	{
		$options="";
		$query = $this->db->get("time_formats");
		foreach ($query->result() as $row)
		{
			$options .= "<option value='".$row->format."' ";
			if($time_format==$row->format)
				$options .= "selected='selected'";
			$options .= " >".date($row->format)."</options>";
			//."&nbsp;&nbsp;&nbsp;(&nbsp;".$row->format."&nbsp;)"
		}
		return $options;
	}
	public function get_time_zone($zone)
	{
		$options="";
		$query = $this->db->get("timezone");
		foreach ($query->result() as $row)
		{
			$options .= "<option value='".$row->diff_from_gmt."' ";
			if($zone==$row->diff_from_gmt)
				$options .= "selected='selected'";
			$options .= " >".$row->time_zone_string."</options>";
		}
		return $options;
	}
	public function get_timespan($sel_seconds)
	{
		$options="";
		for($i=1; $i<=100; $i++) {
			$seconds = ($i*3600);
			$options .= "<option value='".$seconds."'";
			if($seconds == $sel_seconds)
				$options .= " selected='selected' ";
			$options .= ">".$i."</options>";
		}
		return $options;
	}
	public function get_State_name($state)
	{
		$this->db->where("id = $state");
		$query = $this->db->get("mst_state");
		$row = $query->result();
		return "<option value='".$row[0]->id."'>".$row[0]->name."</option>";
	}
	public function get_city_name($city)
	{
		$this->db->where("id = $city");
		$query = $this->db->get("mst_city");
		$row = $query->result();
		return "<option value='".$row[0]->id."'>".$row[0]->name."</option>";
	}
	public function get_alll_data()
	{
		$user = $this->session->userdata('user_id');
		$this->db->where("user_id = $user");
		$query = $this->db->get("tbl_users");
		$row = $query->result();
		return $row[0];
	}
	public function update_settings($data){	
		foreach($data as $key=>$val){
			$tblSetting="UPDATE `tbl_settings` SET `data_value`='$val' WHERE data_key='$key'";
			$this->db->query($tblSetting) or die("error");
		}
	}
	public function getSmsBalance($usr)
	{
		$SQL = "SELECT sms_balance FROM tbl_users where user_id = '$usr'";
		$query = $this->db->query($SQL);
		$row = $query->row();
		if(count($row))
			return $row->sms_balance;
		else
			return 0;
	}
	
}
?>