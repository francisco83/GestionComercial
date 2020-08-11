
<?php
class No_Access extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{
		$this->load->view('no_access');
		//$this->load->view("no_access/index");
		//redirect('Home', 'refresh');
	}
}
?>
