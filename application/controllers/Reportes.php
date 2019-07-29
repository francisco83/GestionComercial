<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes extends CI_Controller {
	public function __construct(){
		parent::__construct();					
	}

	public function servicios(){
		$this->load->model("Servicios_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['servicios'] = $this->Servicios_model->get_all();		
		$this->load->view("Reportes/servicios",$data);
	}

	public function empresas(){
		$this->load->model("Empresas_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['filas'] = $this->Empresas_model->get_all();		
		$this->load->view("Reportes/empresas",$data);
	}

	public function sucursales(){
		$this->load->model("Sucursales_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['filas'] = $this->Sucursales_model->get_all();		
		$this->load->view("Reportes/sucursales",$data);
	}

	public function clientes(){
		$this->load->model("Clientes_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['filas'] = $this->Clientes_model->get_all();		
		$this->load->view("Reportes/clientes",$data);
	}

	public function groups(){
		$this->load->model("Groups_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['filas'] = $this->Groups_model->get_all();		
		$this->load->view("Reportes/groups",$data);
	}

	public function usuarios(){
		$this->load->model("Users_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['filas'] = $this->Users_model->get_all();		
		$this->load->view("Reportes/users",$data);
	}
}
