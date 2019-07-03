<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends Web_Controller {

	public function index()
	{
		$this->login->check_admin_login();
		
		$data['title']		=	'Settings';
		$data['heading']	=	'Settings';
		$data['icon']		=	'fa fa-sliders';
		
		$data['sitename']						=	$this->_settings['sitename'];
		$data['email']							=	$this->_settings['email'];
		$data['logo']							=	$this->_settings['logo'];
		$data['email_name']						=	$this->_settings['sending_email_name'];
		
		$this->_render_admin('admin/settings',$data);
	}
	
	public function save_settings($module='sitename'){
		$this->login->check_admin_login('ajax');
		$data	=	$this->input->post('value');
		
		$update	=	$this->lib->update('config',array('value'=>$data),'name',$module);
		if($update){
			echo "<span class='text-success'><i class='fa fa-check-circle'></i> ".$module." Saved!</span>";
		}
		exit();
	}

}