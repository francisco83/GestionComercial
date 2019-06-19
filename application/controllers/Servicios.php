<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//header('Access-Control-Allow-Origin: *');

class Servicios extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("Servicios_model");
	}

	public function index(){
		$this->load->view("servicios/index");
	}

	public function agregar(){
        $this->load->view("partial/encabezado");
		$this->load->view("servicios/agregar");
		// load pagination library
		$this->load->library('pagination');
        //$this->load->view("pie");
	}
	
	public function mostrar()
	{	
		//valor a Buscar
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;
		$data = array(
			"servicios" => $this->Servicios_model->buscar($buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Servicios_model->buscar($buscar)),
			"cantidad" =>$cantidad
			
		);
		echo json_encode($data);
	}

	public function guardar(){
        $resultado = $this->Servicios_model->nuevo(
                $this->input->post("nombre"),
                $this->input->post("descripcion"),
                $this->input->post("precio")
            );
        if($resultado){
            $mensaje = "Servicio guardado correctamente";
            $clase = "success";
        }else{
            $mensaje = "Error al guardar el Servicio";
            $clase = "danger";
        }
        $this->session->set_flashdata(array(
            "mensaje" => $mensaje,
            "clase" => $clase,
        ));
        redirect("Servicios/agregar");
	}
	
	public function guardarCambios(){
        $resultado = $this->Servicios_model->guardarCambios(
			$this->input->post("id"),
			$this->input->post("nombre"),
			$this->input->post("descripcion"),
			$this->input->post("precio")
        );
        if($resultado){
            $mensaje = "Servicio actualizado correctamente";
            $clase = "success";
        }else{
            $mensaje = "Error al actualizar el servicio";
            $clase = "danger";
        }
        $this->session->set_flashdata(array(
            "mensaje" => $mensaje,
            "clase" => $clase
        ));
        redirect("Servicios");
	}
	
	public function editar($id){
        $servicios = $this->Servicios_model->uno($id);
        if(null === $servicios){
            $this->session->set_flashdata(array(
                "mensaje" => "El servicio que quieres editar no existe",
                "clase" => "danger",
            ));
            redirect("Servicios/");
        }
        $this->load->view("partial/encabezado");
        $this->load->view("Servicios/editar", array("servicios" => $servicios));
        //$this->load->view("pie");
    }

    public function eliminar($id){
        $resultado = $this->Servicios_model->eliminar($id);
        if($resultado){
            $mensaje = "Servicio eliminado correctamente";
            $clase = "success";
        }else{
            $mensaje = "Error al eliminar el servicio";
            $clase = "danger";
        }
        $this->session->set_flashdata(array(
            "mensaje" => $mensaje,
            "clase" => $clase,
        ));
        redirect("Servicios");
	}

	public function listar(){
		$resultado = $this->Servicios_model->listar();
		echo $resultado;
	}
	
}
