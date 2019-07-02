<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cli_servicios_detalle_model extends CI_Model {


	public function guardarCambios($data){

		return $this->db->insert_batch('cli_servicios_detalle', $data);
	}

}
