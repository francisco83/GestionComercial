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

	// public function agregar(){
    //     $this->load->view("partial/encabezado");
	// 	$this->load->view("servicios/agregar");
	// 	// load pagination library
	// 	$this->load->library('pagination');
    //     //$this->load->view("pie");
	// }
	
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
    }

    // public function eliminar($id){
    //     $resultado = $this->Servicios_model->eliminar($id);
    //     if($resultado){
    //         $mensaje = "Servicio eliminado correctamente";
    //         $clase = "success";
    //     }else{
    //         $mensaje = "Error al eliminar el servicio";
    //         $clase = "danger";
    //     }
    //     $this->session->set_flashdata(array(
    //         "mensaje" => $mensaje,
    //         "clase" => $clase,
    //     ));
    //     redirect("Servicios");
	// }

	public function listar(){
		$resultado = $this->Servicios_model->listar();
		echo $resultado;
	}

	public function buscar_servicios()
	{
		$json = [];
		$this->load->database();		
		if(!empty($this->input->get("q"))){
			$this->db->like('nombre', $this->input->get("q"));
		}
			$query = $this->db->select('id,nombre as text')
						->limit(10)
						->get("servicios");
			$json = $query->result();		
		
		echo json_encode($json);
	}

	function get_autocomplete(){
        if (isset($_GET['term'])) {
            $result = $this->Servicios_model->search_blog($_GET['term']);
            if (count($result) > 0) {
			foreach ($result as $row)	
			{
				$data[] = array(
					'id' => $row->id,
					'value'=> $row->nombre,
					'precio'=>$row->precio
				);
			}				
                echo json_encode($data);
            }
        }
	}

	public function ajax_add()
	{
		$this->_validate();		
		$data = array(
				'nombre' => $this->input->post('nombre'),
				'descripcion' => $this->input->post('descripcion'),
				'precio' => $this->input->post('precio'),
			);

		$insert = $this->Servicios_model->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_edit($id)
	{
		$data = $this->Servicios_model->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'nombre' => $this->input->post('nombre'),
				'descripcion' => $this->input->post('descripcion'),
				'precio' => $this->input->post('precio'),
			);
		$this->Servicios_model->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}


	public function ajax_delete($id)
	{
		//delete file
		$Servicios_model = $this->Servicios_model->get_by_id($id);		
		$this->Servicios_model->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('nombre') == '')
		{
			$data['inputerror'][] = 'nombre';
			$data['error_string'][] = 'Debe ingresar un nombre.';
			$data['status'] = FALSE;
		}

		if($this->input->post('precio') == '')
		{
			$data['inputerror'][] = 'precio';
			$data['error_string'][] = 'Debe ingresar un precio.';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}



}
