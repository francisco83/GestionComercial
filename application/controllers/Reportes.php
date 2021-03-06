<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes extends CI_Controller {

	var $empresaId;	

	public function __construct(){
		parent::__construct();			
		$this->load->library(['ion_auth', 'form_validation']);
		
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}
		else
		{
			$this->empresaId = $this->ion_auth->get_empresa_id();
		}	
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
		$data['filas'] = $this->Clientes_model->get_all($this->empresaId);		
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

	public function ver_venta($id){
		$this->load->model("Ventas_detalle_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['filas'] = $this->Ventas_detalle_model->buscarDetalleImprimir($id);		
		$this->load->view("Reportes/ver_detalle_venta",$data);
	}

	public function ver_compra($id){
		$this->load->model("Compras_detalle_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['filas'] = $this->Compras_detalle_model->buscarDetalleImprimir($id);		
		$this->load->view("Reportes/ver_detalle_compra",$data);
	}

	public function ver_venta_ctacte($id){
		$this->load->model("Ventas_detalle_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['filas'] = $this->Ventas_detalle_model->buscarDetalleImprimir($id);		
		$data['pagos'] = $this->Ventas_detalle_model->buscarDetallePagoImprimir($id);		
		$this->load->view("Reportes/ver_detalle_venta_ctacte",$data);
	}
	public function productos(){
		$this->load->model("Productos_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['filas'] = $this->Productos_model->get_all($this->empresaId);		
		$this->load->view("Reportes/productos",$data);
	}

	public function materiales(){
		$this->load->model("Materiales_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['filas'] = $this->Materiales_model->get_all($this->empresaId);		
		$this->load->view("Reportes/materiales",$data);
	}

	public function proveedores(){
		$this->load->model("Proveedores_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['filas'] = $this->Proveedores_model->get_all($this->empresaId);		
		$this->load->view("Reportes/proveedores",$data);
	}

	public function tipos_servicios(){
		$this->load->model("Tipos_Servicios_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['tipos_servicios'] = $this->Tipos_Servicios_model->get_all($this->empresaId);		
		$this->load->view("Reportes/tipos_servicios",$data);
	}

	public function tipos_monedas(){
		$this->load->model("Tipos_Monedas_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['tipos_monedas'] = $this->Tipos_Monedas_model->get_all();		
		$this->load->view("Reportes/tipos_monedas",$data);
	}

	public function tipos_pagos(){
		$this->load->model("Tipos_Pagos_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['tipos_pagos'] = $this->Tipos_Pagos_model->get_all();		
		$this->load->view("Reportes/tipos_pagos",$data);
	}

	public function estados_pedidos(){
		$this->load->model("Estados_Pedidos_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['estados_pedidos'] = $this->Estados_Pedidos_model->get_all();		
		$this->load->view("Reportes/estados_pedidos",$data);
	}

	public function tipos_cocheras(){
		$this->load->model("Tipos_Cocheras_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['tipos_cocheras'] = $this->Tipos_Cocheras_model->get_all();		
		$this->load->view("Reportes/tipos_cocheras",$data);
	}

	public function cocheras(){
		$this->load->model("Cocheras_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['cocheras'] = $this->Cocheras_model->get_all();		
		$this->load->view("Reportes/cocheras",$data);
	}

	public function categorias_productos(){
		$this->load->model("Categorias_Productos_model");
		$this->load->view("partial/cabecera_reporte");		
		$data['categorias_productos'] = $this->Categorias_Productos_model->get_all($this->empresaId);		
		$this->load->view("Reportes/categorias_productos",$data);
	}

	public function ventas(){			
		$this->load->view("Reportes/ventas");
	}

	public function compras(){			
		$this->load->view("Reportes/compras");
	}


	public function ventasxproductos(){			
		$this->load->view("Reportes/ventasxproductos");
	}

	public function ventasXFechas()
	{		
		$fecha_desde = $_GET['fecha_desde']; 
		$fecha_hasta = $_GET['fecha_hasta'];
		$this->load->view("partial/cabecera_reporte");	
		$this->load->model("Ventas_model");		
		$data['filas'] = $this->Ventas_model->ventasXFechasResult($this->empresaId,$fecha_desde,$fecha_hasta);
		$this->load->view("Reportes/ventas_print",$data);
	}

	public function ventasproductosXFechas()
	{		
		$fecha_desde = $_GET['fecha_desde']; 
		$fecha_hasta = $_GET['fecha_hasta'];
		$this->load->view("partial/cabecera_reporte");	
		$this->load->model("Ventas_model");		
		$data['filas'] = $this->Ventas_model->ventasProductosXFechasResult($this->empresaId,$fecha_desde,$fecha_hasta);
		$this->load->view("Reportes/ventasxproductos_print",$data);
	}

	public function comprasXFechas()
	{		
		$fecha_desde = $_GET['fecha_desde']; 
		$fecha_hasta = $_GET['fecha_hasta'];
		$this->load->view("partial/cabecera_reporte");	
		$this->load->model("Compras_model");		
		$data['filas'] = $this->Compras_model->comprasXFechasResult($this->empresaId,$fecha_desde,$fecha_hasta);
		$this->load->view("Reportes/compras_print",$data);
	}


	public function productos_filtros(){			
		$this->load->view("Reportes/productos_filtros");
	}

	public function productosXCategoria()
	{		
		$categoria = $_GET['categoria']; 	   
		$this->load->view("partial/cabecera_reporte");
		$this->load->model("Productos_model");		
		$data['filas'] =   $this->Productos_model->productosXCategoriaResult($this->empresaId,$categoria);				
		$this->load->view("Reportes/productos_print",$data);
	}
	
}
