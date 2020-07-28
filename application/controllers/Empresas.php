<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empresas extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("Empresas_model");
		$this->load->library(['ion_auth', 'form_validation']);

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}
	}

	public function index(){
		$this->load->view("Empresas/index");
	}

	public function get_all(){
		$resultado = $this->Empresas_model->get_all();
		echo $resultado;
	}

	public function mostrar()
	{	
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;
		$data = array(
			"Empresas" => $this->Empresas_model->buscar($buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Empresas_model->buscar($buscar)),
			"cantidad" =>$cantidad
			
		);
		echo json_encode($data);
	}

	public function buscar_Empresas()
	{
		$json = [];
		$this->load->database();		
		if(!empty($this->input->get("q"))){
			$this->db->like('nombre', $this->input->get("q"));
		}
			$query = $this->db->select('id,nombre as text')
						->limit(10)
						->get("Empresas");
			$json = $query->result();		
		
		echo json_encode($json);
	}

	function get_autocomplete(){
        if (isset($_GET['term'])) {
            $result = $this->Empresas_model->search_autocomplete($_GET['term']);
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
				'cuit' => $this->input->post('cuit'),
				'ingresosbrutos' => $this->input->post('ingresosbrutos'),
				'direccion' => $this->input->post('direccion'),
				'telefono' => $this->input->post('telefono'),
				'email' => $this->input->post('email'),
				'habilitado' => 1,
			);

		$insert = $this->Empresas_model->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_edit($id)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$data = $this->Empresas_model->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}
		$this->_validate();
		$data = array(
				'nombre' => $this->input->post('nombre'),
				'cuit' => $this->input->post('cuit'),
				'ingresosbrutos' => $this->input->post('ingresosbrutos'),
				'direccion' => $this->input->post('direccion'),
				'telefono' => $this->input->post('telefono'),
				'email' => $this->input->post('email'),
			);
		$this->Empresas_model->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}


	public function ajax_delete($id)
	{	
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$this->Empresas_model->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_enabled($id)
	{		
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$this->Empresas_model->enabled_by_id($id);
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

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}
		
       $this->load->library('excel');
       $empInfo = $this->Empresas_model->get_all_export();
       $objPHPExcel = new PHPExcel();
       $objPHPExcel->setActiveSheetIndex(0);
       // set Header
       $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Nombre');
       $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Cuit');
       $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Ingresos Brutos');  
       $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Dirección');  
       $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Teléfono');  
       $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Email');  
       // set Row
       $rowCount = 2;
       foreach ($empInfo as $element) {
           $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element['nombre']);
           $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['cuit']);
           $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['ingresosbrutos']);
           $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element['direccion']);
           $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element['telefono']);
           $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $element['email']);
           $rowCount++;
        }
        
       $archivo = "Empresas.xls";
       header('Content-Type: application/vnd.ms-excel');
       header('Content-Disposition: attachment;filename="'.$archivo.'"');
       header('Cache-Control: max-age=0');
       $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
       //Hacemos una salida al navegador con el archivo Excel.
       $objWriter->save('php://output');  
   }




}
