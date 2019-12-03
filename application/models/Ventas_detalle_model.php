<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas_detalle_model extends CI_Model {

	var $table = 'ventas_detalle';


	public function guardarCambios($data){
		return $this->db->insert_batch('ventas_detalle', $data);	
	}

	public function buscar($buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		$this->db->like("fecha",$buscar);
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
			$this->db->limit($cantidadregistro,$inicio);
		}
		$consulta = $this->db->get($this->table);
		return $consulta->result();
	}

	public function get_all()
	{
		$consulta = $this->db->get($this->table);
		return $consulta->result();
	}

	public function get_all_export() {
		$this->db->select(array('e.id', 'e.fecha', 'e.total'));
		$this->db->from('ventas as e');
		$query = $this->db->get();
		return $query->result_array();
	 }
 

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function get_by_id($id)
	{
		return $this->db->get_where($this->table, array("id" => $id))->row();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
	 $this->db->delete($this->table, array("id" => $id));
	}

	public function buscarDetalleXcliente($ventaId,$buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		$this->db->select('ventas_detalle.id,ventas_detalle.productoId,ventas_detalle.precio,ventas_detalle.cantidad');
		$this->db->from('ventas_detalle');
		$this->db->join('ventas','ventas.id=ventas_detalle.ventaId');
		$this->db->where("ventas_detalle.ventaId",$ventaId);
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
		 	$this->db->limit($cantidadregistro,$inicio);
		 }

		$consulta=$this->db->get();
		return $consulta->result_array();
	}


}
