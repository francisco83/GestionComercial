<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Clientes extends CI_Controller {
	

	public function __construct(){
		
		parent::__construct();
		$this->load->model("Clientes_model");		
	}

	public function index(){		
		$this->load->view("clientes/index");
	}

	public function mostrar()
	{	
		//valor a Buscar
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;
		$data = array(
			"clientes" => $this->Clientes_model->buscar($buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Clientes_model->buscar($buscar)),
			"cantidad" =>$cantidad
			
		);
		echo json_encode($data);
	}

	public function guardar(){
        $resultado = $this->Clientes_model->nuevo(
				$this->input->post("codigo"),
				$this->input->post("nombres"),
                $this->input->post("apellidos"),
                $this->input->post("dni"),
                $this->input->post("email"),
                $this->input->post("celular")
            );
        if($resultado){
            $mensaje = "Cliente guardado correctamente";
            $clase = "success";
        }else{
            $mensaje = "Error al guardar el Ciente";
            $clase = "danger";
        }
        $this->session->set_flashdata(array(
            "mensaje" => $mensaje,
            "clase" => $clase,
        ));
        redirect("Clientes/agregar");
	}
	
	public function guardarCambios(){
        $resultado = $this->Clientes_model->guardarCambios(
			$this->input->post("id"),
			$this->input->post("codigo"),
			$this->input->post("nombres"),
			$this->input->post("apellidos"),
			$this->input->post("dni"),
			$this->input->post("email"),
			$this->input->post("celular")
        );
        if($resultado){
            $mensaje = "Cliente actualizado correctamente";
            $clase = "success";
        }else{
            $mensaje = "Error al actualizar el Cliente";
            $clase = "danger";
        }
        $this->session->set_flashdata(array(
            "mensaje" => $mensaje,
            "clase" => $clase,
        ));
        redirect("Clientes");
	}
	
	public function editar($id){
        $clientes = $this->Clientes_model->uno($id);
        if(null === $clientes){
            $this->session->set_flashdata(array(
                "mensaje" => "El cliente que quieres editar no existe",
                "clase" => "danger",
            ));
            redirect("Clientes/");
        }
        $this->load->view("partial/encabezado");
        $this->load->view("Clientes/editar", array("clientes" => $clientes));
        //$this->load->view("pie");
    }

    public function eliminar($id){
        $resultado = $this->Clientes_model->eliminar($id);
        if($resultado){
            $mensaje = "Cliente eliminado correctamente";
            $clase = "success";
        }else{
            $mensaje = "Error al eliminar el cliente";
            $clase = "danger";
        }
        $this->session->set_flashdata(array(
            "mensaje" => $mensaje,
            "clase" => $clase,
        ));
        redirect("Clientes");
	}
}
