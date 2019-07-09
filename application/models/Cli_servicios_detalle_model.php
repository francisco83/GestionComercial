<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cli_servicios_detalle_model extends CI_Model {


	public function guardarCambios($data){

		return $this->db->insert_batch('cli_servicios_detalle', $data);
	}

	public function buscar($servicioId,$buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		//$this->db->like("fecha",$buscar);
		$this->db->where("id_cli_servicios",$servicioId);
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
			$this->db->limit($cantidadregistro,$inicio);
		}
		$consulta = $this->db->get("cli_servicios_detalle");
		return $consulta->result();
	}

	public function buscarDetalleXcliente($servicioId,$buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		$this->db->where("id_cli_servicios",$servicioId);
		//$this->db->where("dni",$clienteId);
		//$this->db->like("fecha",$buscar);
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
		 	$this->db->limit($cantidadregistro,$inicio);
		 }
		$consulta = $this->db->get("cli_servicios_detalle");
		return $consulta->result();
	}

}
