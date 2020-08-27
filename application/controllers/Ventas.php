<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("Ventas_model");
		$this->load->model("Ventas_detalle_model");
		$this->load->model("productos_model");
		$this->load->model("Pagos_model");
		$this->load->library(['ion_auth', 'form_validation']);

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}
	}

	public function index(){
		$this->load->view("Ventas/index");
	}

	public function ver(){	
		$this->load->view("Ventas/ver");
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('clienteid') == '')
		{
			$data['inputerror'][] = 'nombre';
			$data['error_string'][] = 'Debe seleccionar un cliente para realizar la venta.';
			$data['status'] = FALSE;
		}

		if($this->input->post('fechahoy') == '')
		{
			$data['inputerror'][] = 'Fecha';
			$data['error_string'][] = 'Debe ingresar una fecha para la venta.';
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
		$clienteId = $_POST['clienteid']; 
		$fecha = $_POST['fechahoy'];
		$IdProducto = $_POST['IdProducto'];
		$CodigoProducto = $_POST['CodigoProducto'];
		$PrecioVenta = $_POST['PrecioVenta'];
		$Cantidad = $_POST['Cantidad'];
		$moneda = $_POST['moneda'];
		$monedaMonto = $_POST['monedaMonto'];
		$total=$_POST['totalVentaFinal'];
		$vuelto=$_POST['totalVueltoFinal'];

		//$user = $this->ion_auth->logged_in();
				
		$empleadoId =1;
		$sucursalId=0;

		$resultado = $this->Ventas_model->guardar($fecha,$total,$clienteId,$empleadoId,$sucursalId,$IdProducto,$PrecioVenta,$Cantidad,$moneda,$monedaMonto,$total,$vuelto);
		
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


	public function mostrarXcliente()
	{	
		$clienteId = $this->input->post("clienteId");
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;
		$data = array(
			"cli_ventas" => $this->Ventas_model->buscarXcliente($clienteId,$buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Ventas_model->buscarXcliente($clienteId,$buscar)),
			"cantidad" =>$cantidad
			
		);
		echo json_encode($data);
	}

	public function mostrarDetalleXcliente()
	{	
		$ventaId = $this->input->post("ventaId");
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;
		$data = array(
			"ventas_detalle" => $this->Ventas_detalle_model->buscarDetalleXcliente($ventaId,$buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Ventas_detalle_model->buscarDetalleXcliente($ventaId,$buscar)),
			"cantidad" =>$cantidad
			
		);
		echo json_encode($data);
	}

	public function verDetallePagos()
	{
		$IdVenta = $this->input->post("IdVenta");
		$this->load->model("Ventas_detalle_model");		
		$data = $this->Ventas_detalle_model->buscarDetallePagoImprimir($IdVenta);		
		echo json_encode($data);
	}

	public function ventasXFechas()
	{		
		$fecha_desde = $_POST['fecha_desde']; 
		$fecha_hasta = $_POST['fecha_hasta'];
		$this->load->model("Ventas_model");		
		$data = $this->Ventas_model->ventasXFechas($fecha_desde,$fecha_hasta);		
		echo json_encode($data);
	}

	public function ventasProductosXFechas()
	{		
		$fecha_desde = $_POST['fecha_desde']; 
		$fecha_hasta = $_POST['fecha_hasta'];
		$this->load->model("Ventas_model");		
		$data = $this->Ventas_model->ventasProductosXFechas($fecha_desde,$fecha_hasta);		
		echo json_encode($data);
	}

	public function ajax_delete($id)
	{	
		$this->Ventas_model->delete_by_masterid($id);		
		echo json_encode(array("status" => TRUE));
	}

	public function createXLS() {

		$this->load->library('excel');
		$fecha_desde = $_GET['fecha_desde']; 
		$fecha_hasta = $_GET['fecha_hasta'];

		$empInfo = $this->Ventas_model->get_all_export_by_date($fecha_desde,$fecha_hasta);
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		// set Header
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Fecha');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Apellido');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Nombre');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Total Venta');  
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
		 
		$archivo = "Ventas.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$archivo.'"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		//Hacemos una salida al navegador con el archivo Excel.
		$objWriter->save('php://output');  
	}
 




	public function createXLSventasproductos() {

		$this->load->library('excel');
		$fecha_desde = $_GET['fecha_desde']; 
		$fecha_hasta = $_GET['fecha_hasta'];

		$empInfo = $this->Ventas_model->get_all_ventasproductos_export_by_date($fecha_desde,$fecha_hasta);
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		// set Header
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Fecha');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Codigo Producto');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Nombre');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Cantidad');  
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Precio Venta');  
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Precio Compra');  
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Ganancia');  
		// set Row
		$rowCount = 2;

		foreach ($empInfo as $element) {
			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element['fecha']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['codigoproducto']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['nombre']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element['cantidad']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element['precioventa']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $element['preciocompra']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $element['diferencia']);
			$rowCount++;
		 }
		 
		$archivo = "VentasProductos.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$archivo.'"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		//Hacemos una salida al navegador con el archivo Excel.
		$objWriter->save('php://output');  
	}
 

}
