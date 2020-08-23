<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cli_servicios_model extends CI_Model {

	public function guardarCambios($clienteid,$fecha){
		$this->id_cliente = $clienteid;
		$this->fecha = $fecha;
		$this->db->insert('cli_servicios', $this);		
		return $this->db->insert_id();		
	}

	public function guardar($clienteid,$fecha,$cantidad,$servicio,$detalle,$precio){

		$this->db->trans_begin();

		$this->id_cliente = $clienteid;
		$this->fecha = $fecha;
		$this->db->insert('cli_servicios', $this);
		$id = $this->db->insert_id();
		
		
		for ($i=0; $i < count($servicio); $i++) 
		{   			
			$data[$i]['id_cli_servicios'] = $id;
			$data[$i]['id_servicio'] = $servicio[$i];			
			$data[$i]['precio'] = $precio[$i];
			$data[$i]['cantidad'] = $cantidad[$i];
			$data[$i]['descripcion'] = $detalle[$i];
		}

		$resultado =  $this->db->insert_batch('cli_servicios_detalle', $data);	
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();		
		}

		return $resultado;

	}

	public function buscar($clienteId,$buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		$this->db->where("id_cliente",$clienteId);
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
			$this->db->limit($cantidadregistro,$inicio);
		}
		$consulta = $this->db->get("cli_servicios");
		return $consulta->result();
	}

	public function buscarXcliente($clienteId,$buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		$this->db->where("id_cliente",$clienteId);
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
		 	$this->db->limit($cantidadregistro,$inicio);
		 }
		$this->db->order_by("fecha", "desc");
		$this->db->order_by("Id", "desc");
		$consulta = $this->db->get("cli_servicios");
		 
		return $consulta->result();
	}

}
