<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tipos_Servicios_model extends CI_Model {

	var $table = 'tipos_servicios';

	public function buscar($buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
		$this->db->like("nombre",$buscar);
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
		$this->db->select(array('e.id', 'e.nombre', 'e.descripcion', 'e.precio'));
		$this->db->from('tipos_servicios as e');
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

	public function buscarDetalleHistorico($IdServicio)
	{
		$this->db->select(array('e.id', 'e.nombre', 'h.fecha','h.precioanterior','h.precionuevo'));
		$this->db->from('tipos_servicios as e');
		$this->db->join('precio_servicios_h as h','e.id=h.idservicio');
		$this->db->where("e.id =",$IdServicio);
		$this->db->order_by('h.fecha', 'DESC');
		$query = $this->db->get();
		return $query->result();
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
