<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permisos_model extends CI_Model {

	var $table = 'permisos';

	public function buscar($buscar,$inicio = FALSE, $cantidadregistro = FALSE)
	{
	    $this->db->like('controlador', $buscar);
        $this->db->or_like('accion', $buscar);				
		
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

	public function get_all_array()
	{			
		$consulta = $this->db->get($this->table);
		return $consulta->result_array();
	}

	public function get_by_controllerAndUser($controlador,$userId)
	{
		$this->db->select(array('p.controlador', 'p.accion'));
		$this->db->from('permisos as p');
		$this->db->join('users_groups as ug','ug.group_id = p.group_id');
		$this->db->join('users as u','u.id = ug.user_id');
		$this->db->where('controlador',$controlador);
		$this->db->where('u.id',$userId);
		$consulta = $this->db->get();
		return $consulta->result();
	}

	public function get_all_export() {
		$this->db->select(array('e.id', 'e.controlador', 'e.accion', 'e.descripcion'));
		$this->db->from('permisos as p');			
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


	public function Permisos_Grupos_sinAsignar($grupoId)
	{
		$this->db->select(array('p.id', 'p.controlador', 'p.accion','p.descripcion'));
		$this->db->from('permisos as p');
		$this->db->where("p.id NOT IN (select p1.Id 
		from permisos p1
		inner join grupos_permisos gp on p1.id = gp.permisoId and gp.grupoId = ".$grupoId.")", NULL, FALSE);
		$this->db->order_by('p.controlador', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}
	public function Permisos_Grupos_Asignados($grupoId)
	{
		$this->db->select(array('p.id', 'p.controlador', 'p.accion','p.descripcion'));
		$this->db->from('permisos as p');
		$this->db->where("p.id IN (select p1.Id 
		from permisos p1
		inner join grupos_permisos gp on p1.id = gp.permisoId and gp.grupoId = ".$grupoId.")", NULL, FALSE);
		$this->db->order_by('p.controlador', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}

	public function Insertar_grupos_permisos($grupoId,$permisosId)
	{
		$this->grupoId = $grupoId;
		$this->permisosId= $permisosId;

		$this->db->trans_begin();

		for ($i=0; $i < count($permisosId); $i++) 
		{   			
			$data[$i]['grupoId'] = $grupoId;
			$data[$i]['permisoId'] = $permisosId[$i];						
		}

		$this->db->where('grupoId', $grupoId);
		$this->db->delete('grupos_permisos');
		$this->db->insert_batch('grupos_permisos', $data);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();		
		}

	}	
	
	
	public function VerificarPermisos($userId,$controlador)
	{
		$this->db->select('upper(p.accion) as accion');
		$this->db->from('users u');
		$this->db->join('users_groups ug','u.id = ug.user_id');
		$this->db->join('grupos_permisos gp','ug.group_id = gp.grupoId' );
		$this->db->join('permisos p','p.id= gp.permisoId' );
		$this->db->where('upper(p.controlador)',strtoupper($controlador));
		$this->db->where('u.id',$userId);		
		$query = $this->db->get();
		return $query->result();
	}


}
