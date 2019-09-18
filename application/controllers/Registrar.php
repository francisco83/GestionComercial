<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Registrar extends CI_Controller {
	
	public function __construct(){		
		parent::__construct();
		$this->load->model("Clientes_model");		
		$this->load->model("Tipos_Servicios_model");	
		$this->load->model("Cli_servicios_detalle_model");
		$this->load->model("Cli_servicios_model");	
		$this->load->library(['ion_auth', 'form_validation']);

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

	}

	public function index($id){	
		$cliente = $this->Clientes_model->get_by_id($id);
		$this->load->view("registrar/index",$cliente);
	}

	// public function editar($serviceId){	
	// 	//$data['filas'] = 
	// 	$detalle['filas']  = $this->Cli_servicios_detalle_model->get_by_serviceId($serviceId);
	// 	$this->load->view("registrar/editar",$detalle);
	// 	//echo json_encode($detalle);
	// }

	public function editar($serviceId){	
		//$data['filas'] = 
		//$detalle['filas']  = $this->Cli_servicios_detalle_model->get_by_serviceId($serviceId);
		$service['servicioId']=$serviceId;
		$this->load->view("registrar/editar",$service);
		//echo json_encode($detalle);
	}

	public function get_servicios($serviceId){
		$detalle=  $this->Cli_servicios_detalle_model->get_by_serviceId($serviceId);
		echo json_encode($detalle);
	}

	public function ver(){	
		$this->load->view("registrar/ver");
	}

	function get_autocomplete(){
        if (isset($_GET['term'])) {
            $result = $this->Tipos_Servicios_model->search_blog($_GET['term']);
            if (count($result) > 0) {
			foreach ($result as $row)	
			{
				$data[] = array(
					'id' => $row->id,
					'value'=> $row->apellido.' '.$row->nombre
				);
			}				
                echo json_encode($data);
            }
        }
	}

	public function get_all(){
		$resultado = $this->Tipos_Servicios_model->get_all();
		echo json_encode($resultado);
	}

	public function insertar(){
		$clienteid = $_POST['clienteid']; 
		$fecha = $_POST['fechahoy'];
		$cantidad = $_POST['cantidad'];
		$servicio = $_POST['servicio'];
		$detalle = $_POST['detalle'];
		$precio = $_POST['precio'];


		$id = $this->Cli_servicios_model->guardarCambios($clienteid,$fecha);

		for ($i=0; $i < count($servicio); $i++) 
		{   
			//$data[$i]['id_cliente'] = $clienteid;
			$data[$i]['id_cli_servicios'] = $id;
			$data[$i]['id_servicio'] = $servicio[$i];
			//$data[$i]['fecha'] = $fecha;
			$data[$i]['precio'] = $precio[$i];
			$data[$i]['cantidad'] = $cantidad[$i];
			$data[$i]['descripcion'] = $detalle[$i];
		}


		$resultado = $this->Cli_servicios_detalle_model->guardarCambios($data);

		if($resultado){
            $mensaje = "Registro cargado correctamente";
			$clase = "success";
			
			//$json['success'] = 'You have upload your selected files!';


        }else{
            $mensaje = "Error al registrar la carga de servicios";
			$clase = "danger";
			$json['error'] = $this->upload->display_errors();
        }
        $this->session->set_flashdata(array(
            "mensaje" => $mensaje,
            "clase" => $clase,
		));
		
		//$resultado = $this->Tipos_Servicios_model->listar();
		echo json_encode($this);
		
		//redirect('registrar');
		//redirect("registrar/"+$clienteid);
		
	}


	public function mostrar()
	{	
		//valor a Buscar
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;
		$data = array(
			"cli_servicios" => $this->Cli_servicios_model->buscar($buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Cli_servicios_model->buscar($buscar)),
			"cantidad" =>$cantidad
			
		);
		echo json_encode($data);
	}

	public function mostrarXcliente()
	{	
		//valor a Buscar
		$clienteId = $this->input->post("clienteId");
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;
		$data = array(
			"cli_servicios" => $this->Cli_servicios_model->buscarXcliente($clienteId,$buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Cli_servicios_model->buscar($clienteId,$buscar)),
			"cantidad" =>$cantidad
			
		);
		echo json_encode($data);
	}

	public function mostrarDetalleXcliente()
	{	
		//valor a Buscar
		$servicioId = $this->input->post("servicioId");
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;
		$data = array(
			"cli_servicios_detalle" => $this->Cli_servicios_detalle_model->buscarDetalleXcliente($servicioId,$buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Cli_servicios_detalle_model->buscarDetalleXcliente($servicioId,$buscar)),
			"cantidad" =>$cantidad
			
		);
		echo json_encode($data);
	}

	public function ajax_delete($id)
	{	
		$this->Cli_servicios_detalle_model->delete_by_masterid($id);		
		echo json_encode(array("status" => TRUE));
	}
	
	
}
