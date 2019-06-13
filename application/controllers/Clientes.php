<?php
 defined('BASEPATH') OR exit('No direct script access allowed');

 class Clientes Extends CI_Controller
 {
	 public function index()
	 {
		$this->load->view('clientes');
	 }
 }
