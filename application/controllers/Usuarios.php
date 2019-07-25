<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//header('Access-Control-Allow-Origin: *');

class Usuarios extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("Usuarios_model");
		$this->load->library(['ion_auth', 'form_validation']);
	}

	public function index(){
		$this->load->view("usuarios/index");
	}

	public function agregar(){
        $this->load->view("partial/encabezado");
		$this->load->view("usuarios/agregar");
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
			"usuarios" => $this->Usuarios_model->buscar($buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Usuarios_model->buscar($buscar)),
			"cantidad" =>$cantidad
			
		);
		echo json_encode($data);
	}

	public function guardar(){
        $resultado = $this->Usuarios_model->nuevo(
                $this->input->post("nombres"),
                $this->input->post("apellidos"),
                $this->input->post("dni"),
                $this->input->post("email"),
                $this->input->post("celular")
            );
        if($resultado){
            $mensaje = "Usuario guardado correctamente";
            $clase = "success";
        }else{
            $mensaje = "Error al guardar el Usuario";
            $clase = "danger";
        }
        $this->session->set_flashdata(array(
            "mensaje" => $mensaje,
            "clase" => $clase,
        ));
        redirect("Usuarios/agregar");
	}
	
	public function guardarCambios(){
        $resultado = $this->Usuarios_model->guardarCambios(
			$this->input->post("id"),
			$this->input->post("nombres"),
			$this->input->post("apellidos"),
			$this->input->post("dni"),
			$this->input->post("email"),
			$this->input->post("celular")
        );
        if($resultado){
            $mensaje = "Usuario actualizado correctamente";
            $clase = "success";
        }else{
            $mensaje = "Error al actualizar el usuario";
            $clase = "danger";
        }
        $this->session->set_flashdata(array(
            "mensaje" => $mensaje,
            "clase" => $clase,
        ));
        redirect("Usuarios");
	}
	
	public function editar($id){
        $usuarios = $this->Usuarios_model->uno($id);
        if(null === $usuarios){
            $this->session->set_flashdata(array(
                "mensaje" => "El usuario que quieres editar no existe",
                "clase" => "danger",
            ));
            redirect("Usuarios/");
        }
        $this->load->view("partial/encabezado");
        $this->load->view("Usuarios/editar", array("usuarios" => $usuarios));
        //$this->load->view("pie");
    }

    public function eliminar($id){
        $resultado = $this->Usuarios_model->eliminar($id);
        if($resultado){
            $mensaje = "Usuario eliminado correctamente";
            $clase = "success";
        }else{
            $mensaje = "Error al eliminar el usuario";
            $clase = "danger";
        }
        $this->session->set_flashdata(array(
            "mensaje" => $mensaje,
            "clase" => $clase,
        ));
        redirect("Usuarios");
	}
	
}
