<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes_model extends CI_Model {

	var $table = 'clientes';

	public function buscar($empresaId,$buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		$this->db->where('empresaId',$empresaId);
		$this->db->group_start();
	    $this->db->like('apellido', $buscar);
        $this->db->or_like('nombre', $buscar);
		$this->db->or_like('dni', $buscar);
		$this->db->group_end();
		
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
			$this->db->limit($cantidadregistro,$inicio);
		}
		$consulta = $this->db->get($this->table);
		return $consulta->result();
	}

	public function get_all($empresaId)
	{
		$this->db->select(array('e.id', 'e.nombre', 'e.apellido', 'e.dni','e.email','e.telefono','p.nombre as nombre_provincia','e.direccion'));
		$this->db->from('clientes as e');
		$this->db->where('empresaId',$empresaId);
		$this->db->join('provincias as p','e.provinciaId=p.Id', 'left outer');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_all_by_empresaid($empresaId)
	{
		$this->db->select(array('e.id', 'e.nombre', 'e.apellido', 'e.dni','e.email','e.telefono','p.nombre as nombre_provincia','e.direccion'));
		$this->db->from('clientes as e');
		$this->db->join('provincias as p','e.provinciaId=p.Id', 'left outer');
		$this->db->where('empresaId',$empresaId);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_all_export($empresaId) {
		$this->db->select(array('e.id', 'e.nombre', 'e.apellido', 'e.dni','e.email','e.telefono','p.nombre as nombre_provincia','e.direccion'));
		$this->db->from('clientes as e');
		$this->db->join('provincias as p','e.provinciaId=p.Id', 'left outer');
		$this->db->where('empresaId',$empresaId);
		$query = $this->db->get();
		return $query->result_array();
	 }
 
	function search_autocomplete($empresaId,$title){	
		$this->db->where('habilitado', 1);	
		$this->db->where('empresaId', $empresaId);	
		$this->db->group_start();
		$this->db->like('apellido', $title);
		$this->db->or_like('nombre', $title);
		$this->db->or_like('dni', $title);
        $this->db->order_by('apellido', 'ASC');
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

}
