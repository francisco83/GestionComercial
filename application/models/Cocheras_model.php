<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cocheras_model extends CI_Model {

	var $table = 'cocheras';

	public function buscar($buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{

		$this->db->select(array('e.id', 'e.nombre', 'e.comentario','c.nombre as tipo_cochera', 'e.disponible','e.habilitado'));
		$this->db->from('cocheras as e');
		$this->db->join('tipos_cocheras as c','c.id=e.tipo_cochera_id', 'left outer');		
		$this->db->like('e.nombre', $buscar);
		$this->db->order_by('e.nombre', 'ASC');
		if ($inicio !== FALSE && $cantidadregistro !== FALSE) {
			$this->db->limit($cantidadregistro,$inicio);
		}

		$consulta = $this->db->get();

		return $consulta->result();
	}

	public function get_all()
	{
		$this->db->select(array('e.id', 'e.nombre', 'e.comentarios','c.nombre as tipo_cochera', 'e.disponible','e.habilitado'));
		$this->db->from('cocheras as e');
		$this->db->join('tipos_cocheras as c','c.id=e.tipo_cochera_id', 'left outer');
		$this->db->order_by('e.nombre', 'ASC');
		$consulta = $this->db->get();
		return $consulta->result();
	}

	public function get_all_export() {
		$this->db->select(array('e.id', 'e.nombre', 'e.comentarios','c.nombre as tipo_cochera', 'e.disponible','e.habilitado'));
		$this->db->from('cocheras as e');
		$this->db->join('tipos_cocheras as c','c.id=e.tipo_cochera_id', 'left outer');
		$this->db->order_by('e.nombre', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	 }
 

	function search_autocomplete($title){				
        $this->db->like('nombre', $title);
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

	public function get_all_by_categoria($categoria)
	{
		$this->db->select(array('e.id', 'e.nombre', 'e.comentarios','c.nombre as tipo_cochera', 'e.disponible','e.habilitado'));
		$this->db->from('cocheras as e');
		$this->db->join('tipos_cocheras as c','c.id=e.tipo_categoria_id', 'left outer');
		$this->db->where("e.tipo_cochera_id =",$categoria);
		$this->db->or_where('"-1" =', $categoria); 
		$this->db->order_by('e.nombre', 'ASC');
		$query = $this->db->get();
		return $query->result_array();

	}


	public function cocherasXCategoriaResult($categoria)
	{
		$this->db->select(array('e.id', 'e.nombre', 'e.comentarios','c.nombre as tipo_cochera', 'e.disponible','e.habilitado'));
		$this->db->from('cocheras as e');
		$this->db->join('tipos_cocheras as c','c.id=e.tipo_categoria_id', 'left outer');
		$this->db->where("e.tipo_categoria_id =",$categoria);
		$this->db->or_where('"-1" =', $categoria); 
		$this->db->order_by('e.nombre', 'ASC');
		$query = $this->db->get();
		return $query->result();

	}


}
