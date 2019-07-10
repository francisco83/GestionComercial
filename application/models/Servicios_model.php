<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Servicios_model extends CI_Model {

	var $tabla = 'servicios';

	public function buscar($buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		$this->db->like("nombre",$buscar);
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
			$this->db->limit($cantidadregistro,$inicio);
		}
		$consulta = $this->db->get($this->tabla);
		return $consulta->result();
	}

	public function get_all()
	{
		$consulta = $this->db->get($this->tabla);
		return $consulta->result();
	}

	function search_autocomplete($title){
        $this->db->like('nombre', $title);
        $this->db->order_by('nombre', 'ASC');
        $this->db->limit(10);
        return $this->db->get($this->tabla)->result();
	}
	
	public function save($data)
	{
		$this->db->insert($this->tabla, $data);
		return $this->db->insert_id();
	}

	public function get_by_id($id)
	{
		return $this->db->get_where($this->tabla, array("id" => $id))->row();
	}

	public function update($where, $data)
	{
		$this->db->update($this->tabla, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
	 $this->db->delete($this->tabla, array("id" => $id));
	}


}
