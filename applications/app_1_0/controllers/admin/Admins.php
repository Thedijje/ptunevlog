<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admins extends Web_Controller {
	function __construct(){
		parent::__construct();
		$this->login->check_admin_login();
		$this->load->model(array('Admin_model'=>'admin'));
		//$this->permission->check_admin_permission();
	}

	public function index($date=NULL){
		$data['title']			=	'Admins List';
		$data['heading']		=	'Admins List';
		$admin_id				=	admin_user('id');

		$data['roles'] 	=	$this->lib->get_table('admin_roles');
		$data['admins']			=	$this->admin->list();
        
        $this->_render_admin('admin/admin',$data);
	}

	public function save(){

		$data = $this->input->post();
		
		
		if($data['new_password']!=$data['confirm_password']){
			$this->lib->redirect_msg('Confirm Password did not match' ,'danger','admin/admins');
		}
		//check email unique
		$check_email = $this->lib->get_row_array('admin',array('email'=>$data['email']));
		if($check_email){
			$this->lib->redirect_msg('Email Already an existing admin.','danger','admin/admins');
		}

		//check email mobile
		$check_mobile = $this->lib->get_row_array('admin',array('admin_mobile'=>$data['mobile']));
		if($check_mobile){
			$this->lib->redirect_msg('Mobile number already exist.','danger','admin/admins');
		}

		$new_password = password_hash($data['new_password'], PASSWORD_DEFAULT);

		$ins = array();
		$ins['name']			=	$data['name'];
		$ins['email']			=	$data['email'];
		$ins['admin_mobile']	=	$data['mobile'];
		$ins['password'] 		= 	$new_password;
		$ins['role']			=	base64_decode( $data['role']);
		$ins['added_on'] 		=	time();
		$ins['status']	 		=	1;
		$insert_admin 			=	$this->db->insert('admin',$ins);
		if(!$insert_admin ){
			$this->lib->redirect_msg('Unable to add admin, please try again soon','danger','admin/admins');
		}
		$admin_id 				=	$this->db->insert_id();

		if($admin_id ){
			//del previous created admin
			//$del_admin		=	$this->lib->del('admin','id',$admin_id);
			$this->lib->redirect_msg('Admin added successfully','success','admin/admins');
		}else{
			$this->lib->redirect_msg('Admin could not be added successfully','danger','admin/admins');
        }

	}

	public function edit($admin_id){
		if(!$admin_id){
			$this->lib->redirect_msg('Invalid admin id','danger','admin/admins/list');
		}

		$admin_id =	base64_decode($admin_id);
		$data['admin_info']	=	$this->lib->get_row_array('admin',array('id'=>$admin_id));
		$this->load->view('admin/ajax/edit_admin',$data);
	}

	public function update(){
		$data = $this->input->post();
		$admin_id =	base64_decode($data['id']);
		//check email unique

		$check_email = $this->lib->get_row_array('admin',array('email'=>$data['email'],'id!='=>$admin_id));

		if($check_email){
			$this->lib->redirect_msg('Email Already an existing admin','danger','admin/admins/list');
		}

		$admin_info 			=	$this->lib->get_row_array('admin',array('id'=>$admin_id,'status'=>1));


		//check email mobile
		$check_mobile = $this->lib->get_row_array('admin',array('admin_mobile'=>$data['mobile'],'id!='=>$admin_id));
		if($check_mobile){
			$this->lib->redirect_msg('Mobile number already exist.','danger','admin/admins/list');
		}

		
		$ins 					=	array();
		$ins['name']			=	$data['name'];
		$ins['email']			=	$data['email'];
		$ins['admin_mobile']	=	$data['mobile'];
		$ins['role']			=	base64_decode($data['role']);
		$update 				=	$this->lib->update('admin',$ins,'id',$admin_id);
		if($update){
			if($admin_info->role!=$ins['role']){
				//	role is updated so need to mail.
				
				/*
		            Record Activity
		        */
		    	$this->activity->save_activity(17,$admin_id,'update','Admin role/info updated from '.$admin_info->role.' to '.$ins['role']);

				$this->admin->notify_role_update($admin_id,$admin_info->role);
			}else{
				/*
		            Record Activity
		        */
		    	$this->activity->save_activity(17,$admin_id,'update','Admin info updated');
			}
			$this->lib->redirect_msg('Admin Details Updated successfully','success','admin/admins/list');
		}	
	}

	public function del_admin($admin_id){
		if(!$admin_id){
			$this->lib->redirect_msg('Invalid admin id','danger','admin/admins/list');
		}
		$admin_id 		=	base64_decode($admin_id);
		$admin_info		=	$this->lib->get_row_array('admin',array('id'=>$admin_id));
		$del_admin		=	$this->lib->del('admin','id',$admin_id);
		if(!$del_admin){
			$this->lib->redirect_msg('Error deleting admin','error','admin/admins/list');
		}	

		//del it's front user
		$last_reset 	=	$this->lib->get_row('users','user_id',$admin_info->front_user,'last_password_update');
		if($last_reset=''){
			$new_reset 	=	1;
		}else{
			$new_reset 	=	$last_reset+1;
		}
		$del_user 		=	$this->lib->update('users',array('user_status'=>9,'last_password_update'=>$new_reset),'user_id',$admin_info->front_user);

		if(!$del_user){
			$this->lib->redirect_msg('Error deleting front user','error','admin/admins/list');
		}

		/*
            Record Activity
        */
    	$this->activity->save_activity(17,$admin_id,'delete','Admin deleted');

		$this->lib->redirect_msg('Admin Deleted successfully','success','admin/admins/list');

	}

	public function add_admin(){
		$this->load->view('admin/ajax/add_admin',$data);
	}

	public function reset_pass(){
        $admin_id     =    $this->input->get('admin_id');
        $cur_user    =    admin_user('id');
        
        if(!($cur_user==1 OR $cur_user==5) OR !($admin_id)){ 
            $this->lib->display_alert('Invalid request, please close this dialoge and refresh page','danger','times-circle');
            exit();
        }


        if(base64_decode($admin_id)==$cur_user){
            $this->lib->display_alert('You can not reset password for yourself, please use change password in setting to update it','danger','times-circle');
            exit();
        }
        $this->load->view('admin/ajax/reset_admin_password',array('admin_id'=>$admin_id));


    }

    public function reset_password(){
        $data        =    $this->input->post();
        if($data['password']!=$data['confirm_password']){
            $this->lib->redirect_msg('Confirm Password did not match' ,'danger','admin/admins/list');
        }
        //dd($data);
        $new_password     =    password_hash($data['password'], PASSWORD_DEFAULT);

        $admin_id         =    base64_decode($data['admin_id']);

        $reset_pass     =    $this->lib->update('admin',array('password'=>$new_password),'id',$admin_id);

        if(!$reset_pass){
            $this->lib->redirect_msg('Problem occured while password reset, please try again' ,'danger','admin/admins/list');
        }        

		/*
            Record Activity
        */
    	$this->activity->save_activity(17,$admin_id,'update','Admin Password reset');
        $this->lib->redirect_msg('Password reset successfully' ,'success','admin/admins/list');

    }

	public function link_users(){
		$admin_id 	=	$this->input->get('admin_id');
		if(!$admin_id){
			$this->lib->display_alert('Invalid request');
			exit();
		}
		$admin_id 	=	base64_decode($admin_id);
		$user_info	=	$this->manager->linked_user($admin_id);
		if($user_info){
			$this->lib->display_alert('User @'.$user_info->username.' is already linked to this admin as front user, please refresh page and try again');
			exit();
		}

		$admin_info =	$this->lib->get_row_array('admin',array('id'=>$admin_id));
		$this->load->view('admin/ajax/link_user',array('admin_info'=>$admin_info));
	}

	public function check_email(){
		$data		=	$this->input->post('user_email');
		if(!$data){
			$response['types']	=	'danger';
			$response['message']=	'No email provided, please provide a valid email';
			echo json_encode($response);
			exit();
		}

		$email 		=	$data;
		$check_user =	$this->lib->get_row_array('users',array('user_email'=>$email));
		if(!$check_user){
			$response['types']	=	'danger';
			$response['message']=	'User does not exists with this email address, please create a user and then link account.';
			echo json_encode($response);
			exit();
		}

		$check_if_linked 		=	$this->lib->get_row_array('admin',array('front_user'=>$check_user->user_id));
		if($check_if_linked){
			$response['types']	=	'danger';
			$response['message']=	'User with this email address already linked with admin : '.$check_if_linked->name;
			echo json_encode($response);
			exit();
		}

		$response['types']	=	'success';
		$response['message']=	'User available with this email address and ready to link.';
		echo json_encode($response);
		exit();

	}

	public function submit_link_user(){
		$data 			= 	$this->input->post();
		$user_email		=	$data['user_email'];
		$admin_id 		=	base64_decode($data['admin_id']);

		$check_user 	=	$this->lib->get_row_array('users',array('user_email'=>$user_email));

		if(!$check_user){
			$this->lib->redirect_msg('User does not exists with this email address, please create a user and then link account.','danger','admin/admins/list');
		}

		$check_if_linked =	$this->lib->get_row_array('admin',array('front_user'=>$check_user->user_id));
		if($check_if_linked){
			$this->lib->redirect_msg('User with this email address already linked with admin : '.$check_if_linked->name,'danger','admin/admins/list');
		}

		$link_user 		=	$this->lib->update('admin',array('front_user'=>$check_user->user_id),'id',$admin_id);

		if($link_user){
			$this->lib->redirect_msg('User '.$check_user->user_email.' linked with admin successfully','success','admin/admins/list');
		}
		
	}


	public function toggle_suspend($admin_id){
		$link = $this->input->get('url');
		if($link=='inner'){
			$redirect_url='admin/admins/list';
		}else{
			$redirect_url='admin/admins/list';
		}
		if(!$admin_id){
			$this->lib->redirect_msg('Invalid request, please try again','warning',$redirect_url);
		}
		$admin_info 	=	$this->lib->get_row_array('admin',array('id'=>base64_decode($admin_id)));
		/*
			Activity Record
		*/

		if($admin_info->status==1){
			$new_status 	=	7;
			$this->activity->save_activity(1,base64_decode($admin_id),'update','User Suspended');
		}elseif($admin_info->status==7){
			$new_status 	=	1;
			$this->activity->save_activity(1,base64_decode($admin_id),'update','User Activated');
		}
		if(!$new_status){
			$this->lib->redirect_msg('Admin status is not suspended or active, make sure user is in correct state','warning',$redirect_url);
		}
		$this->lib->update('admin',array('status'=>$new_status),'id',base64_decode($admin_id));


		$this->lib->redirect_msg('Admin status updated successfully','success',$redirect_url);
	}

}
