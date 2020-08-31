<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Provincias_model extends CI_Model {

	var $table = 'provincias';


	public function get_all()
	{
		$consulta = $this->db->get($this->table);
		return $consulta->result();
	}

	public function get_all_array()
	{			
		//$this->db->where("habilitado",1);
		$consulta = $this->db->get($this->table);
		return $consulta->result_array();
	}


}
