<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Web_Controller {
	
	function __construct(){
		parent::__construct();
		$this->login->check_admin_login();
	}

	public function index()
	{
		
		$data['title']			=	'Users';
		$data['heading']		=	'Users';
		$data['icon']			=	'fa fa-user';
		
		$data['users']						=	$this->lib->get_table('users');
		
		
		$data['count_active']		=	$this->lib->count_multiple('users',array('user_status'=>1));
		$data['count_pending']		=	$this->lib->count_multiple('users',array('user_status'=>3));
		$data['count_suspended']	=	$this->lib->count_multiple('users',array('user_status'=>7));
		$data['count_inactive']		=	$this->lib->count_multiple('users',array('user_status'=>2)); 
		$data['count_deleted']		=	$this->lib->count_multiple('users',array('user_status'=>9));
		$data['count_premium']		=	$this->lib->count_multiple('users',array('user_membership'=>1));
		$data['count_verified']		=	$this->lib->count_multiple('users',array('user_membership'=>2));
		$data['count_public']			=	$this->lib->count_multiple('users',array('user_membership'=>3));
		$data['keyword']					=	"";


		$this->_render_admin('admin/users', $data);
	}
}
