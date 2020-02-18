<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("Ventas_model");
		$this->load->model("Ventas_detalle_model");
		$this->load->model("Pagos_model");
		$this->load->library(['ion_auth', 'form_validation']);

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
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

	public function insertar(){
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
		

		$id = $this->Ventas_model->guardarCambios($fecha,$total,$clienteId,$empleadoId,$sucursalId);

		$id = $id;
		
		if($id > 0)
		{
			for ($i=0; $i < count($IdProducto); $i++) 
			{   			
				$data[$i]['ventaId'] = $id;
				$data[$i]['productoId'] = $IdProducto[$i];			
				$data[$i]['Precio'] = $PrecioVenta[$i];
				$data[$i]['Cantidad'] = $Cantidad[$i];
			}


			$resultado = $this->Ventas_detalle_model->guardarCambios($data);

			if($resultado){

				for ($i=0; $i < count($moneda); $i++) 
				{   			
					$dataPago[$i]['ventaId'] = $id;
					$dataPago[$i]['tipo_monedaId'] = $moneda[$i];			
					$dataPago[$i]['monto'] = $monedaMonto[$i];
					$dataPago[$i]['fecha_pago'] = $fecha;
					if($moneda[$i]==1){//si es efectivo solo puede dar vuelto
						$dataPago[$i]['vuelto']=$vuelto;
					}
					else{
						$dataPago[$i]['vuelto']=0;
					}
					
				}

				$resultado = $this->Pagos_model->guardarCambios($dataPago);

				if($resultado){
					$mensaje = "Registro cargado correctamente";
					$clase = "success";			
				}else{
					$mensaje = "Error al registrar la venta";
					$clase = "danger";
					$json['error'] = $this->upload->display_errors();
				}
				$this->session->set_flashdata(array(
					"mensaje" => $mensaje,
					"clase" => $clase,
				));

			}
		}
		else{

			$mensaje = "Error al registrar la venta";
					$clase = "danger";
					$json['error'] = $this->upload->display_errors();
		}
				$this->session->set_flashdata(array(
					"mensaje" => $mensaje,
					"clase" => $clase,
				));
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
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Total');  
		// set Row
		$rowCount = 2;
		foreach ($empInfo as $element) {
			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element['fecha']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['apellido']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['nombre']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element['total']);
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
 

}
