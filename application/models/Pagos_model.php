<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagos_model extends CI_Model {

	var $table = 'pagos';


	public function guardarCambios($data){
		return $this->db->insert_batch('pagos', $data);	
	}

	// public function buscar($buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	// {
	// 	$this->db->like("fecha",$buscar);
	// 	if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
	// 		$this->db->limit($cantidadregistro,$inicio);
	// 	}
	// 	$consulta = $this->db->get($this->table);
	// 	return $consulta->result();
	// }

	public function get_all()
	{
		$consulta = $this->db->get($this->table);
		return $consulta->result();
	}

	public function get_all_export() {
		$this->db->select(array('p.id', 'p.tipo_pagoid', 'p.monto','p.vuelto'));
		$this->db->from('pagos as p');
		$query = $this->db->get();
		return $query->result_array();
	 }
 

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function get_by_id($id)
	{
		return $this->db->get_where($this->table, array("id" => $id))->row();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
	 $this->db->delete($this->table, array("id" => $id));
	}


}
