<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materiales_model extends CI_Model {

	var $table = 'materiales';

	public function buscar($empresaId,$buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		$this->db->select(array('e.id', 'e.codigo', 'e.nombre', 'e.descripcion','e.precioCompra','e.existencia','e.habilitado'));
		$this->db->from('materiales as e');		
		$this->db->where('e.empresaId',$empresaId);
		$this->db->group_start();
		$this->db->like('e.codigo', $buscar);
		$this->db->or_like('e.nombre', $buscar);
		$this->db->order_by('e.nombre', 'ASC');
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
			$this->db->limit($cantidadregistro,$inicio);
		}
		$this->db->group_end();
		$consulta = $this->db->get();

		return $consulta->result();
	}

	public function get_all($empresaId)
	{
		$this->db->select(array('e.id', 'e.codigo', 'e.nombre', 'e.descripcion', 'e.precioCompra','e.existencia','e.habilitado'));
		$this->db->from('materiales as e');	
		$this->db->where('e.empresaId',$empresaId);
		$this->db->order_by('e.nombre', 'ASC');
		$consulta = $this->db->get();
		return $consulta->result();
	}

	public function get_all_export($empresaId) {
		$this->db->select(array('e.id', 'e.codigo', 'e.nombre', 'e.descripcion','e.precioCompra','e.existencia','e.habilitado'));
		$this->db->from('materiales as e');		
		$this->db->where('e.empresaId',$empresaId);
		$this->db->order_by('e.nombre', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	 }
 

	function search_autocomplete($empresaId,$title){	
		$this->db->where('habilitado', 1);	
		$this->db->where('empresaId', $empresaId);	
		$this->db->group_start();	
		$this->db->like('codigo', $title);
        $this->db->or_like('nombre', $title);
        $this->db->order_by('nombre', 'ASC');
		$this->db->limit(10);
		$this->db->group_end();
        return $this->db->get($this->table)->result();
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



	public function enabled_by_id($id)
	{
		$reg = $this->db->get_where($this->table, array("id" => $id))->row();

		if($reg->habilitado == "1"){
			$this->habilitado = 0;
		}
		else{
			$this->habilitado = 1;
		}
		$data = array(
			'habilitado' => $this->habilitado
		);

		$this->db->update($this->table, $data, array("id" => $id));

	}

	// public function get_all_by_categoria($empresaId,$categoria)
	// {
	// 	$this->db->select(array('e.id', 'e.codigo', 'e.nombre', 'e.descripcion','c.nombre as categoria','e.precioCompra','e.existencia','e.habilitado'));
	// 	$this->db->from('materiales as e');
	// 	$this->db->join('categorias_materiales as c','c.id=e.tipo_categoria_id', 'left outer');
	// 	$this->db->where('e.empresaId', $empresaId);	
	// 	$this->db->where("e.tipo_categoria_id =",$categoria);
	// 	$this->db->or_where('"-1" =', $categoria); 
	// 	$this->db->order_by('e.nombre', 'ASC');
	// 	$query = $this->db->get();
	// 	return $query->result_array();

	// }


	// public function materialesXCategoriaResult($empresaId,$categoria)
	// {
	// 	$this->db->select(array('e.id', 'e.codigo', 'e.nombre', 'e.descripcion','c.nombre as categoria', 'e.precioCompra','e.existencia','e.habilitado'));
	// 	$this->db->from('materiales as e');
	// 	$this->db->join('categorias_materiales as c','c.id=e.tipo_categoria_id', 'left outer');
	// 	$this->db->where('e.empresaId', $empresaId);	
	// 	$this->db->where("e.tipo_categoria_id =",$categoria);
	// 	$this->db->or_where('"-1" =', $categoria); 
	// 	$this->db->order_by('e.nombre', 'ASC');
	// 	$query = $this->db->get();
	// 	return $query->result();

	// }

	// public function buscarDetalleHistorico($empresaId,$IdProducto)
	// {
	// 	$this->db->select(array('e.id', 'e.nombre', 'h.fecha','h.precioventaanterior','h.precioventanuevo','h.preciocompraanterior','h.preciocompranuevo'));
	// 	$this->db->from('materiales as e');
	// 	$this->db->join('precio_materiales_h as h','e.id=h.idproducto');
	// 	$this->db->where('empresaId', $empresaId);
	// 	$this->db->where("e.id =",$IdProducto);
	// 	$this->db->order_by('h.fecha', 'DESC');
	// 	$query = $this->db->get();
	// 	return $query->result();
	// }

	public function update_quantity($id, $quantity)
	{

		$reg = $this->db->get_where($this->table, array("id" => $id))->row();		

		$data = array(
			'existencia' => $reg->existencia - $quantity
		);

		$this->db->update($this->table, $data, array("id" => $id));

	}


}
