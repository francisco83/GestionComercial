<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Provincias extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("Provincias_model");
		$this->load->library(['ion_auth', 'form_validation']);

		if (!$this->ion_auth->logged_in())
		 {
		 	redirect('Home', 'refresh');
		 }
	}

	public function get_all(){
		$resultado = $this->Provincias_model->get_all();
		echo $resultado;
	}

	public function get_all_array(){
		$resultado = $this->Provincias_model->get_all_array();		
		echo json_encode($resultado);
	}

}
