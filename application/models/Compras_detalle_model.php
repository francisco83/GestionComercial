<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class compras_detalle_model extends CI_Model {

	var $table = 'compras_detalle';


	public function guardarCambios($data){
		return $this->db->insert_batch('compras_detalle', $data);	
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
		$this->db->from('compras as e');
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


	public function buscarDetalleXproveedor($compraId,$buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		$this->db->select('compras_detalle.id,compras_detalle.productoId,productos.nombre,compras_detalle.precio,compras_detalle.cantidad');
		$this->db->from('compras_detalle');
		$this->db->join('compras','compras.id=compras_detalle.compraId');
		$this->db->join('productos','productos.id=compras_detalle.productoId');
		$this->db->where("compras_detalle.compraId",$compraId);
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
		 	$this->db->limit($cantidadregistro,$inicio);
		 }

		$consulta=$this->db->get();
		return $consulta->result_array();
	}

	public function buscarDetalleImprimir($compraId)
	{		
		$this->db->select('compras.id as codigo_compra,compras.fecha as fecha_compra,compras.total, proveedores.nombre as nombre_proveedor,compras_detalle.cantidad,compras_detalle.precio,productos.nombre as nombre_producto, productos.descripcion as producto_descripcion');
		$this->db->from('compras_detalle');
		$this->db->join('compras','compras.id=compras_detalle.compraId');
		$this->db->join('productos','productos.id=compras_detalle.productoId');
		$this->db->join('proveedores','proveedores.id=compras.proveedorId');
		$this->db->where("compras_detalle.compraId",$compraId);

		$consulta=$this->db->get();
		return $consulta->result();
	
	}
		

		public function buscarDetallePagoImprimir($compraId)
		{		
			$this->db->select('pagos.compraId as codigo_compra,pagos.fecha_pago,pagos.monto,tipos_monedas.nombre,pagos.vuelto');
			$this->db->from('pagos');
			$this->db->join('tipos_monedas','pagos.tipo_monedaid=tipos_monedas.Id');
			$this->db->where("pagos.compraId",$compraId);
	
			$consulta=$this->db->get();
			return $consulta->result();
		}


}
