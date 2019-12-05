<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas_model extends CI_Model {

	//var $table = 'ventas';


	public function guardarCambios($fecha,$total,$vuelto,$clienteId,$empleadoId,$sucursalId){
		$this->fecha = $fecha;
		$this->total= $total;
		$this->vuelto= $vuelto;
		$this->clienteId = $clienteId;
		$this->empleadoId =$empleadoId;
		$this->sucursalId =$sucursalId;

		$this->db->insert('ventas', $this);		
		//$this->db->insert_batch($this->table, $this);
		return $this->db->insert_id();		
	}

	public function buscar($buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		$this->db->like("fecha",$buscar);
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
			$this->db->limit($cantidadregistro,$inicio);
		}
		$consulta = $this->db->get('ventas');
		return $consulta->result();
	}

	public function get_all()
	{
		$consulta = $this->db->get('ventas');
		return $consulta->result();
	}

	public function get_all_export() {
		$this->db->select(array('e.id', 'e.fecha', 'e.total'));
		$this->db->from('ventas as e');
		$query = $this->db->get();
		return $query->result_array();
	 }
 
/*
	function search_autocomplete($title){
        $this->db->like('first_name', $title);
        $this->db->order_by('first_name', 'ASC');
        $this->db->limit(10);
        return $this->db->get($this->table)->result();
	}
*/

	public function save($data)
	{
		$this->db->insert('ventas', $data);
		return $this->db->insert_id();
	}

	public function get_by_id($id)
	{
		return $this->db->get_where('ventas', array("id" => $id))->row();
	}

	public function update($where, $data)
	{
		$this->db->update('ventas', $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
	 $this->db->delete('ventas', array("id" => $id));
	}

	public function buscarXcliente($clienteId,$buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		$this->db->where("clienteId",$clienteId);
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
		 	$this->db->limit($cantidadregistro,$inicio);
		 }
		$this->db->order_by("fecha", "desc");
		$this->db->order_by("Id", "desc");
		$consulta = $this->db->get("ventas");
		 
		return $consulta->result();
	}

	// public function enabled_by_id($id)
	// {
	// 	$reg = $this->db->get_where($this->table, array("id" => $id))->row();

	// 	if($reg->active == "1"){
	// 		$this->active = 0;
	// 	}
	// 	else{
	// 		$this->active = 1;
	// 	}
	// 	$data = array(
	// 		'active' => $this->active
	// 	);

	// 	$this->db->update($this->table, $data, array("id" => $id));

	// }

	//buscarDetalleImprimir



}
