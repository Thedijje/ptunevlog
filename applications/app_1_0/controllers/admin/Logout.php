<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends Web_Controller {

	public function index()
	{
		
		$this->session->unset_userdata('admin');
		$this->session->set_flashdata(array('msg'=>'You have successfully logged out','type'=>'info'));
		redirect(base_url('admin/login'));
		exit();
	}
}
