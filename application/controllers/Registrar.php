<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Registrar extends CI_Controller {
	
	public function __construct(){		
		parent::__construct();
		$this->load->model("Clientes_model");		
		$this->load->model("Servicios_model");	
		$this->load->model("Cliente_servicios_model");		
	}

	public function index($id){	
		$this->load->view("registrar/index");
	}

	function get_autocomplete(){
        if (isset($_GET['term'])) {
            $result = $this->Servicios_model->search_blog($_GET['term']);
            if (count($result) > 0) {
			foreach ($result as $row)	
			{
				$data[] = array(
					'id' => $row->id,
					'value'=> $row->nombre
				);
			}				
                echo json_encode($data);
            }
        }
	}

	public function listar(){
		$resultado = $this->Servicios_model->listar();
		echo json_encode($resultado);
	}

	public function insertar(){
		$fecha = $_POST['fecha'];
		$cantidad = $_POST['cantidad'];
		$servicio = $_POST['servicio'];
		$detalle = $_POST['detalle'];

		for ($i=0; $i < count($fecha); $i++) 
		{   
			$data[$i]['id_cliente'] = '1';
			$data[$i]['id_servicio'] = $servicio[$i];;
			$data[$i]['fecha'] = $fecha[$i];
			$data[$i]['precio'] = '300';
			$data[$i]['cantidad'] = $cantidad[$i];
			$data[$i]['descripcion'] = $detalle[$i];
		}
		
		$result = $this->Cliente_servicios_model->guardarCambios($data);
		//$resultado = $this->Servicios_model->listar();
		//echo json_encode($resultado);
	}
	
}
