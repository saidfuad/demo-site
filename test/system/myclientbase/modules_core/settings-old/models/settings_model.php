<?php 
class Settings_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function Settings_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
	}
}
?>