<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_model extends CI_Model {

	public function buscar($buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		$this->db->like("nombres",$buscar);
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
			$this->db->limit($cantidadregistro,$inicio);
		}
		$consulta = $this->db->get("usuarios");
		return $consulta->result();
	}

	public function nuevo($nombres, $apellidos, $dni, $email, $celular){
		$this->nombres = $nombres;
		$this->apellidos = $apellidos;
		$this->dni = $dni;
		$this->email = $email;
		$this->celular = $celular;
		return $this->db->insert('usuarios', $this);
	}

	public function eliminar($id){
		return $this->db->delete("usuarios", array("id" => $id));
	}

	public function uno($id){
		return $this->db->get_where("usuarios", array("id" => $id))->row();
	}

	public function guardarCambios($id, $nombres, $apellidos, $dni, $email, $celular){
		$this->id = $id;
		$this->nombres = $nombres;
		$this->apellidos = $apellidos;
		$this->dni = $dni;
		$this->email = $email;
		$this->celular = $celular;
		return $this->db->update('usuarios', $this, array("id" => $id));
	}

}
