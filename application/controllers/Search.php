<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Search extends CI_Controller {


	public function index()
	{
		$json = [];
		$this->load->database();		
		if(!empty($this->input->get("q"))){
			$this->db->like('nombre', $this->input->get("q"));
		}
			$query = $this->db->select('id,nombre as text')
						->limit(10)
						->get("servicios");
			$json = $query->result();		
		
		echo json_encode($json);
	}
}
