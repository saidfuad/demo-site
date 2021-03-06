<?php 

class Mdl_Sessions extends MY_Model {

	public function validate() {

		$this->load->library('form_validation');

		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		$this->form_validation->set_rules('username', $this->lang->line('username'), 'required');

		$this->form_validation->set_rules('password', $this->lang->line('password'), 'required|md5');

		return parent::validate();

	}
	
	public function getOS() {  
		global $user_agent;
		$user_agent     =   $_SERVER['HTTP_USER_AGENT'];
		
		$os_platform = "Unknown - ".$user_agent;

		$os_array       =   array(
								'/windows nt 6.2/i'     =>  'Windows 8',
								'/windows nt 6.1/i'     =>  'Windows 7',
								'/windows nt 6.0/i'     =>  'Windows Vista',
								'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
								'/windows nt 5.1/i'     =>  'Windows XP',
								'/windows xp/i'         =>  'Windows XP',
								'/windows nt 5.0/i'     =>  'Windows 2000',
								'/windows me/i'         =>  'Windows ME',
								'/win98/i'              =>  'Windows 98',
								'/win95/i'              =>  'Windows 95',
								'/win16/i'              =>  'Windows 3.11',
								'/macintosh|mac os x/i' =>  'Mac OS X',
								'/mac_powerpc/i'        =>  'Mac OS 9',
								'/linux/i'              =>  'Linux',
								'/ubuntu/i'             =>  'Ubuntu',
								'/iphone/i'             =>  'iPhone',
								'/ipod/i'               =>  'iPod',
								'/ipad/i'               =>  'iPad',
								'/android/i'            =>  'Android',
								'/blackberry/i'         =>  'BlackBerry',
								'/webos/i'              =>  'Mobile'
							);

		foreach ($os_array as $regex => $value) { 

			if (preg_match($regex, $user_agent)) {
				$os_platform    =   $value;
			}

		}   
		
		return $os_platform;

	} 

 function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

	
	//device detection
	public function browser_detect(){
		global $user_agent;
		$user_agent     =   $_SERVER['HTTP_USER_AGENT'];
		
		$os_platform = "Unknown - ".$user_agent;

		$os_array       =   array(
								'/windows nt 6.2/i'     =>  'Desktop',
								'/windows nt 6.1/i'     =>  'Desktop',
								'/windows nt 6.0/i'     =>  'Desktop',
								'/windows nt 5.2/i'     =>  'Desktop',
								'/windows nt 5.1/i'     =>  'Desktop',
								'/windows xp/i'         =>  'Desktop',
								'/windows nt 5.0/i'     =>  'Desktop',
								'/windows me/i'         =>  'Desktop',
								'/win98/i'              =>  'Desktop',
								'/win95/i'              =>  'Desktop',
								'/win16/i'              =>  'Desktop',
								'/macintosh|mac os x/i' =>  'Desktop',
								'/mac_powerpc/i'        =>  'Desktop',
								'/linux/i'              =>  'Desktop',
								'/ubuntu/i'             =>  'Desktop',
								'/iphone/i'             =>  'iPhone',
								'/ipod/i'               =>  'iPod',
								'/ipad/i'               =>  'iPad',
								'/android/i'            =>  'Android Mobile',
								'/blackberry/i'         =>  'BlackBerry Mobile',
								'/webos/i'              =>  'Mobile'
							);

		foreach ($os_array as $regex => $value) { 

			if (preg_match($regex, $user_agent)) {
				$os_platform    =   $value;
			}

		}   
		return $os_platform;
		} // ends function browser_detect
		
}

?>