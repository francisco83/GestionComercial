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

	public function guardar($fecha,$total,$clienteId,$empleadoId,$sucursalId,$IdProducto,$PrecioVenta,$Cantidad,$moneda,$monedaMonto,$vuelto)
	{
		$this->fecha = $fecha;
		$this->total= $total;
		$this->clienteId = $clienteId;
		$this->empleadoId =$empleadoId;
		$this->sucursalId =$sucursalId;

		$this->db->trans_begin();

		$this->db->insert('ventas', $this);		
		$id = $this->db->insert_id();		

		for ($i=0; $i < count($IdProducto); $i++) 
		{   			
			$data[$i]['ventaId'] = $id;
			$data[$i]['productoId'] = $IdProducto[$i];			
			$data[$i]['Precio'] = $PrecioVenta[$i];
			$data[$i]['Cantidad'] = $Cantidad[$i];

			$this->productos_model->update_quantity($IdProducto[$i],$Cantidad[$i]);
		}


		$resultado = $this->Ventas_detalle_model->guardarCambios($data);

		if($resultado){

			for ($i=0; $i < count($moneda); $i++) 
			{   			
				$dataPago[$i]['ventaId'] = $id;
				$dataPago[$i]['tipo_monedaId'] = $moneda[$i];			
				$dataPago[$i]['monto'] = $monedaMonto[$i];
				$dataPago[$i]['fecha_pago'] = $fecha;

				if($moneda[$i]== 1 && $monedaMonto[$i]!=0 && $monedaMonto[$i]!=null){//si es efectivo solo puede dar vuelto
					$dataPago[$i]['vuelto']=$vuelto;
				}
				else{
					$dataPago[$i]['vuelto']=0;
				}
				
			}
			if (count($dataPago)!=1 || (count($dataPago)==1 && $dataPago[0]['tipo_monedaId']=='1' && $dataPago[0]['monto']!=0))
			{
				$resultado = $this->Pagos_model->guardarCambios($dataPago);
			}

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

	public function delete_by_masterid($id)
	{
		$this->db->trans_begin();

		$this->db->select(array('vd.id', 'vd.productoId', 'vd.cantidad'));
		$this->db->from('ventas_detalle as vd');
		$this->db->where("ventaId",$id);
		$query = $this->db->get();

		if (count($query) > 0) {
			foreach ($query->result() as $row)	
			{
				$this->db->set('existencia', 'existencia + '.$row->cantidad, FALSE);
				$this->db->where('id', $row->productoId);
				$this->db->update("productos");
			}							
		}	
	
		$this->db->delete("pagos", array("ventaId" => $id));
		$this->db->delete("ventas_detalle", array("ventaId" => $id));
		$this->db->delete("ventas", array("id" => $id));
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
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

	//buscarDetalleImprimir



}
