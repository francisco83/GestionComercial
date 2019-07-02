<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cli_servicios_model extends CI_Model {


	public function guardarCambios($clienteid,$fecha){
		$this->id_cliente = $clienteid;
		$this->fecha = $fecha;
		$this->db->insert('cli_servicios', $this);
		
		return $this->db->insert_id();
		
	}

	public function buscar($buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		$this->db->like("fecha",$buscar);
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
			$this->db->limit($cantidadregistro,$inicio);
		}
		$consulta = $this->db->get("cli_servicios");
		return $consulta->result();
	}


}
