<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compras extends CI_Controller {

	var $empresaId;		

	public function __construct(){
		parent::__construct();
		$this->load->model("Compras_model");
		$this->load->model("Compras_detalle_model");
		$this->load->model("productos_model");
		$this->load->model("Pagos_model");
		$this->load->library(['ion_auth', 'form_validation']);

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}
		else
		{
			$this->empresaId = $this->ion_auth->get_empresa_id();
		}	
	}

	public function index(){
		$this->load->view("Compras/index");
	}

	public function ver(){	
		$this->load->view("Compras/ver");
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('proveedorid') == '')
		{
			$data['inputerror'][] = 'nombre';
			$data['error_string'][] = 'Debe seleccionar un proveedor para realizar la compra.';
			$data['status'] = FALSE;
		}

		if($this->input->post('fechahoy') == '')
		{
			$data['inputerror'][] = 'Fecha';
			$data['error_string'][] = 'Debe ingresar una fecha para la compra.';
			$data['status'] = FALSE;
		}

		if($this->input->post('IdProducto') == '')
		{
			$data['inputerror'][] = 'Productos';
			$data['error_string'][] = 'No hay productos cargados.';
			$data['status'] = FALSE;
		}

		// if($this->input->post('monedaMonto') == 0)
		// {
		// 	$data['inputerror'][] = 'Moneda';
		// 	$data['error_string'][] = 'El monto del pago no puede ser cero.';
		// 	$data['status'] = FALSE;
		// }

		// if($this->input->post('Cantidad') <= 0)
		// {
		// 	$data['inputerror'][] = 'Cantidad';
		// 	$data['error_string'][] = 'La cantidad no puede ser negativa o igual a cero.';
		// 	$data['status'] = FALSE;
		// }

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	public function insertar(){
		$this->_validate();
		$proveedorId = $_POST['proveedorid']; 
		$fecha = $_POST['fechahoy'];
		$IdProducto = $_POST['IdProducto'];
		$CodigoProducto = $_POST['CodigoProducto'];
		$PrecioCompra = $_POST['PrecioCompra'];
		$Cantidad = $_POST['Cantidad'];
		$moneda = $_POST['moneda'];
		$monedaMonto = $_POST['monedaMonto'];
		$total=$_POST['totalCompraFinal'];
		$vuelto=$_POST['totalVueltoFinal'];

		//$user = $this->ion_auth->logged_in();
				
		$empleadoId =1;
		$sucursalId=0;

		$resultado = $this->Compras_model->guardar($this->empresaId,$fecha,$total,$proveedorId,$empleadoId,$sucursalId,$IdProducto,$PrecioCompra,$Cantidad,$moneda,$monedaMonto,$total,$vuelto);
		
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;


		if($resultado){
			$data['status'] = TRUE;	
        }else{
			$data['status'] = FALSE;
        }

		echo json_encode($data);

	}


	public function mostrarXproveedor()
	{	
		$proveedorId = $this->input->post("proveedorId");
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;
		$data = array(
			"cli_compras" => $this->Compras_model->buscarXproveedor($this->empresaId,$proveedorId,$buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Compras_model->buscarXproveedor($this->empresaId,$proveedorId,$buscar)),
			"cantidad" =>$cantidad
			
		);
		echo json_encode($data);
	}

	public function mostrarDetalleXproveedor()
	{	
		$compraId = $this->input->post("compraId");
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;
		$data = array(
			"compras_detalle" => $this->Compras_detalle_model->buscarDetalleXproveedor($compraId,$buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Compras_detalle_model->buscarDetalleXproveedor($compraId,$buscar)),
			"cantidad" =>$cantidad
			
		);
		echo json_encode($data);
	}

	public function verDetallePagos()
	{
		$Idcompra = $this->input->post("Idcompra");
		$this->load->model("Compras_detalle_model");		
		$data = $this->Compras_detalle_model->buscarDetallePagoImprimir($Idcompra);		
		echo json_encode($data);
	}

	public function comprasXFechas()
	{		
		$fecha_desde = $_POST['fecha_desde']; 
		$fecha_hasta = $_POST['fecha_hasta'];
		$this->load->model("Compras_model");		
		$data = $this->Compras_model->comprasXFechas($this->empresaId,$fecha_desde,$fecha_hasta);		
		echo json_encode($data);
	}

	public function comprasProductosXFechas()
	{		
		$fecha_desde = $_POST['fecha_desde']; 
		$fecha_hasta = $_POST['fecha_hasta'];
		$this->load->model("Compras_model");		
		$data = $this->Compras_model->comprasProductosXFechas($this->empresaId,$fecha_desde,$fecha_hasta);		
		echo json_encode($data);
	}

	public function ajax_delete($id)
	{	
		$this->Compras_model->delete_by_masterid($id);		
		echo json_encode(array("status" => TRUE));
	}

	public function createXLS() {

		$this->load->library('excel');
		$fecha_desde = $_GET['fecha_desde']; 
		$fecha_hasta = $_GET['fecha_hasta'];

		$empInfo = $this->Compras_model->get_all_export_by_date($this->empresaId,$fecha_desde,$fecha_hasta);
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		// set Header
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Fecha');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Apellido');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Nombre');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Total compra');  
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Total Pago');  
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Total Vuelto');  
		// set Row
		$rowCount = 2;
		foreach ($empInfo as $element) {
			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element['fecha']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['apellido']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['nombre']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element['total']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element['monto']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $element['vuelto']);
			$rowCount++;
		 }
		 
		$archivo = "Compras.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$archivo.'"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		//Hacemos una salida al navegador con el archivo Excel.
		$objWriter->save('php://output');  
	}
 




	public function createXLScomprasproductos() {

		$this->load->library('excel');
		$fecha_desde = $_GET['fecha_desde']; 
		$fecha_hasta = $_GET['fecha_hasta'];

		$empInfo = $this->Compras_model->get_all_comprasproductos_export_by_date($this->empresaId,$fecha_desde,$fecha_hasta);
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		// set Header
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Fecha');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Codigo Producto');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Nombre');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Cantidad');  
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Precio compra');  
		 
		// set Row
		$rowCount = 2;

		foreach ($empInfo as $element) {
			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element['fecha']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['codigoproducto']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['nombre']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element['cantidad']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element['preciocompra']);						
			$rowCount++;
		 }
		 
		$archivo = "ComprasProductos.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$archivo.'"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		//Hacemos una salida al navegador con el archivo Excel.
		$objWriter->save('php://output');  
	}
 

}
