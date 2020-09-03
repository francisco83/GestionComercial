
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grupos_permisos_model extends CI_Model {

	var $table = 'grupos_permisos';

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

}
