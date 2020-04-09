<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("Productos_model");
		$this->load->library(['ion_auth', 'form_validation']);

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}
	}

	public function index(){
		$this->load->view("productos/index");
	}

	public function get_all(){
		$resultado = $this->Productos_model->get_all();
		echo $resultado;
	}

	public function mostrar()
	{	
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;
		$data = array(
			"Productos" => $this->Productos_model->buscar($buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Productos_model->buscar($buscar)),
			"cantidad" =>$cantidad
			
		);
		echo json_encode($data);
	}

	public function buscar_Productos()
	{
		$json = [];
		$this->load->database();		
		if(!empty($this->input->get("q"))){
			$this->db->like('codigo', $this->input->get("q"));
			$this->db->or_like('nombre', $this->input->get("q"));
		}
			$query = $this->db->select('id,nombre as text')
						->limit(10)
						->get("productos");
			$json = $query->result();		
		
		echo json_encode($json);
	}

	function get_autocomplete(){
        if (isset($_GET['term'])) {
            $result = $this->Productos_model->search_autocomplete($_GET['term']);
            if (count($result) > 0) {
			foreach ($result as $row)	
			{
				$data[] = array(
					'id' => $row->id,
					'value'=> $row->nombre,
					'precioVenta'=> $row->precioVenta,
					'codigoProducto'=>$row->codigo,
					'existencia'=>$row->existencia
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
				'codigo' => $this->input->post('codigo'),
				'nombre' => $this->input->post('nombre'),
				'descripcion' => $this->input->post('descripcion'),
				'tipo_categoria_id' => $this->input->post('tipo_categoria_id'),
				'precioVenta' => $this->input->post('precioVenta'),
				'precioCompra' => $this->input->post('precioCompra'),				
				'existencia' => $this->input->post('existencia'),
			);

		$insert = $this->Productos_model->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_edit($id)
	{
		$data = $this->Productos_model->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
			'codigo' => $this->input->post('codigo'),
			'nombre' => $this->input->post('nombre'),
			'descripcion' => $this->input->post('descripcion'),
			'tipo_categoria_id' => $this->input->post('tipo_categoria_id'),
			'precioVenta' => $this->input->post('precioVenta'),
			'precioCompra' => $this->input->post('precioCompra'),				
			'existencia' => $this->input->post('existencia'),
			);
		$this->Productos_model->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}


	public function ajax_delete($id)
	{	
		$this->Productos_model->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_enabled($id)
	{		
		$this->Productos_model->enabled_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('codigo') == '')
		{
			$data['inputerror'][] = 'codigo';
			$data['error_string'][] = 'Debe ingresar un código para el producto.';
			$data['status'] = FALSE;
        }
        
        
		if($this->input->post('nombre') == '')
		{
			$data['inputerror'][] = 'nombre';
			$data['error_string'][] = 'Debe ingresar un nombre para el producto.';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	public function productosXCategorias()
	{		
		$categoria = $_POST['categoria']; 
		$this->load->model("Productos_model");		
		$data = $this->Productos_model->get_all_by_categoria($categoria);		
		echo json_encode($data);
	}

	public function createXLS() {

	   $this->load->library('excel');

       $empInfo = $this->Productos_model->get_all_export();
	          
       $objPHPExcel = new PHPExcel();
       $objPHPExcel->setActiveSheetIndex(0);
       // set Header
       $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Codigo');
       $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Nombre');
	   $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Descripcion');
	   $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Categoria');
       $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Precio Venta');  
       $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Precio Compra');  
       $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Existencia');  
       // set Row
       $rowCount = 2;
       foreach ($empInfo as $element) {
           $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element['codigo']);
           $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['nombre']);
           $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['descripcion']);
           $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element['categoria']);
           $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element['precioVenta']);
           $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $element['precioCompra']);
           $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $element['existencia']);
           $rowCount++;
        }
        
       $archivo = "Productos.xls";
       header('Content-Type: application/vnd.ms-excel');
       header('Content-Disposition: attachment;filename="'.$archivo.'"');
       header('Cache-Control: max-age=0');
       $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
       //Hacemos una salida al navegador con el archivo Excel.
       $objWriter->save('php://output');  
   }

   public function createXLSxCategoria() {

	$this->load->library('excel');
	
	$categoria = $_GET['categoria'];   
	
	$empInfo = $this->Productos_model->get_all_by_categoria($categoria);	
	
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	// set Header
	$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Codigo');
	$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Nombre');
	$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Descripcion');
	$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Categoria');
	$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Precio Venta');  
	$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Precio Compra');  
	$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Existencia');  
	// set Row
	$rowCount = 2;
	foreach ($empInfo as $element) {
		$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element['codigo']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['nombre']);
		$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['descripcion']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element['categoria']);
		$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element['precioVenta']);
		$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $element['precioCompra']);
		$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $element['existencia']);
		$rowCount++;
	 }
	 
	$archivo = "Productos.xls";
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$archivo.'"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	//Hacemos una salida al navegador con el archivo Excel.
	$objWriter->save('php://output');  
}


}
