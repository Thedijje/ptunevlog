<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends Web_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('login_model','login');
		
	}

	public function index()
	{
		$this->_render_admin('admin/login',NULL, true);
		// $data['config']		=	$this->_settings;
		// // dd($data);
		// $this->load->view(, $data);
	}
	
	
	
	public function validate(){
		$data	=	$this->input->post();
		if($data['email']=='' OR $data['password']==''){
			$this->lib->redirect_msg('Email/password can not be blank!','danger','admin/login');
		}
		
		$data['password']		=	$data['password'];
		$data['email']			=	$data['email'];
		$redirect				=	$data['redirect'];
		unset($data['redirect']);

		$login	=	$this->login->validate($data);
		
		if(!$login){
			$this->lib->redirect_msg('Invalid email/password combination!','danger','admin/login');
		}else{
			if(isset($redirect) && $redirect!=''){
				redirect(base_url($redirect));
			}else{
				redirect(base_url('admin/'));
			}
			exit();
		}
		
		
	}
	
	public function forget_password(){
		$this->_render_admin('admin/ajax/pass_reset');
	}
	
	public function reset_password(){
		$email	=	$this->input->post('email');
		$this->load->helper('string');
		if(!$email){
			$this->lib->redirect_msg('Invalid request, please try again soon!','danger','admin/login');
		}
		
		$check	=	$this->lib->get_row_array('admin',array('email'=>$email));
		if(!$check){
			$this->lib->redirect_msg('Email address you have specified, does not exist in record, please try again soon!','danger','admin/login');
		}else{
			$rand				=	random_string('alnum',8);
			$data['password']	=	password_hash($rand,PASSWORD_DEFAULT);
			
			$update	=	$this->lib->update('admin',$data,'email',$email);
			if(!$update){
				$this->lib->redirect_msg('Error in resetting password, please try again soon','danger','admin/login');
			}
			
			// email
			$mdata['name']		=	config_item('sitename');
			$mdata['from']		=	config_item('email');
			$mdata['to']		=	$email;
			$mdata['message']	=	"Hi ".$check->name."<br>You have requested new password on ".config_item('sitename').", Your new password is : ".$rand.".<br> Please login and change your password to make it more secure and don't share it with anyone.<br>Regards<br>Admin Team";
			$mdata['subject']		=	"New password request ".config_item('sitename');
			$email_send	=	$this->lib->send_formatted_mail($mdata);

			if($email_send){
				$this->lib->redirect_msg('Password instruction sent you in email, please check email to login','success','admin/login');
			}else{
				$this->lib->redirect_msg('Could not sent you email, please try resetting it again','danger','admin/login');
			}

		}
	}
}
