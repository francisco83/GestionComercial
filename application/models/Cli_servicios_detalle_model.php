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

	public function buscarDetalleImprimir($servicioId)
	{		
		$this->db->select('cli_servicios.id as codigo_servicio,cli_servicios.fecha as fecha_servicio,cli_servicios_detalle.precio,cli_servicios_detalle.cantidad,cli_servicios_detalle.descripcion,tipos_servicios.nombre,clientes.apellido,clientes.nombre as nombrecliente');
		$this->db->from('cli_servicios_detalle');
		$this->db->join('tipos_servicios','tipos_servicios.id=cli_servicios_detalle.id_servicio');
		$this->db->join('cli_servicios','cli_servicios.id=cli_servicios_detalle.id_cli_servicios');
		$this->db->join('clientes','clientes.id=cli_servicios.id_cliente');
		$this->db->where("id_cli_servicios",$servicioId);

		$consulta=$this->db->get();
		return $consulta->result();
	}

}
