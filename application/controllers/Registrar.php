<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Registrar extends CI_Controller {
	
	public function __construct(){		
		parent::__construct();
		$this->load->model("Clientes_model");		
		$this->load->model("Servicios_model");		
	}

	public function index($id){		
		$this->load->view("registrar/index");
	}

	function get_autocomplete(){
        if (isset($_GET['term'])) {
            $result = $this->Servicios_model->search_blog($_GET['term']);
            if (count($result) > 0) {
			foreach ($result as $row)	
			{
				$data[] = array(
					'id' => $row->id,
					'value'=> $row->nombre
				);
				//$data['id'] = $row['id'];
				//$data['value'] = $row['nombre'];
				//$data['value'] = $row->nombre;
                //array_push($returnData, $data);
			}	
				//$arr_result[] = $row->nombre;				
                print json_encode($data);
            }
        }
	}
	
}
