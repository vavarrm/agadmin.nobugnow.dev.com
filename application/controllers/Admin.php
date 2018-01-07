<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	
	
	public function __construct() 
	{
		parent::__construct();	
		$encrypt_admin_data = $this->session->userdata('encrypt_admin_data');
		if(empty($encrypt_admin_data))
		{
				$this->myfunc->gotourl('Login', '請登入');
		}
    }
	
	public function renterTemplates()
	{
		$this->smarty->displayFrame('Admin/renterTemplates.tpl');
	}
	
	public function index()
	{
		$this->smarty->displayFrame('Admin/frame.tpl');
	}
	
}
