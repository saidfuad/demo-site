<?php 
class Device_Settings_model extends Model 
{
	/**
	* Instanciar o CI
	*/
	public function device_settings_model()
    {
        parent::Model();
		$this->CI =& get_instance();
		$this->load->database();
		$this->load->library('session');
		$this->am = "assests_master";
    }
	
	public function fetch_devices($class){
		
		$this->db->select('id, device_id, assets_name');
		$this->db->where("assets_class = '".$class."'");
		$this->db->where('status',1);
		$this->db->where('del_date',null);
		
		$this->order_by = 'id';
		$query = $this->db->get($this->am);
		return $query->result();
		
	}
	
	public function fetch_device_class(){
		
		$sql = "SELECT id, assets_class_name from assests_class_master WHERE del_date is null AND status=1";
		$res = $this->db->query($sql);
		
		return $res->result();
		
	}
	
	public function fetch_device_commands($class){
		
		$sql = "SELECT id, command, comments from assests_command_master WHERE assets_class_id = '$class' AND del_date is null AND status=1";
		$res = $this->db->query($sql);
		
		return $res->result();
		
	}

	public function save($db_array){
		$success = TRUE;
		$this->load->library('zenvia/HumanClientMain');
		
		$account = "rastrearnanet.api";
		$password= "wjgEFUiW9i";
		$callbackOption=  HumanSimpleSend::CALLBACK_INACTIVE;
	
		$sender = new HumanSimpleSend($account, $password);
		
		$devices = $db_array['device_ids'];
		$command = $db_array['command'];
		$data = array();
		$string = '';
		$ids = implode(',', $devices);
		
		if(count($devices) > 0) {
			
			$sql = "SELECT sim_number FROM assests_master WHERE del_date is null AND status=1 AND id IN ($ids)";
			$res = $this->db->query($sql);
			$rows = $res->result();
			$sms = array();
			foreach ($rows as $row) {
				if($row->sim_number != '' && $command != '') {
					
					$message = new HumanSimpleMessage();
					$message->setBody($body);
					$message->setTo($to);
					$message->setMsgId($msgId);
					$response = $sender->sendMessage($message, $callbackOption);
					
					$sqlU = "INSERT INTO smslog (user_id, mobile, sms_text, add_date) VALUES ('".$this->session->userdata('user_id')."', '".$row->sim_number."', '".mysql_real_escape_string($command . ", Resp : " . $response->getMessage())."', '".gmdate('Y-m-d H:i:s')."')";
					$this->db->query($sqlU);
				}

			}
		}
		
		$reply = "Command Posted to Device(s)";
		
		return $reply;
	}
} 
?>