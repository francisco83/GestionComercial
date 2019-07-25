<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{		
		$this->load->view('home');
		$this->load->library(['ion_auth', 'form_validation']);
		$this->load->view('partial/encabezado');
	}
}
