<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tipos_Pagos extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("Tipos_Pagos_model");
		$this->load->library(['ion_auth', 'form_validation']);

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('Home', 'refresh');
		}
	}

	public function index(){
		$this->load->view("tipos_pagos/index");
	}

	public function get_all(){
		$resultado = $this->Tipos_Pagos_model->get_all();
		echo $resultado;
	}

	public function mostrar()
	{	
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;
		$data = array(
			"tipos_pagos" => $this->Tipos_Pagos_model->buscar($buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Tipos_Pagos_model->buscar($buscar)),
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
						->get("tipos_pagos");
			$json = $query->result();		
		
		echo json_encode($json);
	}

	function get_autocomplete(){
        if (isset($_GET['term'])) {
            $result = $this->Tipos_Pagos_model->search_autocomplete($_GET['term']);
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
		$this->_validate();		
		$data = array(
				'nombre' => $this->input->post('nombre'),
				'descripcion' => $this->input->post('descripcion'),
				'habilitado' =>1,
			);

		$insert = $this->Tipos_Pagos_model->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_edit($id)
	{
		$data = $this->Tipos_Pagos_model->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'nombre' => $this->input->post('nombre'),
				'descripcion' => $this->input->post('descripcion')
			);
		$this->Tipos_Pagos_model->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}


	public function ajax_delete($id)
	{	
		$this->Tipos_Pagos_model->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_enabled($id)
	{		
		$this->Tipos_Pagos_model->enabled_by_id($id);
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

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	public function createXLS() {

       $this->load->library('excel');
       $empInfo = $this->Tipos_Pagos_model->get_all_export();
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
        
       $archivo = "Tipos_Pagos.xls";
       header('Content-Type: application/vnd.ms-excel');
       header('Content-Disposition: attachment;filename="'.$archivo.'"');
       header('Cache-Control: max-age=0');
       $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
       //Hacemos una salida al navegador con el archivo Excel.
       $objWriter->save('php://output');  
   }




}
