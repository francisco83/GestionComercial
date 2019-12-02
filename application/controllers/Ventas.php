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
		

		$id = $this->Ventas_model->guardarCambios($fecha,$total,$vuelto,$clienteId,$empleadoId,$sucursalId);

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
/*
	public function get_all(){
		$resultado = $this->Ventas_model->get_all();
		echo $resultado;
	}

	public function mostrar()
	{	
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;
		$data = array(
			"Ventas" => $this->Ventas_model->buscar($buscar,$inicio,$cantidad),
			"totalregistros" => count($this->Ventas_model->buscar($buscar)),
			"cantidad" =>$cantidad
			
		);
		echo json_encode($data);
	}

	public function buscar_Ventas()
	{
		$json = [];
		$this->load->database();		
		if(!empty($this->input->get("q"))){
			$this->db->like('apellido', $this->input->get("q"));
			$this->db->or_like('nombre', $this->input->get("q"));
		}
			$query = $this->db->select('id,nombre as text')
						->limit(10)
						->get("Ventas");
			$json = $query->result();		
		
		echo json_encode($json);
	}

	function get_autocomplete(){
        if (isset($_GET['term'])) {
            $result = $this->Ventas_model->search_autocomplete($_GET['term']);
            if (count($result) > 0) {
			foreach ($result as $row)	
			{
				$data[] = array(
					'id' => $row->id,
					'value'=> $row->apellido.' '.$row->nombre
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
				'apellido' => $this->input->post('apellido'),
				'dni' => $this->input->post('dni'),
				'email' => $this->input->post('email'),				
				'telefono' => $this->input->post('telefono'),
			);

		$insert = $this->Ventas_model->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_edit($id)
	{
		$data = $this->Ventas_model->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
			'nombre' => $this->input->post('nombre'),
			'apellido' => $this->input->post('apellido'),
			'dni' => $this->input->post('dni'),
			'email' => $this->input->post('email'),				
			'telefono' => $this->input->post('telefono'),
			);
		$this->Ventas_model->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}


	public function ajax_delete($id)
	{	
		$this->Ventas_model->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_enabled($id)
	{		
		$this->Ventas_model->enabled_by_id($id);
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
       $empInfo = $this->Ventas_model->get_all_export();
       $objPHPExcel = new PHPExcel();
       $objPHPExcel->setActiveSheetIndex(0);
       // set Header
       $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Nombre');
       $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Apellido');
       $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'DNI');  
       $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Email');  
       $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Teléfono');  
       // set Row
       $rowCount = 2;
       foreach ($empInfo as $element) {
           $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element['nombre']);
           $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['apellido']);
           $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['dni']);
           $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element['email']);
           $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element['telefono']);
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

*/


}
