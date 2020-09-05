<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categorias_Productos extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("Categorias_Productos_model");
		$this->load->library(['ion_auth', 'form_validation']);
		$this->load->model("Permisos_model");		
		$this->controlador = 'categorias_productos';

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('Home', 'refresh');
		}
		else
		{
			$this->empresaId = $this->ion_auth->get_empresa_id();
			$this->userId = $this->ion_auth->get_user_id();		 
			$this->permisos = $this->Permisos_model->VerificarPermisos($this->userId,$this->controlador);
		}	
	}

	public function index(){
		if(array_search('VER', array_column($this->permisos, 'accion'))===false){
			redirect('Home', 'refresh');			
		}
		else{
			$this->load->view("categorias_productos/index");
		}
	}

	public function get_all(){
		if(array_search('VER', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador , 'refresh');			
		}
		else{
			$resultado = $this->Categorias_Productos_model->get_all();
			echo $resultado;
		}
	}

	public function get_all_array(){
		$resultado = $this->Categorias_Productos_model->get_all_array($this->empresaId);		
		echo json_encode($resultado);
	}

	public function mostrar()
	{	
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;
		$data = array(
			"categorias_productos" => $this->Categorias_Productos_model->buscar($this->empresaId,$buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Categorias_Productos_model->buscar($this->empresaId,$buscar)),
			"cantidad" =>$cantidad
			
		);
		echo json_encode($data);
	}

	public function buscar_pagos()
	{
		$json = [];
		$this->load->database();		
		if(!empty($this->input->get("q"))){
			$this->db->like('nombre', $this->input->get("q"));
		}
			$query = $this->db->select('id,nombre as text')
						->limit(10)
						->get("categorias_productos");
			$json = $query->result();		
		
		echo json_encode($json);
	}

	function get_autocomplete(){
        if (isset($_GET['term'])) {
            $result = $this->Categorias_Productos_model->search_autocomplete($this->empresaId,$_GET['term']);
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

	public function ajax_add()
	{
		if(array_search('AGREGAR', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador , 'refresh');			
		}
		else{
			$this->_validate();		
			$data = array(
					'nombre' => $this->input->post('nombre'),
					'descripcion' => $this->input->post('descripcion'),
					'empresaId'=> $this->empresaId
				);

			$insert = $this->Categorias_Productos_model->save($data);
			echo json_encode(array("status" => TRUE));
		}
	}

	public function ajax_edit($id)
	{		
		if(array_search('EDITAR', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador , 'refresh');			
		}
		else{
			$data = $this->Categorias_Productos_model->get_by_id($id);
			echo json_encode($data);
		}
	}

	public function ajax_update()
	{
		if(array_search('EDITAR', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador , 'refresh');			
		}
		else{
			$this->_validate();
			$data = array(
					'nombre' => $this->input->post('nombre'),
					'descripcion' => $this->input->post('descripcion')
				);
			$this->Categorias_Productos_model->update(array('id' => $this->input->post('id')), $data);
			echo json_encode(array("status" => TRUE));
		}
	}


	public function ajax_delete($id)
	{	
		if(array_search('BORRAR', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador , 'refresh');			
		}
		else{
			$this->Categorias_Productos_model->delete_by_id($id);
			echo json_encode(array("status" => TRUE));
		}
	}

	public function ajax_enabled($id)
	{		
		if(array_search('HABILITAR', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador , 'refresh');			
		}
		else{
			$this->Categorias_Productos_model->enabled_by_id($id);
			echo json_encode(array("status" => TRUE));
		}
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

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	public function createXLS() {

		if(array_search('EXPORTAR_ALL', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador , 'refresh');			
		}
		else{

			$this->load->library('excel');
			$empInfo = $this->Categorias_Productos_model->get_all_export($this->empresaId);
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);
			// set Header
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Nombre');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Descripcion');       
			// set Row
			$rowCount = 2;
			foreach ($empInfo as $element) {
				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element['nombre']);
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['descripcion']);           
				$rowCount++;
				}
				
			$archivo = $this->controlador.".xls";
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$archivo.'"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			//Hacemos una salida al navegador con el archivo Excel.
			$objWriter->save('php://output');  
		}
   }




}
