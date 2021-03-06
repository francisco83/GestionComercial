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
		$this->db->trans_begin();

		$this->db->update($this->table, $data, $where);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}

		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		 $this->db->trans_begin();
		 
	 	$this->db->delete($this->table, array("id" => $id));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
	}


	public function buscarDetalleXcliente($ventaId,$buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		$this->db->select('ventas_detalle.id,ventas_detalle.productoId,productos.nombre,ventas_detalle.precio,ventas_detalle.cantidad');
		$this->db->from('ventas_detalle');
		$this->db->join('ventas','ventas.id=ventas_detalle.ventaId');
		$this->db->join('productos','productos.id=ventas_detalle.productoId');
		$this->db->where("ventas_detalle.ventaId",$ventaId);
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
		 	$this->db->limit($cantidadregistro,$inicio);
		 }

		$consulta=$this->db->get();
		return $consulta->result_array();
	}

	public function buscarDetalleImprimir($ventaId)
	{		
		$this->db->select('ventas.id as codigo_venta,ventas.fecha as fecha_venta,ventas.total,clientes.apellido as apellido_cliente, clientes.nombre as nombre_cliente,ventas_detalle.cantidad,ventas_detalle.precio,productos.nombre as nombre_producto, productos.descripcion as producto_descripcion');
		$this->db->from('ventas_detalle');
		$this->db->join('ventas','ventas.id=ventas_detalle.ventaId');
		$this->db->join('productos','productos.id=ventas_detalle.productoId');
		$this->db->join('clientes','clientes.id=ventas.clienteId');
		$this->db->where("ventas_detalle.ventaId",$ventaId);

		$consulta=$this->db->get();
		return $consulta->result();
	
	}
		

		public function buscarDetallePagoImprimir($ventaId)
		{		
			$this->db->select('pagos.ventaId as codigo_venta,pagos.fecha_pago,pagos.monto,tipos_monedas.nombre,pagos.vuelto');
			$this->db->from('pagos');
			$this->db->join('tipos_monedas','pagos.tipo_monedaid=tipos_monedas.Id');
			$this->db->where("pagos.ventaId",$ventaId);
	
			$consulta=$this->db->get();
			return $consulta->result();
		}


}
