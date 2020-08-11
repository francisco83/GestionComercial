<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("Users_model");
		$this->load->model("Users_groups_model");
		$this->load->library(['ion_auth', 'form_validation']);

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('Home', 'refresh');
		}
	}

	public function index(){
		$this->load->view("users/index");
	}

	public function get_all(){
		$resultado = $this->Users_model->listar();
		echo $resultado;
	}

	public function get_all_array(){
		$resultado = $this->Users_model->get_all_array();		
		echo json_encode($resultado);
	}

	public function mostrar()
	{	
		$buscar = $this->input->post("buscar");
		$numeropagina = $this->input->post("nropagina");
		$cantidad = $this->input->post("cantidad");
		
		$inicio = ($numeropagina -1)*$cantidad;


		$this->data['users'] = $this->Users_model->buscar($buscar,$inicio,$cantidad);
			
	   //Cargo los grupos de los usuarios		
		foreach ($this->data['users'] as $k => $user)
		{
			$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
		}

		$data = array(
			"users" => $this->data['users'],
			"totalregistros" => count($this->Users_model->buscar($buscar)),
			"cantidad" =>$cantidad,

			
		);
		echo json_encode($data);
	}

	public function buscar()
	{
		$json = [];
		$this->load->database();		
		if(!empty($this->input->get("q"))){
			$this->db->like('nombre', $this->input->get("q"));
		}
			$query = $this->db->select('id,nombre as text')
						->limit(10)
						->get("Users");
			$json = $query->result();		
		
		echo json_encode($json);
	}

	function get_autocomplete(){
        if (isset($_GET['term'])) {
            $result = $this->Users_model->search_autocomplete($_GET['term']);
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
		
		$this->_validate();		
		$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'email' => $this->input->post('email'),
				'phone' => $this->input->post('phone'),
				'password' => $this->input->post('password'),
				
			);

		$insert = $this->Users_model->save($data);

		$grupos = $_POST['groups']; 

		for ($i=0; $i < count($grupos); $i++) 
		{   
			
			$datarel[$i]['group_id'] = $grupos[$i];
			$datarel[$i]['user_id'] = $insert;
			
		}

		$resultado = $this->Users_groups_model->saveAll($datarel);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_edit($id)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$data = $this->Users_model->get_by_id($id);
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
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'email' => $this->input->post('email'),
				'phone' => $this->input->post('phone'),
				'password' => $this->input->post('password'),
			);
		$this->Users_model->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}


	public function ajax_delete($id)
	{	
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$this->Users_model->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_enabled($id)
	{		
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$this->Users_model->enabled_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('first_name') == '')
		{
			$data['inputerror'][] = 'first_name';
			$data['error_string'][] = 'Debe ingresar un nombre.';
			$data['status'] = FALSE;
		}

		
		if($this->input->post('last_name') == '')
		{
			$data['inputerror'][] = 'last_name';
			$data['error_string'][] = 'Debe ingresar un apellido.';
			$data['status'] = FALSE;
		}

		if($this->input->post('email') == '')
		{
			$data['inputerror'][] = 'email';
			$data['error_string'][] = 'Debe ingresar un email.';
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
       $empInfo = $this->Users_model->get_all_export();
       $objPHPExcel = new PHPExcel();
       $objPHPExcel->setActiveSheetIndex(0);
       // set Header
       $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Nombre');
       $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Apellido');
       $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Email');  
       // set Row
       $rowCount = 2;
       foreach ($empInfo as $element) {
           $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element['firt_name']);
           $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['last_name']);
           $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['email']);
           $rowCount++;
        }
        
       $archivo = "Usuarios.xls";
       header('Content-Type: application/vnd.ms-excel');
       header('Content-Disposition: attachment;filename="'.$archivo.'"');
       header('Cache-Control: max-age=0');
       $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
       //Hacemos una salida al navegador con el archivo Excel.
       $objWriter->save('php://output');  
   }




}
