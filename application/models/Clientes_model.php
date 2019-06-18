<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes_model extends CI_Model {

	public function buscar($buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		$this->db->like("nombres",$buscar);
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
			$this->db->limit($cantidadregistro,$inicio);
		}
		$consulta = $this->db->get("clientes");
		return $consulta->result();
	}

	public function nuevo($codigo,$nombres, $apellidos, $dni, $email, $celular){
		$this->codigo = $codigo;
		$this->nombres = $nombres;
		$this->apellidos = $apellidos;
		$this->dni = $dni;
		$this->email = $email;
		$this->celular = $celular;
		return $this->db->insert('clientes', $this);
	}

	public function eliminar($id){
		return $this->db->delete("clientes", array("id" => $id));
	}

	public function uno($id){
		return $this->db->get_where("clientes", array("id" => $id))->row();
	}

	public function guardarCambios($id, $codigo, $nombres, $apellidos, $dni, $email, $celular){
		$this->id = $id;
		$this->codigo = $codigo;
		$this->nombres = $nombres;
		$this->apellidos = $apellidos;
		$this->dni = $dni;
		$this->email = $email;
		$this->celular = $celular;
		return $this->db->update('clientes', $this, array("id" => $id));
	}

}
