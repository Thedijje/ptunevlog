<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {
	
		// public function validate($data,$fields){
			
		// 	foreach($fields as $key=>$value):
		// 	if(!empty($data[$value])){
		// 		$members['member_'.$value]=$data[$value];
		// 	}else{
		// 		$this->lib->redirect_msg(ucfirst($value).' should not be empty','warning','signup');
		// 	}
			
		// 	endforeach;
		// 	return $members;
		// }
		
		public function validate($data){
			if($data['email']=='' OR $data['password']==''){
			return false;	
			}
			$data['status']	=	1;
			
			$ins['email'] =$data['email'];
			$ins['status']=$data['status'];
			$check		=	$this->lib->get_row_array('admin',array('email'=>$data['email'],'status'=>$data['status']));
			
			$verify_password = password_verify($data['password'],$check->password);
			if($verify_password){
				$admin 	=	$check;
				$sess	=	array(
					'id'			=>	$admin->id,
					'name'			=>	$admin->name,
					'email'			=>	$admin->email,
					'role'			=>	$admin->role,
					'is_login'		=>	TRUE,
					'admin_timezone'=>	$data['user_timezone'],
					'last_login'	=>	time()
				);
			
			$this->session->set_userdata('admin',$sess);
			$this->lib->update('admin',array('last_login'=>time()),'id',$admin->id);
			
			return TRUE;	
				
			}else{
			
			return FALSE;
			}
			
			
			
		}

		
		
		public function check_admin_login($ajax=NULL){
			$adm_data	=	$this->session->userdata('admin');

			if(!isset($adm_data) OR !$adm_data['is_login'] OR !$adm_data['email']){
				if($ajax!=NULL){
				$this->lib->display_alert('Your session expired, redirecting you to login page! Please wait...','danger','warning');
				?>
				<meta http-equiv="refresh" content="3;url=<?php echo base_url('admin/login?redirect='.uri_string());?>">
				<?php
				exit();
				}
				$this->lib->redirect_msg('To access this page, you need to login first!','danger','admin/login?redirect='.uri_string());

			}
			$u_timezone 	=	$_SESSION['admin']['admin_timezone'] ?? '';
			if($u_timezone!=''){
				date_default_timezone_set($u_timezone);
			}
			$this->lib->update('admin',array('admin_last_activity'=>time()),'email',$adm_data['email']);
			
		}
		
		public function check_user_login(){
			user_timezone();
			//$this->output->set_header('HTTP/1.0 200 OKKKKK');
			$user_data 	=	$this->session->userdata('webapp_user');
			if(!isset($user_data['user_id'])){
				unset($_SESSION['webapp_user']);
				if ($this->input->is_ajax_request()) {
			  	 exit('LoggedOut');
				}
				$this->lib->redirect_msg('You need to login first!','danger','login?redirect='.uri_string());
				exit();
			}

			/*
			*		checking active status
			*/
			$check_user 		=		$this->lib->get_row_array('users',array('user_id'=>$user_data['user_id'],'user_status<='=>2));
			if($check_user->user_status==2){
				if($_SERVER['REQUEST_URI']!='/profile/edit' AND $_SERVER['REQUEST_URI']!='/profile/profile_edit'){
					$this->lib->redirect_msg('Your account is inactivated by admin, please edit you profile or contact admin','warning','profile/edit');
					exit();	
				}
			}
			

			
			if((isset($user_data['password_update']) AND $user_data['password_update']!=$check_user->last_password_update ) OR  ($check_user->user_status!=webapp_user('user_status'))){
					log_message('error','User @'.$check_user->username.' is being logged out because of password change time diff expacted '.$user_data['password_update'].' found new#'.$check_user->last_password_update);
					unset($_SESSION['webapp_user']);
					
					if ($this->input->is_ajax_request()) {
				  	 exit('LoggedOut');
					}
					$this->lib->redirect_msg('Your session is expired, due to some updation in account, please login again to continue!','danger','login?redirect='.uri_string());
					exit();
			}
			
			if(!$check_user){
				unset($_SESSION['webapp_user']);

				if ($this->input->is_ajax_request()) {
			  	 exit('LoggedOut');
				}
				
				$this->lib->redirect_msg('Please login again to continue','warning','logout');
			}

			$this->save_user_activity($check_user->username);
			
		}

		public function check_api_login($data){
			if(!$data){
				return false;
			}

			$this->db->select('*');
			$this->db->from('users');
			$this->db->where('username',$data['username']);
			//$this->db->where('user_is_verified','1');
			$this->db->where('user_status<=',2);
			
			$query	=	$this->db->get();

			if(!$query){
				log_message('error','DB query error at api user login #'.mysql_error());
				return false;
			}

			if($query->num_rows()!==1){
				log_message('error','Expecting single row in api user login check, returning #'.$query->num_rows());
				return false;
			}

			$row 	=	$query->row();
			if(!$row){
				$this->login->record_login(0,5,true,0);
			}

			$isPasswordCorrect = password_verify($data['password'], $row->user_password);
			if(!$isPasswordCorrect){
				log_message('error','Invalid Password for username'.$data['username'].' through API');
				sleep(2);
				$this->login->record_login($row->user_id,5,true,0);
				return false;
			}
			return $row;
		}
		

		public function create_api_token($email,$user_id,$last_pass){
			
			if(!$email OR !$user_id){
				return false;
			}
			$pre_key 	=	base64_encode(PRE_API_SECREATE_KEY);
			$email 		=	base64_encode($email);
			$user_id 	=	base64_encode($user_id);
			$pass_time 	=	base64_encode($last_pass);	//	Last password update time
			$post_key	=	base64_encode(POST_API_SECREATE_KEY);
			
			$raw_token	=	$pre_key.'--'.$email.'--'.$user_id.'--'.$pass_time.'--'.$post_key;
			$final_token=	base64_encode($raw_token);
			/*
			Update Login time
			*/

			return $final_token;			

		}

		public function refresh_auth($logout=null){
		user_timezone();
		$apache_header 	=	apache_request_headers();
		$token_key 		=	strtoupper(config_item('auth_key'));

		
		if(!isset($apache_header[$token_key])){
			log_message('info','No auth header found or invalid auth header');
			$this->token_expire();
		}

		$userAuthString	=	$apache_header[$token_key];
		$userAuthArray	=	explode('--',base64_decode($userAuthString));
		if(!is_array($userAuthArray)){
			log_message('info','Auth Header is not an array');
			$this->token_expire();
		} 
		
		if(base64_decode($userAuthArray[0])!=PRE_API_SECREATE_KEY OR base64_decode($userAuthArray[4])!=POST_API_SECREATE_KEY){
			log_message('error','JWT secrate key mismatch #'.$userAuthString);
			//$this->output->set_status_header(403);
			//die('here');
			//return $this->login->token_expire_get();
			//return $this->token_expire();
			
		}

		if(!isset($userAuthArray[0]) OR !isset($userAuthArray[1]) OR !isset($userAuthArray[2])){
			log_message('error','Invalid auth token, no array offset is correct');
			$this->token_expire();
			
		}

		$userId				=	base64_decode($userAuthArray[1]);
		$email				=	base64_decode($userAuthArray[2]);
		$last_pass			=	base64_decode($userAuthArray[3]);
		//die($userId.'--'.$email.'--'.$last_pass);
		/*
		if(!API_AUTH and time()>1504701171){
			log_message('debug','Agreement broken');
			redirect(base_url('APIv15/login/token_expire'));
			exit();
		}
		*/

		$checkUserInfo	=	$this->lib->get_row_array('users',array('user_id'=>$userId,'user_email'=>$email,'last_password_update'=>$last_pass,'user_status<='=>2));
		//var_dump($checkUserInfo);
		//exit();
		if(!$checkUserInfo){$this->token_expire();
		}

		$current_api 	=	$this->uri->segment(2);
		

		if($current_api=='feed' AND $checkUserInfo->user_status==2){
			$this->token_expire();
		}

		if(!$checkUserInfo OR !$userAuthArray){$this->token_expire();
		}


		if($checkUserInfo){
			log_message('info','User login refreshed for user ID# '.$checkUserInfo->user_id.' and email '.$checkUserInfo->user_email);
			$this->set_user_online($userId);
			if($checkUserInfo->user_timezone!=''):
				date_default_timezone_set($checkUserInfo->user_timezone);
			endif;
			return $this->save_user_login($checkUserInfo);
		}

		
		
		
	}

	public function save_user_login($user_info){

		$user 				=	array(
								'user_id'			=>	$user_info->user_id,
								'user_email'		=>	$user_info->user_email,
								'user_name'			=>	$user_info->user_first_name.' '.$user_info->user_last_name,
								'user_status'		=>	$user_info->user_status,
								'user_membership'	=>	$user_info->user_membership
								);
		$this->session->set_userdata('app_user',$user);
		return true;

	}

	/*********
	*	EMAIL
	*********/

	public function forget_password_email($userData,$token,$pass_token){
		if(!$userData OR !$token){
			return false;
		}

		$email['name']		=	config_item('sitename');
		$email['from']		=	config_item('sending_email');
		$email['subject']	=	"Password reset token ".$token;
		$email['to']		=	$userData->user_email;
		$email['message']	=	$this->load->view('email/forget_password_otp',array('user_info'=>$userData,'token'=>$token,'reset_token'=>$pass_token),TRUE);
		$send 				=	$this->lib->send_formatted_mail($email);
		if(!$send){
			log_message('error','OTP mail could not be sent, please try again soon');
			return false;
		}
		return true;
	}

	

	public function failed_attempt($username){
		$last_attempt 	=	$this->session->userdata('failed_attempt');
		$failed_ip		=	$this->session->userdata('ip_address');
		$current_ip		=	$this->input->ip_address();
		if(isset($last_attempt) AND isset($failed_ip) AND $failed_ip==$current_ip){
			$this->record_failed_attempt($username);
			unset($_SESSION['failed_attempt']);
			unset($_SESSION['ip_address']);
			log_message('debug','recording failed attempt');
		}else{

		$this->session->set_tempdata(array('failed_attempt'=>time(),'ip_address'=>$this->input->ip_address()),10);
		return true;
		}

		
	}

	public function record_login($user_id,$status=1,$app=false,$type=0){
		$this->load->library('user_agent');
		
		$current_ip	=	$this->input->ip_address();
		$os 		=	$this->agent->platform();
		if($os=='Unknown Platform'){
			$os = 'Mobile app';
		}
		$browser	=	$this->agent->agent_string().' | Version: '.$this->agent->version();
		$is_mobile 	=	$app ? '1' : '0';
		if($status==1){
			$status =	1;
		}else{
			$status =	5;
		}
		$user_id 	=	$user_id ?? 0;
		$type 		=	(string)$type;
		
		$input 		=	array(
			'lr_user_id'	=>	$user_id,
			'lr_user_type'	=>	$type,
			'lr_ip'			=>	$current_ip,
			'lr_browser'	=>	$browser,
			'lr_os'			=>	$os,
			'lr_is_app'		=>	$is_mobile,
			'lr_status'		=>	$status,
			'lr_time'		=>	time()

		);
		$this->db->insert('login_record',$input);
		
		if($status!=1):
			log_message('error','Failed login from user#'.$user_id.', user type: '.$type.', IP : '.$current_ip.' at time: '.date(DATE_TIME_FORMAT,time()));
		endif;
		
		return true;
	}

	public function record_failed_attempt($username){
		
		$last_attempt 	=	$this->session->userdata('failed_attempt');
		$failed_ip		=	$this->session->userdata('ip_address');

		$current_ip		=	$this->input->ip_address();

		if(isset($last_attempt) AND $last_attempt+2>time() AND isset($failed_ip) AND $current_ip==$failed_ip){
			log_message('debug','Recording failed attempt inserting process');
			$this->load->library('user_agent');

			$username 	=	$username;
			$ip_address	=	$current_ip;
			$os 		=	$this->agent->platform();
			$browser	=	$this->agent->agent_string().' | Version: '.$this->agent->version();


			$track_record	=	array(	'username'		=>	$username,
										'ip_address'	=>	$ip_address,
										'last_attempt'	=>	time(),
										'attempt_count'	=>	1,
										'browser_info'	=>	$browser,
										'os_info'		=>	$os
										);
							
			$this->notification->slack_notify('Failed attempt of login from IP #'.$failed_ip.' \n Browser# : '.$browser.'\n OS# : '.$os);
			$this->db->replace('failed_login_attempt',$track_record);
			log_message('debug','record added');
			
			return true;
		}
	}


	public function set_user_online($user_id){
		//log_message('error','Setting user online# '.$user_id);
		$this->lib->update('users',array('user_last_active'=>time()),'user_id',$user_id);
		return true;
	}

	public function check_online_status($user_id){
		if(!$user_id){
			return "Offline";
		}

		$check_user 	=	$this->lib->get_row_array('users',array('user_id'=>$user_id,'user_status'=>1));
		if(!$check_user){
			return "Offline";
		}

		if($check_user->user_last_active==''){
			return 'Offiline';
		}

		if($check_user->user_last_active+10>=time()){
			return "Online";
		}else{
			return 'Active '.timespan($check_user->user_last_active,time(),1).' ago';
		}

		
	}

	public function total_online_users($type=''){
		$time 	=	time();
		if($type=='android'){
			$type='android';
		}elseif($type=='ios'){
			$type=='ios';
		}else{
			$type=='web';
		}

		$count_users	=	0;
		$count_users 	=	$this->lib->count_multiple('users',array('user_last_active>='=>$time-10,'user_status'=>1));
		return $count_users;
	}

	public function save_user_activity($username){
		$excluded_url	=	array(
									'update_active_user',
									'getChat',
									'check_active_status',
									'update_new_chat_status',
									'get_new_group_chat',
									'group_msg_mark_read',
									'group_scroll_up',
									'send_group_notification',
								);
		
		if(in_array($this->uri->segment(2),$excluded_url)){
			return false;
		}

		$this->load->library('user_agent');

		$current_ip	=	$this->input->ip_address();
		$os 		=	$this->agent->platform();
		
		if($os=='Unknown Platform'){
			$os = 'Mobile app';
		}

		$browser		=	$this->agent->agent_string().' | Version: '.$this->agent->version().' On OS: '.$os;

		$current_url	=	uri_string();

		$ins_history	=	array(
			'uh_username'	=>	$username,
			'uh_url_visit'	=>	$current_url,
			'uh_time'		=>	time(),
			'uh_device_info'=>	$browser,
			'uh_ip'			=>	$current_ip,
			'uh_status'		=>	1
		);


		$this->db->insert('user_activity_history',$ins_history);
		
	}

	public function token_expire(){
			$this->session->unset_userdata('app_user');
			unset($_SESSION['app_user']);
			//die('now here');
			$msg 		=	array(
			'message'	=>	'User token expired, Due to update in account setting/password',
			'data'		=>	array('message'=>'User token expired, Due to update in account setting/password','auth_token'=>""),
			'response'	=>	"logout",
			'status'	=>	'logout',
			'time'		=>	time()
			);
		
			$header		=	200;	
			$this->output->set_status_header(403);
			echo json_encode($msg);
			exit();
	}
	
		


		
	
}