<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tipos_Servicios extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("Tipos_Servicios_model");
		$this->load->library(['ion_auth', 'form_validation']);
		$this->load->model("Permisos_model");
		$this->controlador = 'tipos_Servicios';	

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
			$this->load->view($this->controlador."/index",$this->permisos);
		}
	}

	public function get_all(){
		if(array_search('VER', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador, 'refresh');	
		}
		else{
			$resultado = $this->Tipos_Servicios_model->get_all();
			echo $resultado;
		}
	}

	public function mostrar()
	{	
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;
		$data = array(
			"tipos_servicios" => $this->Tipos_Servicios_model->buscar($this->empresaId,$buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Tipos_Servicios_model->buscar($this->empresaId,$buscar)),
			"cantidad" =>$cantidad
			
		);
		echo json_encode($data);
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
						->get("tipos_servicios");
			$json = $query->result();		
		
		echo json_encode($json);
	}

	function get_autocomplete(){
        if (isset($_GET['term'])) {
            $result = $this->Tipos_Servicios_model->search_autocomplete($this->empresaId,$_GET['term']);
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
		if(array_search('AGREGAR', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador, 'refresh');	
		}
		else{
			$this->_validate();		
			$data = array(
					'nombre' => $this->input->post('nombre'),
					'descripcion' => $this->input->post('descripcion'),
					'precio' => $this->input->post('precio'),
					'habilitado' =>1,
					'empresaId' => $this->empresaId,
				);

			$insert = $this->Tipos_Servicios_model->save($data);
			echo json_encode(array("status" => TRUE));
		}
	}

	public function ajax_edit($id)
	{
		if(array_search('EDITAR', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador, 'refresh');	
		}
		else{		
			$data = $this->Tipos_Servicios_model->get_by_id($id);
			echo json_encode($data);
		}
	}

	public function ajax_update()
	{
		if(array_search('EDITAR', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador, 'refresh');	
		}
		else{	
			$this->_validate();
			$data = array(
					'nombre' => $this->input->post('nombre'),
					'descripcion' => $this->input->post('descripcion'),
					'precio' => $this->input->post('precio'),
				);
			$this->Tipos_Servicios_model->update(array('id' => $this->input->post('id')), $data);
			echo json_encode(array("status" => TRUE));
		}
	}


	public function ajax_delete($id)
	{	
		if(array_search('BORRAR', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador, 'refresh');	
		}
		else{	
			$this->Tipos_Servicios_model->delete_by_id($id);
			echo json_encode(array("status" => TRUE));
		}
	}

	public function ajax_enabled($id)
	{		
		if(array_search('HABILITAR', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador, 'refresh');	
		}
		else{	
			$this->Tipos_Servicios_model->enabled_by_id($id);
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

	public function verdetallehistorico ()
	{
		if(array_search('VERHISTORICO', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador, 'refresh');	
		}
		else{	
			$IdServicio = $this->input->post("IdServicio");
			$this->load->model("Tipos_Servicios_model");		
			$data = $this->Tipos_Servicios_model->buscarDetalleHistorico($this->empresaId,$IdServicio);		
			echo json_encode($data);	
		}

	}

	public function createXLS() {
		if(array_search('VERHISTORICO', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador, 'refresh');	
		}
		else{	
			$this->load->library('excel');
			$empInfo = $this->Tipos_Servicios_model->get_all_export($this->empresaId);
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);
			// set Header
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Nombre');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Descripcion');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Precio');  
			// set Row
			$rowCount = 2;
			foreach ($empInfo as $element) {
				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element['nombre']);
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['descripcion']);
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['precio']);
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
