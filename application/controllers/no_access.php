<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php
class No_Access extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();	
		
	}
	function index()
	{
		//ob_start();
		//$this->load->view('no_access');
		$this->load->view("no_access/index");
		//redirect('Home', 'refresh');
	}
}
?>
