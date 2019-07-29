<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cli_servicios_detalle_model extends CI_Model {


	public function guardarCambios($data){

		return $this->db->insert_batch('cli_servicios_detalle', $data);
	}

	public function buscar($servicioId,$buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{		
		$this->db->where("id_cli_servicios",$servicioId);
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
			$this->db->limit($cantidadregistro,$inicio);
		}
		$consulta = $this->db->get("cli_servicios_detalle");
		return $consulta->result();
	}

	public function buscarDetalleXcliente($servicioId,$buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		$this->db->select('cli_servicios_detalle.id,tipos_servicios.nombre,cli_servicios_detalle.precio,cli_servicios_detalle.cantidad,cli_servicios_detalle.descripcion');
		$this->db->from('cli_servicios_detalle');
		$this->db->join('tipos_servicios','tipos_servicios.id=cli_servicios_detalle.id_servicio');
		$this->db->where("id_cli_servicios",$servicioId);
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
		 	$this->db->limit($cantidadregistro,$inicio);
		 }

		$consulta=$this->db->get();
		return $consulta->result_array();
	}

}
