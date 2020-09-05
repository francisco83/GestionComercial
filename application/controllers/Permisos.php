<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permisos extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("Permisos_model");
		$this->load->library(['ion_auth', 'form_validation']);

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('Home', 'refresh');
		}
	}

	public function index(){
		$this->load->view("Permisos/index");
	}

	public function get_all(){
		$resultado = $this->Permisos_model->get_all();
		echo $resultado;
	}

	public function get_all_array(){
		$resultado = $this->Permisos_model->get_all_array();		
		echo json_encode($resultado);
	}
	public function mostrar()
	{	
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;
		$data = array(
			"Permisos" => $this->Permisos_model->buscar($buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Permisos_model->buscar($buscar)),
			"cantidad" =>$cantidad
			
		);
		echo json_encode($data);
	}

	function get_autocomplete(){
        if (isset($_GET['term'])) {
            $result = $this->Permisos_model->search_autocomplete($_GET['term']);
            if (count($result) > 0) {
			foreach ($result as $row)	
			{
				$data[] = array(
					'id' => $row->id,
					'value'=> $row->controlador
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
				'controlador' => $this->input->post('controlador'),
				'accion' => $this->input->post('accion'),
				'descripcion' => $this->input->post('descripcion')
			);

		$insert = $this->Permisos_model->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_edit($id)
	{
		$data = $this->Permisos_model->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'controlador' => $this->input->post('controlador'),
				'accion' => $this->input->post('accion'),
				'descripcion' => $this->input->post('descripcion'),
			);
		$this->Permisos_model->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}


	public function ajax_delete($id)
	{	
		$this->Permisos_model->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_enabled($id)
	{		
		$this->Permisos_model->enabled_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('controlador') == '')
		{
			$data['inputerror'][] = 'controlador';
			$data['error_string'][] = 'Debe ingresar un nombre de controlador.';
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
       $empInfo = $this->Permisos_model->get_all_export();
       $objPHPExcel = new PHPExcel();
       $objPHPExcel->setActiveSheetIndex(0);
       // set Header
       $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'controlador');
       $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'accion');       
       $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'descripcion');       
       // set Row
       $rowCount = 2;
       foreach ($empInfo as $element) {
           $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element['Nombre Controlador']);
           $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['Acción']);           
           $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['Descripción']);           
           $rowCount++;
        }
        
       $archivo = "Permisos.xls";
       header('Content-Type: application/vnd.ms-excel');
       header('Content-Disposition: attachment;filename="'.$archivo.'"');
       header('Cache-Control: max-age=0');
       $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
       //Hacemos una salida al navegador con el archivo Excel.
       $objWriter->save('php://output');  
   }

   public function Permisos_Grupos_sinAsignar()
   {
	   $grupoId = $this->input->post("grupoId");
	   $this->load->model("Permisos_model");		
	   $data = $this->Permisos_model->Permisos_Grupos_sinAsignar($grupoId);		
	   echo json_encode($data);	
   }

   public function Permisos_Grupos_Asignados()
   {
	   $grupoId = $this->input->post("grupoId");
	   $this->load->model("Permisos_model");		
	   $data = $this->Permisos_model->Permisos_Grupos_Asignados($grupoId);		
	   echo json_encode($data);	
   }

   public function AddGruposPermisos()
   {
		$grupoId = $this->input->post("grupoId");
		$permisos_ = $this->input->post("permisosId");
		$permisosId = explode(",", $permisos_);

		$this->load->model("Permisos_model");		
		$data = $this->Permisos_model->Insertar_grupos_permisos($grupoId,$permisosId);		
		echo json_encode(array("status" => TRUE));	
   }

   public function VerificarPermisos()
   {
		$userId = $this->input->post("userId");
		$controlador = $this->input->post("controlador");				
		$this->load->model("Permisos_model");		

		$data = $this->Permisos_model->VerificarPermisos($userId,$controlador);		
		echo json_encode($data);	
   }
   

}
