<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Clientes extends CI_Controller {
	
	public function __construct(){		
		parent::__construct();
		$this->load->model("Clientes_model");
		$this->load->model("Ventas_model");
		$this->load->model("Permisos_model");
		$this->load->library(['ion_auth', 'form_validation']);
		$this->controlador = 'Clientes';	

		if (!$this->ion_auth->logged_in())
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

	public function index()
	{
		if(array_search('VER', array_column($this->permisos, 'accion'))===false){
			redirect('Home', 'refresh');			
		}
		else{
			$this->load->view($this->controlador."/index",$this->permisos);
		}
	}

	public function ctacte($id)
	{			
		if(array_search('CTACTE', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador , 'refresh');			
		}
		else{
			$data['filas'] = $this->Ventas_model->detalleCtaCteVentaxCliente($this->empresaId,$id);		
			$this->load->view($this->controlador."/ctacte",$data);
		}
	}

	public function get_all(){
		if(array_search('VER', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador , 'refresh');			
		}
		else{
			$resultado = $this->Clientes_model->get_all();
			echo $resultado;
		}
	}

	public function get_all_by_empresaid($empresaId){
		$resultado = $this->Clientes_model->get_all_by_id($empresaId);
		echo $resultado;
	}

	public function mostrar()
	{	
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;
		$data = array(
			"Clientes" => $this->Clientes_model->buscar($this->empresaId,$buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Clientes_model->buscar($this->empresaId,$buscar)),
			"cantidad" =>$cantidad
			
		);
		echo json_encode($data);
	}

	public function buscar_Clientes()
	{
		$json = [];
		$this->load->database();		
		if(!empty($this->input->get("q"))){
			$this->db->like('apellido', $this->input->get("q"));
			$this->db->or_like('nombre', $this->input->get("q"));
		}
			$query = $this->db->select('id,nombre as text')
						->limit(10)
						->get("Clientes");
			$json = $query->result();		
		
		echo json_encode($json);
	}

	function get_autocomplete(){
        if (isset($_GET['term'])) {
            $result = $this->Clientes_model->search_autocomplete($this->empresaId,$_GET['term']);
            if (count($result) > 0) {
			foreach ($result as $row)	
			{
				$data[] = array(
					'id' => $row->id,
					'value'=> $row->apellido.' '.$row->nombre.'  -  '.$row->dni 
				);
			}				
                echo json_encode($data);
            }
        }
	}

	public function ajax_add()
	{
		if(array_search('NUEVO', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador, 'refresh');			
		}
		else{
			$this->_validate();		
			$data = array(
					'nombre' => $this->input->post('nombre'),
					'apellido' => $this->input->post('apellido'),
					'dni' => $this->input->post('dni'),
					'email' => $this->input->post('email'),				
					'telefono' => $this->input->post('telefono'),
					'provinciaId' => $this->input->post('provinciaId'),
					'direccion' => $this->input->post('direccion'),
					'habilitado' => 1,
					'empresaId' =>$this->empresaId,
				);

			$insert = $this->Clientes_model->save($data);
			echo json_encode(array("status" => TRUE));
		}
	}

	public function ajax_edit($id)
	{
		if(array_search('EDITAR', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador, 'refresh');			
		}
		else{
			$data = $this->Clientes_model->get_by_id($id);
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
				'apellido' => $this->input->post('apellido'),
				'dni' => $this->input->post('dni'),
				'email' => $this->input->post('email'),				
				'telefono' => $this->input->post('telefono'),
				'provinciaId' => $this->input->post('provinciaId'),
				'direccion' => $this->input->post('direccion'),
				);
			$this->Clientes_model->update(array('id' => $this->input->post('id')), $data);
			echo json_encode(array("status" => TRUE));
		}
	}


	public function ajax_delete($id)
	{	
		if(array_search('BORRAR', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador , 'refresh');			
		}
		else{
			$this->Clientes_model->delete_by_id($id);
			echo json_encode(array("status" => TRUE));
		}
	}

	public function ajax_enabled($id)
	{	
		if(array_search('HABILITAR', array_column($this->permisos, 'accion'))===false){
			redirect($this->controlador, 'refresh');			
		}
		else{	
			$this->Clientes_model->enabled_by_id($id);
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
			redirect($this->controlador, 'refresh');			
		}
		else
		{
			$this->load->library('excel');
			$empInfo = $this->Clientes_model->get_all_export($this->empresaId);
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);
			// set Header
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Nombre');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Apellido');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'DNI');  
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Email');  
			$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Teléfono');  
			$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Provincia');  
			$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Dirección');  
			// set Row
			$rowCount = 2;
			foreach ($empInfo as $element) {
				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element['nombre']);
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['apellido']);
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['dni']);
				$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element['email']);
				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element['telefono']);
				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $element['nombre_provincia']);
				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $element['direccion']);
				$rowCount++;
				}
				
			$archivo = $this->controlador.".xls";
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$archivo.'"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');       
			$objWriter->save('php://output');  
		}
   }




}
