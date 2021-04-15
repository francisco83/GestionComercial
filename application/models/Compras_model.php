<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compras_model extends CI_Model {

	//var $table = 'compras';


	public function guardarCambios($fecha,$total,$proveedorId,$empleadoId,$sucursalId){
		$this->fecha = $fecha;
		$this->total= $total;
		//$this->vuelto= $vuelto;
		$this->proveedorId = $proveedorId;
		$this->empleadoId =$empleadoId;
		$this->sucursalId =$sucursalId;

		$this->db->insert('compras', $this);		
		//$this->db->insert_batch($this->table, $this);
		return $this->db->insert_id();		
	}

	public function guardar($empresaId,$fecha,$total,$proveedorId,$empleadoId,$sucursalId,$IdProducto,$PrecioCompra,$Cantidad,$moneda,$monedaMonto,$vuelto)
	{
		$this->fecha = $fecha;
		$this->total= $total;
		$this->proveedorId = $proveedorId;
		$this->empleadoId =$empleadoId;
		$this->sucursalId =$sucursalId;
		$this->empresaId = $empresaId;

		$this->db->trans_begin();

		$this->db->insert('compras', $this);		
		$id = $this->db->insert_id();		

		for ($i=0; $i < count($IdProducto); $i++) 
		{   			
			$data[$i]['compraId'] = $id;
			$data[$i]['productoId'] = $IdProducto[$i];			
			$data[$i]['Precio'] = $PrecioCompra[$i];
			$data[$i]['Cantidad'] = $Cantidad[$i];

			$this->productos_model->update_quantity($IdProducto[$i],$Cantidad[$i]);
		}


		$resultado = $this->Compras_detalle_model->guardarCambios($data);

		if($resultado){

			for ($i=0; $i < count($moneda); $i++) 
			{   			
				$dataPago[$i]['compraId'] = $id;
				$dataPago[$i]['tipo_monedaId'] = $moneda[$i];			
				$dataPago[$i]['monto'] = $monedaMonto[$i];
				$dataPago[$i]['fecha_pago'] = $fecha;
				$dataPago[$i]['empresaId'] = $empresaId;

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

	public function buscar($empresaId,$buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		$this->db->where('empresaId',$empresaId);
		$this->db->group_start();
		$this->db->like("fecha",$buscar);
		$this->db->group_end();
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
			$this->db->limit($cantidadregistro,$inicio);
		}
		$consulta = $this->db->get('compras');
		return $consulta->result();
	}

	public function get_all($empresaId)
	{
		$this->db->where('empresaId',$empresaId);
		$consulta = $this->db->get('compras');
		return $consulta->result();
	}

	public function get_all_export($empresaId) {
		$this->db->select(array('e.id', 'e.fecha', 'e.total'));
		$this->db->from('compras as e');
		$this->db->where('e.empresaId',$empresaId);
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
		$this->db->insert('compras', $data);
		return $this->db->insert_id();
	}

	public function get_by_id($id)
	{
		return $this->db->get_where('compras', array("id" => $id))->row();
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
		$this->db->from('compras_detalle as vd');
		$this->db->where("compraId",$id);
		$query = $this->db->get();

		if (count($query) > 0) {
			foreach ($query->result() as $row)	
			{
				$this->db->set('existencia', 'existencia + '.$row->cantidad, FALSE);
				$this->db->where('id', $row->productoId);
				$this->db->update("productos");
			}							
		}	
	
		$this->db->delete("pagos", array("compraId" => $id));
		$this->db->delete("compras_detalle", array("compraId" => $id));
		$this->db->delete("compras", array("id" => $id));
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


	public function buscarXproveedor($empresaId,$proveedorId,$buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{		
		$this->db->where("empresaId",$empresaId);
		$this->db->where("proveedorId",$proveedorId);
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
		 	$this->db->limit($cantidadregistro,$inicio);
		 }
		$this->db->order_by("fecha", "desc");
		$this->db->order_by("Id", "desc");
		$consulta = $this->db->get("compras");
		 
		return $consulta->result();
	}

	
	public function detalleCtaCteCompraxProveedor($empresaId,$proveedorId)
	{	
		$this->db->select('compras.id as codigo_compra,compras.fecha as fecha_compra,compras.total as total,IFNULL(sum(pagos.vuelto),0) as vuelto,IFNULL(sum(pagos.monto),0) as monto,proveedores.Id as proveedorId,proveedores.nombre');
		$this->db->from('compras');
		$this->db->join('pagos','compras.id=pagos.compraId','left');
		$this->db->join('proveedores','proveedores.id=compras.proveedorId');
		$this->db->where("compras.proveedorId",$proveedorId);
		$this->db->where('compras.empresaId',$empresaId);
		$this->db->group_by(array("compras.id", "compras.fecha")); 
		$this->db->order_by("compras.Id,compras.fecha","desc");

		$consulta=$this->db->get();
		return $consulta->result();
	}

	public function comprasXFechas($empresaId,$fecha_desde,$fecha_hasta)
	{
		$this->db->select(array('e.id', 'e.fecha', 'e.total','proveedores.Id as proveedorId','proveedores.nombre','IFNULL(sum(pagos.monto),0) as monto','IFNULL(sum(pagos.vuelto),0) as vuelto'));
		$this->db->from('compras as e');
		$this->db->join('pagos','e.id=pagos.compraId','left');
		$this->db->join('proveedores','proveedores.id=e.proveedorId');
		$this->db->where("e.fecha >=",$fecha_desde);
		$this->db->where("e.fecha <=",$fecha_hasta);
		$this->db->where('e.empresaId',$empresaId);
		$this->db->group_by(array("e.id", "e.fecha")); 
		$query = $this->db->get();
		return $query->result_array();

	}

	public function comprasXFechasResult($empresaId,$fecha_desde,$fecha_hasta)
	{
		$this->db->select(array('e.id', 'e.fecha', 'e.total','proveedores.Id as proveedorId','proveedores.nombre','IFNULL(sum(pagos.monto),0) as monto','IFNULL(sum(pagos.vuelto),0) as vuelto'));
		$this->db->from('compras as e');
		$this->db->join('pagos','e.id=pagos.compraId','left');
		$this->db->join('proveedores','proveedores.id=e.proveedorId');
		$this->db->where("e.fecha >=",$fecha_desde);
		$this->db->where("e.fecha <=",$fecha_hasta);
		$this->db->where('e.empresaId',$empresaId);
		$this->db->group_by(array("e.id", "e.fecha")); 
		$query = $this->db->get();
		return $query->result();

	}

	public function get_all_export_by_date($empresaId,$fecha_desde,$fecha_hasta) {
		$this->db->select(array('e.id', 'e.fecha', 'e.total','proveedores.Id as proveedorId','proveedores.nombre','IFNULL(sum(pagos.monto),0) as monto','IFNULL(sum(pagos.vuelto),0) as vuelto'));
		$this->db->from('compras as e');
		$this->db->join('pagos','e.id=pagos.compraId','left');
		$this->db->join('proveedores','proveedores.id=e.proveedorId');
		$this->db->where("e.fecha >=",$fecha_desde);
		$this->db->where("e.fecha <=",$fecha_hasta);
		$this->db->where('e.empresaId',$empresaId);
		$this->db->group_by(array("e.id", "e.fecha")); 
		$query = $this->db->get();
		return $query->result_array();
	 }


	public function comprasProductosXFechas($empresaId,$fecha_desde,$fecha_hasta)
	{
		$this->db->select(array('v.fecha','max(p.codigo) as codigoproducto','p.nombre','sum(vd.cantidad) as cantidad','(precioCompra)*sum(vd.cantidad)  as preciocompra','(precioCompra)*sum(vd.cantidad) as preciocompra','((precioCompra)*sum(vd.cantidad))-((precioCompra)*sum(vd.cantidad)) as diferencia'));
		$this->db->from('compras as v');
		$this->db->join('compras_detalle as vd','vd.compraId = v.id');
		$this->db->join('productos as p','vd.productoid = p.id','left');
		$this->db->where("v.fecha >=",$fecha_desde);
		$this->db->where("v.fecha <=",$fecha_hasta);
		$this->db->where('v.empresaId',$empresaId);
		$this->db->group_by(array("v.fecha","vd.productoid")); 
		$query = $this->db->get();
		return $query->result_array();
	}

	public function comprasProductosXFechasResult($empresaId,$fecha_desde,$fecha_hasta)
	{
		$this->db->select(array('v.fecha','max(p.codigo) as codigoproducto','p.nombre','sum(vd.cantidad) as cantidad','(precioCompra)*sum(vd.cantidad)  as preciocompra','(precioCompra)*sum(vd.cantidad) as preciocompra','((precioCompra)*sum(vd.cantidad))-((precioCompra)*sum(vd.cantidad)) as diferencia'));	
		$this->db->from('compras as v');
		$this->db->join('compras_detalle as vd','vd.compraId = v.id');
		$this->db->join('productos as p','vd.productoid = p.id','left');
		$this->db->where("v.fecha >=",$fecha_desde);
		$this->db->where("v.fecha <=",$fecha_hasta);
		$this->db->where('v.empresaId',$empresaId);
		$this->db->group_by(array("v.fecha","vd.productoid")); 
		$query = $this->db->get();
		return $query->result();
	}

	public function get_all_comprasproductos_export_by_date($empresaId,$fecha_desde,$fecha_hasta) {
		$this->db->select(array('v.fecha','max(p.codigo) as codigoproducto','p.nombre','sum(vd.cantidad) as cantidad','(precioCompra)*sum(vd.cantidad)  as preciocompra','(precioCompra)*sum(vd.cantidad) as preciocompra','((precioCompra)*sum(vd.cantidad))-((precioCompra)*sum(vd.cantidad)) as diferencia'));
		$this->db->from('compras as v');
		$this->db->join('compras_detalle as vd','vd.compraId = v.id');
		$this->db->join('productos as p','vd.productoid = p.id','left');
		$this->db->where("v.fecha >=",$fecha_desde);
		$this->db->where("v.fecha <=",$fecha_hasta);
		$this->db->where('v.empresaId',$empresaId);
		$this->db->group_by(array("v.fecha","vd.productoid")); 
		$query = $this->db->get();
		return $query->result_array();
	 }

	//buscarDetalleImprimir



}
