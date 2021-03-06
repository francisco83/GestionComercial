<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cocheras extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("Cocheras_model");
		$this->load->model("Cocheras_Clientes_model");
		$this->load->library(['ion_auth', 'form_validation']);

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}
	}

	public function index(){
		$this->load->view("cocheras/index");
	}

	public function asignar(){
		$this->load->view("cocheras/asignar");
	}

	public function get_all(){
		$resultado = $this->Cocheras_model->get_all();
		echo $resultado;
	}

	public function mostrar()
	{	
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;
		$data = array(
			"Cocheras" => $this->Cocheras_model->buscar($buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Cocheras_model->buscar($buscar)),
			"cantidad" =>$cantidad
			
		);
		echo json_encode($data);
	}

	public function buscar_Cocheras()
	{
		$json = [];
		$this->load->database();		
		if(!empty($this->input->get("q"))){			
			$this->db->or_like('nombre', $this->input->get("q"));
		}
			$query = $this->db->select('id,nombre as text')
						->limit(10)
						->get("cocheras");
			$json = $query->result();		
		
		echo json_encode($json);
	}

	function get_autocomplete(){
        if (isset($_GET['term'])) {
            $result = $this->Cocheras_model->search_autocomplete($_GET['term']);
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
				'comentario' => $this->input->post('comentario'),
				'tipo_cochera_id' => $this->input->post('tipo_cochera_id'),
				'disponible' => $this->input->post('disponible')==null? 0 : 1,
				'habilitado' =>1,
			);

		$insert = $this->Cocheras_model->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function add_asignacion()
	{
		//$this->_validate();		
		$data = array(				
				'cocheraid' => $this->input->post('idCochera'),
				'clienteid' => $this->input->post('clienteid'),				
				'fecha_asignacion' => $this->input->post('fechaasignacion'),
			);

		$insert = $this->Cocheras_Clientes_model->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function update_asignacion()
	{
		//$this->_validate();
		$data = array(
			'clienteId' => $this->input->post('clienteId'),
			'cocheraId' => $this->input->post('cocheraId'),
			'fecha_asignacion' => $this->input->post('fecha_asignacion'),
			);
		$this->Cocheras_Clientes_model->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	

	public function ajax_edit($id)
	{
		$data = $this->Cocheras_model->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
			'nombre' => $this->input->post('nombre'),
			'comentario' => $this->input->post('comentario'),
			'tipo_cochera_id' => $this->input->post('tipo_cochera_id'),
			'disponible' => $this->input->post('disponible')==null? 0 : 1,
			);
		$this->Cocheras_model->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}


	public function ajax_delete($id)
	{	
		$this->Cocheras_model->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_enabled($id)
	{		
		$this->Cocheras_model->enabled_by_id($id);
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
			$data['error_string'][] = 'Debe ingresar un nombre para la cochera.';
			$data['status'] = FALSE;
        }
        
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	public function CocherasXCategorias()
	{		
		$categoria = $_POST['categoria']; 
		$this->load->model("Cocheras_model");		
		$data = $this->Cocheras_model->get_all_by_categoria($categoria);		
		echo json_encode($data);
	}

	public function createXLS() {

	   $this->load->library('excel');

       $empInfo = $this->Cocheras_model->get_all_export();
	          
       $objPHPExcel = new PHPExcel();
       $objPHPExcel->setActiveSheetIndex(0);
       // set Header
       $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Nombre');
       $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Comentario');
	   $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Disponible');
	   $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Categoria');

       // set Row
       $rowCount = 2;
       foreach ($empInfo as $element) {
           $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element['nombre']);
           $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['comentario']);
           $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['Disponible']);
           $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element['categoria']);
           $rowCount++;
        }
        
       $archivo = "Cocheras.xls";
       header('Content-Type: application/vnd.ms-excel');
       header('Content-Disposition: attachment;filename="'.$archivo.'"');
       header('Cache-Control: max-age=0');
       $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
       //Hacemos una salida al navegador con el archivo Excel.
       $objWriter->save('php://output');  
   }

   
}
