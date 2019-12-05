<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("Ventas_model");
		$this->load->model("Ventas_detalle_model");
		$this->load->model("Pagos_model");
		$this->load->library(['ion_auth', 'form_validation']);

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}
	}

	public function index(){
		$this->load->view("Ventas/index");
	}

	public function ver(){	
		$this->load->view("Ventas/ver");
	}

	public function insertar(){
		$clienteId = $_POST['clienteid']; 
		$fecha = $_POST['fechahoy'];
		$IdProducto = $_POST['IdProducto'];
		$CodigoProducto = $_POST['CodigoProducto'];
		$PrecioVenta = $_POST['PrecioVenta'];
		$Cantidad = $_POST['Cantidad'];
		$moneda = $_POST['moneda'];
		$monedaMonto = $_POST['monedaMonto'];
		$total=$_POST['totalVentaFinal'];
		$vuelto=$_POST['totalVueltoFinal'];

		//$user = $this->ion_auth->logged_in();

		
		$empleadoId =1;
		$sucursalId=0;
		

		$id = $this->Ventas_model->guardarCambios($fecha,$total,$vuelto,$clienteId,$empleadoId,$sucursalId);

		$id = $id;
		
		if($id > 0)
		{
			for ($i=0; $i < count($IdProducto); $i++) 
			{   			
				$data[$i]['ventaId'] = $id;
				$data[$i]['productoId'] = $IdProducto[$i];			
				$data[$i]['Precio'] = $PrecioVenta[$i];
				$data[$i]['Cantidad'] = $Cantidad[$i];
			}


			$resultado = $this->Ventas_detalle_model->guardarCambios($data);

			if($resultado){

				for ($i=0; $i < count($moneda); $i++) 
				{   			
					$dataPago[$i]['ventaId'] = $id;
					$dataPago[$i]['tipo_monedaId'] = $moneda[$i];			
					$dataPago[$i]['monto'] = $monedaMonto[$i];
				}

				$resultado = $this->Pagos_model->guardarCambios($dataPago);

				if($resultado){
					$mensaje = "Registro cargado correctamente";
					$clase = "success";			
				}else{
					$mensaje = "Error al registrar la venta";
					$clase = "danger";
					$json['error'] = $this->upload->display_errors();
				}
				$this->session->set_flashdata(array(
					"mensaje" => $mensaje,
					"clase" => $clase,
				));

			}
		}
		else{

			$mensaje = "Error al registrar la venta";
					$clase = "danger";
					$json['error'] = $this->upload->display_errors();
		}
				$this->session->set_flashdata(array(
					"mensaje" => $mensaje,
					"clase" => $clase,
				));
	}


	public function mostrarXcliente()
	{	
		$clienteId = $this->input->post("clienteId");
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;
		$data = array(
			"cli_ventas" => $this->Ventas_model->buscarXcliente($clienteId,$buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Ventas_model->buscarXcliente($clienteId,$buscar)),
			"cantidad" =>$cantidad
			
		);
		echo json_encode($data);
	}

	public function mostrarDetalleXcliente()
	{	
		$ventaId = $this->input->post("ventaId");
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;
		$data = array(
			"ventas_detalle" => $this->Ventas_detalle_model->buscarDetalleXcliente($ventaId,$buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Ventas_detalle_model->buscarDetalleXcliente($ventaId,$buscar)),
			"cantidad" =>$cantidad
			
		);
		echo json_encode($data);
	}



}
