<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos_model extends CI_Model {

	var $table = 'productos';

	public function buscar($buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		$this->db->like('codigo', $buscar);
        $this->db->or_like('nombre', $buscar);
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
		$this->db->select(array('e.id', 'e.codigo', 'e.nombre', 'e.descripcion', 'e.precioVenta','e.precioCompra','e.existencia'));
		$this->db->from('productos as e');
		$query = $this->db->get();
		return $query->result_array();
	 }
 

	function search_autocomplete($title){		
		$this->db->like('codigo', $title);
        $this->db->or_like('nombre', $title);
        $this->db->order_by('nombre', 'ASC');
        $this->db->limit(10);
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
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
	 $this->db->delete($this->table, array("id" => $id));
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

}
