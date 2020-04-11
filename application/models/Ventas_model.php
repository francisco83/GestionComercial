<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas_model extends CI_Model {

	//var $table = 'ventas';


	public function guardarCambios($fecha,$total,$clienteId,$empleadoId,$sucursalId){
		$this->fecha = $fecha;
		$this->total= $total;
		//$this->vuelto= $vuelto;
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

	// public function detalleCtaCteVentaxCliente($clienteId)
	// {	
	// 	$this->db->select('ventas.id as codigo_venta,ventas.fecha as fecha_venta,ventas.total,ventas.vuelto,pagos.monto,clientes.Id as clienteId,clientes.nombre,clientes.apellido');
	// 	$this->db->from('ventas');
	// 	$this->db->join('pagos','ventas.id=pagos.ventaId');
	// 	$this->db->join('clientes','clientes.id=ventas.clienteId');
	// 	$this->db->where("ventas.clienteId",$clienteId);

	// 	$consulta=$this->db->get();
	// 	return $consulta->result();
	// }


	public function detalleCtaCteVentaxCliente($clienteId)
	{	
		$this->db->select('ventas.id as codigo_venta,ventas.fecha as fecha_venta,ventas.total as total,IFNULL(sum(pagos.vuelto),0) as vuelto,IFNULL(sum(pagos.monto),0) as monto,clientes.Id as clienteId,clientes.nombre,clientes.apellido');
		$this->db->from('ventas');
		$this->db->join('pagos','ventas.id=pagos.ventaId','left');
		$this->db->join('clientes','clientes.id=ventas.clienteId');
		$this->db->where("ventas.clienteId",$clienteId);
		$this->db->group_by(array("ventas.id", "ventas.fecha")); 
		$this->db->order_by("ventas.Id,ventas.fecha","desc");

		$consulta=$this->db->get();
		return $consulta->result();
	}

	public function ventasXFechas($fecha_desde,$fecha_hasta)
	{
		$this->db->select(array('e.id', 'e.fecha', 'e.total','clientes.Id as clienteId','clientes.nombre','clientes.apellido','IFNULL(sum(pagos.monto),0) as monto','IFNULL(sum(pagos.vuelto),0) as vuelto'));
		$this->db->from('ventas as e');
		$this->db->join('pagos','e.id=pagos.ventaId','left');
		$this->db->join('clientes','clientes.id=e.clienteId');
		$this->db->where("e.fecha >=",$fecha_desde);
		$this->db->where("e.fecha <=",$fecha_hasta);
		$this->db->group_by(array("e.id", "e.fecha")); 
		$query = $this->db->get();
		return $query->result_array();

	}

	public function ventasXFechasResult($fecha_desde,$fecha_hasta)
	{
		$this->db->select(array('e.id', 'e.fecha', 'e.total','clientes.Id as clienteId','clientes.nombre','clientes.apellido','IFNULL(sum(pagos.monto),0) as monto','IFNULL(sum(pagos.vuelto),0) as vuelto'));
		$this->db->from('ventas as e');
		$this->db->join('pagos','e.id=pagos.ventaId','left');
		$this->db->join('clientes','clientes.id=e.clienteId');
		$this->db->where("e.fecha >=",$fecha_desde);
		$this->db->where("e.fecha <=",$fecha_hasta);
		$this->db->group_by(array("e.id", "e.fecha")); 
		$query = $this->db->get();
		return $query->result();

	}

	public function get_all_export_by_date($fecha_desde,$fecha_hasta) {
		$this->db->select(array('e.id', 'e.fecha', 'e.total','clientes.Id as clienteId','clientes.nombre','clientes.apellido','IFNULL(sum(pagos.monto),0) as monto','IFNULL(sum(pagos.vuelto),0) as vuelto'));
		$this->db->from('ventas as e');
		$this->db->join('pagos','e.id=pagos.ventaId','left');
		$this->db->join('clientes','clientes.id=e.clienteId');
		$this->db->where("e.fecha >=",$fecha_desde);
		$this->db->where("e.fecha <=",$fecha_hasta);
		$this->db->group_by(array("e.id", "e.fecha")); 
		$query = $this->db->get();
		return $query->result_array();
	 }


	public function ventasProductosXFechas($fecha_desde,$fecha_hasta)
	{
		$this->db->select(array('v.fecha','max(p.codigo) as codigoproducto','p.nombre','sum(vd.cantidad) as cantidad','(precioVenta)*sum(vd.cantidad)  as precioventa','(precioCompra)*sum(vd.cantidad) as preciocompra','((precioVenta)*sum(vd.cantidad))-((precioCompra)*sum(vd.cantidad)) as diferencia'));
		$this->db->from('ventas as v');
		$this->db->join('ventas_detalle as vd','vd.ventaId = v.id');
		$this->db->join('productos as p','vd.productoid = p.id','left');
		$this->db->where("v.fecha >=",$fecha_desde);
		$this->db->where("v.fecha <=",$fecha_hasta);
		$this->db->group_by(array("v.fecha","vd.productoid")); 
		$query = $this->db->get();
		return $query->result_array();
	}

	public function ventasProductosXFechasResult($fecha_desde,$fecha_hasta)
	{
		$this->db->select(array('v.fecha','max(p.codigo) as codigoproducto','p.nombre','sum(vd.cantidad) as cantidad','(precioVenta)*sum(vd.cantidad)  as precioventa','(precioCompra)*sum(vd.cantidad) as preciocompra','((precioVenta)*sum(vd.cantidad))-((precioCompra)*sum(vd.cantidad)) as diferencia'));	
		$this->db->from('ventas as v');
		$this->db->join('ventas_detalle as vd','vd.ventaId = v.id');
		$this->db->join('productos as p','vd.productoid = p.id','left');
		$this->db->where("v.fecha >=",$fecha_desde);
		$this->db->where("v.fecha <=",$fecha_hasta);
		$this->db->group_by(array("v.fecha","vd.productoid")); 
		$query = $this->db->get();
		return $query->result();
	}

	public function get_all_ventasproductos_export_by_date($fecha_desde,$fecha_hasta) {
		$this->db->select(array('v.fecha','max(p.codigo) as codigoproducto','p.nombre','sum(vd.cantidad) as cantidad','(precioVenta)*sum(vd.cantidad)  as precioventa','(precioCompra)*sum(vd.cantidad) as preciocompra','((precioVenta)*sum(vd.cantidad))-((precioCompra)*sum(vd.cantidad)) as diferencia'));
		$this->db->from('ventas as v');
		$this->db->join('ventas_detalle as vd','vd.ventaId = v.id');
		$this->db->join('productos as p','vd.productoid = p.id','left');
		$this->db->where("v.fecha >=",$fecha_desde);
		$this->db->where("v.fecha <=",$fecha_hasta);
		$this->db->group_by(array("v.fecha","vd.productoid")); 
		$query = $this->db->get();
		return $query->result_array();
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
