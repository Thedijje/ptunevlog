<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends Web_Controller {

	function __construct(){
		
		parent::__construct();
		$this->load->model(array('User_model'=>'user'));
		$this->load->helper(array('cookie','security'));
		$this->output->set_header('Strict-Transport-Security: max-age=31536000;');
		
	}

	public function index(){
	
		if(isset($_SESSION['is_password_set']) AND $_SESSION['is_password_set']=='yes'){
			$this->session->unset_userdata('is_password_set');
			$this->lib->redirect_msg('Password Reset Successfully. Please login with your new password.','success','login');
		}

		$user_id =	webapp_user('user_id');
		if(isset($user_id) AND $user_id!=''){
			
			redirect(base_url('home'));
			exit();
		}
		$data 	=	array(
			'title'				=>	'Login to start',
			'meta_desription'	=>	'Login to mobi-hub : A simple, social and user-friendly business app for consumer electronic traders. Mobi-Hub will connect you to thousands of traders across the globe.',
			'meta_keyword'		=>	'mobi-hub, web app, mobile traders, traders, electronic traders, mobile traders platform, mobi-hub platform, mobile sale. apple iphone seller'
			);
		$remmber 		=	get_cookie('remmber_token');

		if($remmber){
			$remmber 	=	get_credential($remmber);
			$data['username']	=	$remmber[1];
			$data['password']	=	$remmber[0];
		}
		$this->load->view('front/includes/public_header',$data);
		$this->load->view('front/login',$data);
		$this->load->view('front/includes/public_footer',$data);

	}

	public function validate(){
		if(!validate_csrf()){
			$this->lib->redirect_msg('Security token mismatched','danger','login');
		}
		clear_session_data('registration');
		clear_session_data('reg_files');
		// clear_session_data('otp_verified');
		$error_lang			=	$this->lang->line('login');
		$user_id			=	webapp_user('user_id');
		if($user_id!=NULL){
			redirect(base_url('home'));
			exit();
		}
		
		$data	=	$this->input->post();
		if(!$data){
			$this->lib->redirect_msg($error_lang['invalid_request'],'danger','login');
		}

		$data		= $this->security->xss_clean($data);
		$username	=	$data['username'];
		$check_user =	$this->lib->get_row_array('users',array('username'=>$username,'user_status<='=>3));
		
		if(!$check_user){
			$this->login->record_login(0,5,false,0);
			$this->lib->redirect_msg($error_lang['msg_incorrect_login'],'danger','login');
		}
		
		$isPasswordCorrect = password_verify($data['password'], $check_user->user_password);
		
		if(!$check_user OR !$isPasswordCorrect){
			$this->login->record_login($check_user->user_id,5,false,0);
			$this->lib->redirect_msg($error_lang['msg_incorrect_login'],'danger','login');
		}

		if($check_user->user_status==3){
			//	Checking if user made payment
			$this->load->model('payment_model');
			$check_payment 	=	$this->payment_model->check_user_payment($check_user->user_id);
			if(!$check_payment){
				log_message('error','User with ID# '.$check_user->user_id.' is not having payment, sending to payment page');
				$unpaid_user_info	=	array(
					'user_id'		=>	$check_user->user_id,
					'user_name'		=>	$check_user->user_first_name,
					'user_status'	=>	$check_user->user_status,
					'user_email'	=>	$check_user->user_email
				);

				$this->session->set_userdata('unpaid_user',$unpaid_user_info);
				$this->lib->redirect_msg($error_lang['msg_incomplete_payment'],'info','payment/start');
			}

			$this->lib->redirect_msg($error_lang['msg_incomplete_payment'],'warning','login');
		}

		if($check_user->user_status==8){
			$this->lib->redirect_msg($error_lang['msg_activation'],'info','login');
			exit();
		}
		/**
		*	Check payment and expiration date
		*/

		if($check_user->user_membership==1){
			$upgrade_info	=	$this->lib->get_row_array('membership_upgrade',array('mu_user_id'=>$check_user->user_id,'mu_plan_id'=>1,'mu_status'=>1,'mu_expire_on>'=>time()));

			// $check_expire = $upgrade_info->mu_expire_on > time() ? true : false;
			if(!$upgrade_info){
				$this->lib->update('users',array('user_membership'=>3),'user_id',$check_user->user_id);
				$this->membership->update_expired($check_user->user_id);

				/**
				 * Send email to user about 
				 * 
				 * */
				//$this->lib->redirect_msg('Your Verification plan expired, Your account functionality will be limited! Please login again to upgrade/Verify your account & unlock full functionality','danger','login');
				$this->lib->redirect_msg('Your Verification plan expired, Your account functionality will be limited! Please login again to renew/Verify your account & unlock full functionality','danger','login');
				exit();
			}
		}

		$this->login->record_login($check_user->user_id ?? 0,1,false,0);
		$membership_detail		=	$this->membership->detail($check_user->user_id);
		$is_vsm					=	$this->users->is_vsm($check_user->user_id);
		$user_info	=	array(
			'user_id'			=>	$check_user->user_id,
			'user_first_name'	=>	$check_user->user_first_name,
			'user_last_name'	=>	$check_user->user_last_name,
			'username'			=>	$check_user->username,
			'user_email'		=>	$check_user->user_email,
			'user_dp'			=>	$check_user->user_display_pic,
			'user_status'		=>	$check_user->user_status,
			'password_update'	=>	$check_user->last_password_update,
			'user_membership'	=>	$check_user->user_membership,
			'membership_expire'	=>	$upgrade_info->mu_expire_on ?? 0,
			'membership_date'	=>	$membership_detail->mu_time ?? '',
			'vsm_user'			=>	$is_vsm ?? '0'
		);

		$this->session->set_userdata('webapp_user',$user_info);
		$user_timezone 			=	$data['timezone'] ?? '';
		if($user_timezone!=''){
			$this->session->set_userdata('user_timezone',$user_timezone);
		}

		if(isset($data['rememberMe']) AND $data['rememberMe']==1){
			$hour = 3600 * 24 * 10;
			$remmber 	=	secure_login($data['username'],$data['password']);
			set_cookie('remmber_token',$remmber,$hour,NULL,NULL,NULL,NULL,true);
		}else{
			delete_cookie('remmber_token');
		}

		/*
		*	Redirect to edit profile if account inactivated
		*/
		if($check_user->user_status==2){
			$this->lib->redirect_msg('Your account is deactivated by admin, please edit your profile or contact admin','warning','profile/edit');
			exit();
		}

		/*
		*	Redirect admin's created user to change their password
		*/
		$is_admin 	=	$this->users->is_admin($check_user->user_id);
		
		if($check_user->user_created_by!=0 AND $check_user->last_password_update=='' AND !$is_admin){
			//	User is created by admin and default last password is yet not changed
			$this->lib->redirect_msg('Your account is created by admin, please change your password to keep it secure','warning','accounts/change_password');
			exit();
		}

		/*
		*	Redirect user to change profile image
		*/
		$user_default_dp 	=	$this->lib->get_settings('user_default_avatar');
		if($check_user->user_display_pic==$user_default_dp){
			$this->lib->redirect_msg('Please complete your profile and add image to your profile.','info','profile/edit');
			exit();
		}
		$company_info 	=	$this->lib->get_row_array('company',array('company_id'=>$check_user->company_id));
		if(!$company_info || $company_info->company_address=='' || $company_info->company_zip=='' || $company_info->company_city=='' || $company_info->company_state=='' || $company_info->company_country=='' || $company_info->company_logo=='' || $company_info->company_logo=='static/images/placeholder/company.png' || $company_info->company_description==''){
				$this->lib->redirect_msg('Please complete company profile','info','company/edit_company/'.base64_encode($check_user->company_id));
				exit();
		}
		
		/*
		*	No following users, redirecting to explore page to open posts and follow users
		
		$this->load->model('follow_model');
		$following	=	$this->follow_model->get_follow_num('following',$check_user->user_id);
		if($following<5){
			$this->lib->redirect_msg('Explore recent posts, follow users/company to get their posts in your feed.','info','explore');
			exit();
		}
		----	Depricated, swaping explore to home and vice versa, new post will be on home from all of user on mobi-hub
		*/
		if(isset($data['redirect']) AND $data['redirect']!=''){
			redirect(base_url($data['redirect']));
		}

		redirect(base_url('home'));
		exit();
	}

	public function not_found(){
		not_found();
		
	}

	public function reset(){

		$user_id =	webapp_user('user_id');

		if(isset($user_id) AND $user_id!=''){
			redirect(base_url('home'));
			exit();
		}

		$data 	=	array(
			'title'				=>	'Reset password assitant',
			'meta_desription'	=>	'Reset password assitant.',
			'meta_keyword'		=>	'mobi-hub, web app, mobile traders, traders, electronic traders, mobile traders platform, mobi-hub platform, mobile sale. apple iphone seller'
		);

		$data['countries'] 				= $this->lib->get_table('countries',array('country_name'=>'asc'));
		$this->load->view('front/includes/public_header',$data);
		$this->load->view('front/password_reset',$data);
		$this->load->view('front/includes/public_footer',$data);

	}

	public function send_reset_code(){
		$user 		=	$this->input->post('user');
		$response	=	array();
		if(!$user){
			$response['status']	=	'error';
			$response['msg']		=	'Invalid request, please try again soon';
			echo json_encode($response);
			exit();
		}

		$check_user		=	$this->lib->get_row_array('users',array('username'=>$user,'user_status<='=>2));

		if(!$check_user){
			$check_user		=	$this->lib->get_row_array('users',array('user_email'=>$user,'user_status<='=>2));
		}

		if(!$check_user){

			log_message('error','User tried to request  forget password with wrong username # '.$user.' From IP #'.$this->input->ip_address());
			//$this->notification->slack_notify('User tried to request  forget password with wrong username # '.$user.' From IP #'.$this->input->ip_address());
			$response['status']	=	'error';
			$response['msg']		=	'Invalid Username/Email address';
			echo json_encode($response);
			exit();
		}

		$token_plain 	=	random_string('nozero',5);
		$reset_token	=	base64_encode($token_plain);
		$email 			=	base64_encode($check_user->user_email);
		$pass_token		=	base64_encode(time().'--'.$email.'--'.$reset_token);

		$save_token	 	=	$this->lib->update('users',array('pass_reset_token'=>$pass_token),'user_email',$check_user->user_email);

		if(!$save_token){
			$response['status']	=	'error';
			$response['msg']		=	'Error sending token';
			echo json_encode($response);
			exit();
		}

		$this->session->set_userdata('app_pass_token',$pass_token);

		$this->session->set_userdata('verification_attempts',2);

		$this->login->forget_password_email($check_user,$token_plain,$pass_token);

		$message			=	"Hello, ".$check_user->username." You OTP to reset Mobi-Hub Password is ".$token_plain;

		$this->notification->send_sms($check_user->user_mobile,$message);

		log_message('error','Message and mail sent to user # '.$user.' to reset password with token '.$token_plain);

		$response['status']	=	'success';
		$response['msg']		=	'OTP sent to your registered email '.substr($check_user->user_email,0,3).'xxxxxxxx'.substr($check_user->user_email,-5).' and mobile '.substr($check_user->user_mobile,0,4).'xxxxxx'.substr($check_user->user_mobile,-2).". Valid for 10 minutes only.";

		echo json_encode($response);
		exit();
	}

	public function verify_reset_otp(){
			//validate session value
			if(!isset($_SESSION['verification_attempts']) || !isset($_SESSION['app_pass_token'])){
				echo json_response('error','invalid_data','Invalid attempt in password reset.');
				exit();
			}

			$verify_attempts	=	$_SESSION['verification_attempts'];
			//validate attempts
			if($verify_attempts<1){
				$this->session->unset_userdata('app_pass_token');
				$this->session->unset_userdata('verification_attempts');
				echo json_response('error','attempt_fail','You have entered wrong OTP 3 times.');
				exit();
			}

			$new_verify_attempts	=	$verify_attempts-1;
			$this->session->set_userdata('verification_attempts',$new_verify_attempts);

			// decode token
			$reset_token 	= 	$_SESSION['app_pass_token'];
			
			$token_decode	=		base64_decode($reset_token);
			$token_keys 	=		explode('--',$token_decode);
			$request_time =		$token_keys['0'];
			$user_email 	=		base64_decode($token_keys['1']);
			$plain_otp 		=		base64_decode($token_keys['2']);	

			// validate time token set
			$current_time 	= 	time();
			if(($current_time-$request_time)>60*10){
				echo json_response('error','time_expired','Password Reset time expired.');
				exit();
			}

			//validate otp
			$user_otp			=		$this->input->post('c_otp');

			if($plain_otp!==$user_otp){
				echo json_response('error','invalid_otp','Invalid OTP, Only '.$verify_attempts.' attempts left.');
				exit();
			}

			//here will be way to reset form
			if($plain_otp===$user_otp){
				$this->session->unset_userdata('verification_attempts');
				$this->session->set_userdata('is_otp_verified','yes');

				$response = array(
					'status'	=>	'success',
					'type'		=>	'success',
					'msg'		=>	'OTP verified',
				);
				$response['html'] = $this->load->view('front/ajax/set_new_password', null,true);
				// dd($response);
				echo json_encode($response);

				
				//echo json_response('success','success','OTP verified');
				exit();
			}

			echo json_response('error','error','Error Resetting password.');
			exit();
	}


	public function save_new_password(){
		if(!isset($_SESSION['is_otp_verified']) || !isset($_SESSION['app_pass_token']) || $_SESSION['is_otp_verified']!='yes'){
			echo json_response('error','invalid_data','Invalid attempt in password reset.');
			exit();
		}

		$token_decode			=		base64_decode($_SESSION['app_pass_token']);
		$token_keys 			=		explode('--',$token_decode);
		$user_email 			=		base64_decode($token_keys['1']);
		$user_id 					= 	$this->lib->get_row('users','user_email',$user_email,'user_id');
		$request_time 		=		$token_keys['0'];

		// validate time token set
		$current_time 	= 	time();
		if(($current_time-$request_time)>60*10){
			echo json_response('error','time_expired','Password Reset time expired.');
			exit();
		}

		$password 				=		$this->input->post('new_pass');

		if(in_array($password,$this->config->item('weak_password'))){
			echo json_response('error','error','Weak password not allowed.');
			exit();
		}

		$new_password 		=		password_hash($password,PASSWORD_DEFAULT);



		$input 				=	array('user_password'=>$new_password,'pass_reset_token'=>'');
		$update_pass	=	$this->lib->update('users',$input,'user_id',$user_id);

		if(!$update_pass){
			echo json_response('error','error','Serevr Error in Password Reset');
			exit();
		}

		$this->session->set_userdata('is_password_set','yes');

		$pass_change_email	=	$this->send_email->password_change($user_id);
		echo json_response('success','success','Password Reset Successfully.');

		exit();
	}



	public function logout(){
		unset($_SESSION['webapp_user']);
		$_SESSION['webapp_user']	=	NULL;
		$this->lib->redirect_msg('You have logged out','success','login');
	}

}
