<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Servicios_model extends CI_Model {

	public function buscar($buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		$this->db->like("nombre",$buscar);
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
			$this->db->limit($cantidadregistro,$inicio);
		}
		$consulta = $this->db->get("servicios");
		return $consulta->result();
	}



	public function nuevo($nombre, $descripcion, $precio){
		$this->nombre = $nombre;
		$this->descripcion = $descripcion;
		$this->precio = $precio;
		return $this->db->insert('servicios', $this);
	}

	public function eliminar($id){
		return $this->db->delete("servicios", array("id" => $id));
	}

	public function uno($id){
		return $this->db->get_where("servicios", array("id" => $id))->row();
	}

	public function guardarCambios($id, $nombre, $descripcion, $precio){
		$this->id = $id;
		$this->nombre = $nombre;
		$this->descripcion = $descripcion;
		$this->precio = $precio;
		return $this->db->update('servicios', $this, array("id" => $id));
	}

	public function listar()
	{
		$consulta = $this->db->get("servicios");
		return $consulta->result();
	}

	function search_blog($title){
        $this->db->like('nombre', $title);
        $this->db->order_by('nombre', 'ASC');
        $this->db->limit(10);
        return $this->db->get('servicios')->result();
    }

}
