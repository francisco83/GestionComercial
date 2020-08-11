<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library(['ion_auth', 'form_validation']);
	}
	public function index()
	{		
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}
		$this->load->view('home');
		$this->load->library(['ion_auth', 'form_validation']);
	}
}
