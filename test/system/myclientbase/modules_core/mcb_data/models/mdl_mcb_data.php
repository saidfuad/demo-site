<?php 

class Mdl_Mcb_Data extends MY_Model {

	public function get($key) {

		$this->db->select('data_value');

		$this->db->where('data_key', $key);

		$query = $this->db->get('tbl_settings');
		
		// echo $this->db->last_query();

		if ($query->row()) {

			return $query->row()->data_value;

		}

		else {

			return NULL;

		}

	}

	public function save($key, $value) {

		if (!is_null($this->get($key))) {

			$this->db->where('data_key', $key);

			$db_array = array(
				'data_value'	=>	$value
			);

			$this->db->update('tbl_settings', $db_array);

		}

		else {

			$db_array = array(
				'data_key'	=>	$key,
				'data_value'	=>	$value
			);

			$this->db->insert('tbl_settings', $db_array);

		}

	}

	public function delete($key) {

		$this->db->where('data_key', $key);

		$this->db->delete('tbl_settings');

	}

	public function set_session_data() {

		$tbl_settings = $this->db->get('tbl_settings')->result();

		foreach ($tbl_settings as $data) {

			$this->{$data->data_key} = $data->data_value;

		}

	}

}

?>