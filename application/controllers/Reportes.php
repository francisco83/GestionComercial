<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes extends CI_Controller {
	public function __construct(){
		parent::__construct();			
		$this->load->library(['ion_auth', 'form_validation']);
		
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}
	}

	public function tipos_servicios(){
		$this->load->model("Tipos_Servicios_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['tipos_servicios'] = $this->Tipos_Servicios_model->get_all();		
		$this->load->view("Reportes/tipos_servicios",$data);
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

	public function ver_registrar($id){
		$this->load->model("Cli_servicios_detalle_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['filas'] = $this->Cli_servicios_detalle_model->buscarDetalleImprimir($id);		
		$this->load->view("Reportes/ver_detalle_registrar",$data);
	}

	public function productos(){
		$this->load->model("Productos_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['filas'] = $this->Productos_model->get_all();		
		$this->load->view("Reportes/productos",$data);
	}

	public function proveedores(){
		$this->load->model("Proveedores_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['filas'] = $this->Proveedores_model->get_all();		
		$this->load->view("Reportes/proveedores",$data);
	}
}
