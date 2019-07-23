<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_groups_model extends CI_Model {


	public function saveAll($data)
	{
		return $this->db->insert_batch('users_groups', $data);
	}

}
