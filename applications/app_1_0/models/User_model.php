<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	public function country_list(){
		$country_detail				=	array();
		$countries					=	$this->lib->get_table('countries',array('country_name'=>'asc'));
		if($countries){
			foreach($countries as $country){
				$detail =	array(
						'name'	=>	$country->country_name,
						'id'	=>	base64_encode($country->country_id),
						'code'	=>	'+'.$country->country_phonecode
						);
				$country_detail[] 	=	$detail;
			}
		}
		return $country_detail;
	}
	
	public function state_by_country($country_id){
		$state_list		=	array();
		$all_state 		=	$this->lib->get_by_id('states','state_country_id',$country_id);
		if(!$all_state){
			return array();
		}
		foreach($all_state as $state){
			$detail 	=	array(
							'state_id'	=>	base64_encode($state->state_id),
							'state_name'=>	$state->state_name
							);
			$state_list[]=	$detail;
		}
		return $state_list;
	}

	public function city_by_state($state_id){
		$city_list		=	array();
		$all_city 		=	$this->lib->get_by_id('cities','city_state',$state_id);
		if($all_city):
		foreach($all_city as $city){
				$detail 	=	array(
					'city_name'	=>	$city->city_name,
					'city_id'	=>	base64_encode($city->city_id)
				);
				$city_list[]		=	$detail;
		}
		endif;
		return $city_list;
	}

	public function membership_plan(){
		if(ENV == 'development')
			$all_membership 	=	$this->lib->get_multi_where('user_membership',array('membership_id'=>1));
		else
			$all_membership 	=	$this->lib->get_by_id('user_membership','membership_status',1);

		foreach($all_membership as $membership){
			$m_info['membership_id']			=	base64_encode($membership->membership_id);
			$m_info['membership_name']			=	$membership->membership_name;
			$m_info['membership_detail']		=	$membership->membership_description;
			//$m_info['membership_cost']			=	"On";
			$m_info['membership_cost']			=	$membership->membership_cost;
			$m_info['membership_duration']		=	"\x20/year";
			
			
			$m_list[]							=	$m_info;
		}

		return $m_list;
	}

	public function update_city($user_id,$city_id){
		if(!$user_id OR !$city_id){
			log_message('error','unable to update user city due to missing user id or city id, signup using API');
			return false;
		}
		$insert_data	=	array(
			'uc_user_id'	=>	$user_id,
			'uc_city_id'	=>	$city_id,
			'uc_added_at'	=>	time(),
			'uc_status'		=>	1
		);
		$query			=	$this->db->insert('users_city',$insert_data);
		if(!$query){
			log_message('error','Unable to save city info and user info in user_cities due to mysql database error #'.mysql_error());
			return false;
		}
	}

	public function add_city_state($user_id,$city_name,$state_name,$country_id){
		
		
		$this->db->insert('locale_request',	array('lr_user_id'	=>	$user_id,
											'lr_country_id'		=>	$country_id,
											'lr_state_name'		=>	$state_name,
											'lr_city_name'		=>	$city_name,
											'lr_requetsed'		=>	time(),
											'lr_status'			=>	1));
		return $this->db->insert_id();
	}

	public function add_city($user_id,$city_name,$state_id,$country_id){
		
		$this->db->insert('locale_request',array('lr_user_id'	=>	$user_id,
												'lr_state_name'	=>	"",
												'lr_city_name'	=>	$city_name,
												'lr_state_id'	=>	$state_id,
												'lr_country_id'	=>	$country_id,
												'lr_requetsed'	=>	time(),
												'lr_status'		=>	1)
											);
		return $this->db->insert_id();

	}

	public function user_locale($user_id=NULL,$locale_id=NULL){
		$this->db->select('*');
		$this->db->from('locale_request	locale');
		$this->db->join('countries		country',	'locale.lr_country_id	=	country.country_id',	'left');
		$this->db->join('states			state',	'locale.lr_state_id	=	state.state_id',	'left');
		$this->db->join('users			user',	'user.user_id	=	locale.lr_user_id',	'left');
		if($user_id!=NULL){
			$this->db->where('locale.lr_user_id',$user_id);	
		}
		if($locale_id!=NULL){
			$this->db->where('locale.lr_id',$locale_id);
		}

		$this->db->limit(1);
		$this->db->order_by('locale.lr_id','DESC');
		$query 	=	$this->db->get();
		return $query->row();
	}

	public function validate_region($type='states',$region_name,$parent=NULL){
		if($type=='states'){
			$state_exists = $this->lib->get_row_array('states',array('state_name'=>$region_name,'state_country_id'=>$parent));
			if($state_exists){
				return true;
			}
		}

		if($type=='city' AND $parent!=NULL){
			$city_exists 	=	$this->lib->get_row_array('cities',array('city_state'=>$parent,'city_name'=>$region_name));
			if($city_exists){
				return true;
			}
		}

		return false;
	} 

	public function user_info($id=NULL,$count=100,$admin=NULL,$filter=NULL){
		$list_users				=	new stdClass;
		$this->db->select('*');
		$this->db->from('users	user');
		$this->db->join('cities		city',	'city.city_id	=	user.user_city',	'left');
		$this->db->join('countries	country',	'country.country_id	=	user.user_country',	'left');
		$this->db->join('company	comp',	'comp.company_id	=	user.company_id',	'left');
		$this->db->join('status		status',	'status.status_id	=	user.user_status',	'left');
		$this->db->join('pi_payment_type		payment_method',	'payment_method.pt_user_id	=	user.user_id',	'left');
		$this->db->join('user_membership		membership',	'membership.membership_id	=	user.user_membership',	'left');
		
		$this->db->order_by('user.user_id','desc');
		
		if($filter!=NULL AND is_array($filter)){
			foreach($filter as $key=>$value){

				$this->db->where('user.'.$key,$value);
			}
		}

		if($id!=NULL){
			$this->db->or_where('user.user_id',$id);
			$this->db->or_where('user.username',$id);
			if($admin==NULL){
				$this->db->where('user.user_status',1);
			}
			$query				=	$this->db->get();
			if(!$query){
				log_message('Error in Fetching user details, MYSQL query error is '.$this->db->_error_message());
			}

			// if(!file_exists($query->row()->user_display_pic)){
			// 	//	$this->set_default_dp($id);
			// 	//	$query->row()->user_display_pic	=	config_item('user_default_avatar');
			// }
			$user_info	=	$query->row();
			if(!$user_info){
				return $user_info;
			}	
			if(!file_exists($user_info->user_display_pic)){
				$user_info->user_display_pic	=	config_item('user_default_avatar');
			}

			return $user_info;
		}
		if($admin!=NULL){
				$this->db->where('user.user_status',1);
		}
		if(is_array($count)){
			$this->db->limit($count[0],$count[1]);
		}else{
			$this->db->limit($count);
		}
		
		$query	=	$this->db->get();
		
		if(!$query){
			log_message('error','Error in Fetching user details, MYSQL query error is '.mysql_error());
			return $list_users;
		}

		if($query AND $query->num_rows()>0){
			return $list_users	=	$query->result();
		}else{
			
			return $list_users;
		}
	}

	public function user_city($user_id=NULL){
		if(!$user_id){
			return false;
		}
		$this->db->select('*');
		$this->db->from('users_city	ucity');
		$this->db->join('cities		city',	'city.city_id	=	ucity.uc_city_id',	'left');
		$this->db->join('states		state',	'state.state_id	=	city.city_state',	'left');
		$this->db->join('countries	country',	'state.state_country_id	=	country.country_id',	'left');
		if($user_id!=NULL){
			$this->db->where('ucity.uc_user_id',$user_id);
		}
		$this->db->order_by('city.city_name','asc');
		$query		=	$this->db->get();
		return $query->result();
	}

	public function user_payment($user_id=NULL){
		$this->db->select('*');
		$this->db->from('user_payment	payment');
		$this->db->join('users		user',	'payment.up_user_id	=	user.user_id',	'left');
		$this->db->where('payment.up_status',1);
		if($user_id!=NULL){
			$this->db->where('user.user_id',$user_id);
			$query	=	$this->db->get();
			if(!$query){
				log_message('error','User and payment info not obtained due to db error# '.mysql_error());
				return false;
			}
			return $query->row();
		}
		$this->db->order_by('up','desc');
		$query	=	$this->db->get();
		if(!$query){
				log_message('error','User and payment info not obtained due to db error# '.mysql_error());
				return false;
		}
		return $query->result();
	}

	public function user_document_list($user_id){

	}

	public function following_list($user_id){
		if(!$user_id){
			return false;
		}
		$list_following 	=	$this->lib->get_multi_where('user_follow',array('uf_user_id'=>$user_id,'uf_status'=>1));
		if(!$list_following){
			return array();
		}
		foreach($list_following as $following){
			$user[]	=	$following->uf_followed;
		}
		return array_unique($user); 
	}

	public function city_to_country($city_id){
		if(!$city_id){
			return false;
		}
	}

	public function post_img_resize($post_image_path,$max_width=1000){
		if(!file_exists($post_image_path)){
			return false;
		}
		if(!getimagesize($post_image_path) OR !$post_image_path or !file_exists($post_image_path)){
			log_message('error','Message : post image resizing failed due to invalid image or image not existing, image path is '.$post_image_path);
			return false;
		}

		$img_data = getimagesize($post_image_path);
		$width = $img_data[0];
		$height = $img_data[1];

		if($width>$max_width){
			$this->lib->image_resize($post_image_path,$max_width);
		}elseif($height>$max_width){
			$this->lib->image_resize($post_image_path,NULL,$max_width);
		}
	}

	public function update_user_online($user_id){
		if(!$user_id){
			return false;
		}
		$this->lib->update('users',array('user_last_active'=>time()),'user_id',$user_id);
	}

	public function payment_methods($user_id){
		if(!$user_id){
			return false;
		}

		$methods 	=	$this->lib->get_row_array('pi_payment_type',array('pt_user_id'=>$user_id,'pt_status'=>1));
		if(!$methods){
			//	Payment method does not exists, so creating payment method
			$this->insert_default_payment($user_id);

			return $this->payment_methods($user_id);
		}

		return $methods;
	}

	public function auto_follow_support($user_id){
		if(!$user_id){
			return false;
		}
		$this->load->model('follow_model');
		$user_id 		= 	base64_decode($user_id);
		$support_id 	= 	$this->lib->get_row('users','username','MobiHubSupport','user_id');
		$check_follow 	=	$this->follow_model->check_follow_status($user_id,$support_id);
		if($check_follow){
			log_message('debug','User '.$user_id.' already following @MobiHubSupport');
			return true;
		}
		$insert_data 	=	array(
					'uf_user_id'		=>	$user_id,
					'uf_followed'		=>	$support_id,
					'uf_followed_at'	=> 	time(),
					'uf_status'     	=>  1
				);
		$query	=	$this->db->insert('user_follow',$insert_data);
		if(!$query){
			log_message('error','Error auto following Mobi-Support for user id #'.$user_id.' due to db error# '.mysql_error());
			return false;
		}
		return true;
	}

	function generate_verif_token($user_id){
		if(!$user_id){
			return false;
		}
		$user_info = $this->lib->get_row_array('users',array('user_id'=>$user_id));
		if(!$user_info){
			return false;
		}

		$this->load->helper('string');

		$random_string	= random_string('md5');
		$email			= $user_info->user_email;
		$final_hash 	= md5($random_string.'-'.$email);
		$this->db->replace('verification_token', array('vt_user_id'=>$user_id,'vt_token'=>$final_hash));
		return $final_hash;
	}
	
	public function verify_token($token){
		if(!$token){
			return false;
		}

		$verif_data =	 $this->lib->get_row_array('verification_token',array('vt_token'=>$token));
		if(!$verif_data){
			log_message('error','Invalid verification_token for token #'.$token);

			return false;
		}

		$user_info 	=	$this->lib->get_row_array('users',array('user_id'=>$verif_data->vt_user_id));
		if(!$user_info){
			return false;
		}

		return $user_info;
	}

	public function log_activity($context,$id,$user_id,$key=''){
		$user_info 	=	$this->lib->get_row_array('users',array('user_id'=>$user_id));

		//	Ignore self profile
		if($context=='users' AND $user_id==$id){
			return false;
		}

		//	Ignore self company
		if($context=='company' AND $user_info->user_company==$id){
			return false;
		}

		// Ignore self post
		if($context=='user_posts'){
			$post_info 	=	$this->lib->get_row_array('user_posts',array('post_user_id'=>$user_id,'post_id'=>$id));
			if($post_info){
				log_message('debug','not logging post for '.$user_id.' for post '.base64_encode($id));
				return false;
			}
		}
		
		if($key!==''){
			$key 	=	str_replace(array(' ','%20'),',',$key);
			
		}

		$activity 	=	array(
							'ua_user_id'	=>	$user_id,
							'ua_context'	=>	$context,
							'ua_context_id'	=>	$id,
							'ua_keywords'	=>	$key,
							'ua_time'		=>	time(),
							'ua_status'		=>	1
						);
		$this->db->insert('user_activity',$activity);
		
		return true;
	}

	/*
	*	Manage model
	*/
	
	public function is_admin($user_id){
		
		/*
		*	Author : @Thedijje
		*	This function check if this user is a replica of admin user
		*/

		if(!$user_id){
			return false;
		}

		$admin_id 	=	$this->lib->get_row_array('admin',array('front_user'=>$user_id));
		if($admin_id){
			return true;
		}else{
			return false;
		}

	}

	
	/*
	*	Add default payment method
	*/

	public function insert_default_payment($user_id){
		
		$pm['pt_user_id']		=	$user_id;
		$pm['pt_type_name']		=	'default';
		$pm['pt_description']	=	'Contact for details';
		$pm['pt_created_at']	=	time();
		$pm['pt_status']		=	1;

		$query 	=	$this->db->insert('pi_payment_type',$pm);
		log_message('error','Payment method created for user# '.$user_id);
		if($query){
			return $this->db->insert_id();
		}
		log_message('error','default payment method was not added for the user #'.$user_id);
		return false;


	}


	public function banned_words(){
		$bad_words 			=	$this->lib->get_table('ban_words',array('bw_word'=>'asc'));
		if(!$bad_words){
			return "";

		}

		foreach($bad_words as $words){
			$word_list[]	=	"'".addslashes($words->bw_word)."'";
		}

		return implode(',',$word_list);
	}


	public function set_default_dp($user_id){
		$this->lib->update('users',array('user_display_pic'=>config_item('user_default_avatar')),'user_id',$user_id);
		return true;
	}
	public function custom_validation_rules($data) {

		
        $form_validation = array(
            array(
                'field' => 'first_name',
                'label' => 'first name',
                'rules' => 'required|trim|min_length[3]|max_length[30]'
            ),
            array(
                'field' => 'last_name',
                'label' => 'last name',
                'rules' => 'required'
            ),
           /* array(
                'field' => 'phone',
                'label' => 'phone',
                'rules' => 'required|trim|min_length[10]|is_unique[users.user_mobile]'
			),*/
			array(
                'field' => 'phone',
                'label' => 'phone',
                'rules' => array('required', 'numeric', 'min_length[10]', 'max_length[13]', array('mobile', array($this, 'unique_mobile_number'))),
                'errors' => array('mobile' => "This field must contain unique value !!"),
            ),
            array(
                'field' => 'select_state',
                'label' => 'state',
                'rules' => 'required'
            ), 
			array(
                'field' => 'city',
                'label' => 'city',
                'rules' => 'required'
			), 
			array(
                'field' => 'country',
                'label' => 'country',
                'rules' => 'required'
			), 
			
            array(
                'field' => 'company',
                'label' => 'company',
                'rules' => 'required'
            )
		); 
		if(empty($data["user_id"])){
			$form_validation[] = array(
                'field' => 'username',
                'label' => 'username',
                'rules' => 'required'
            );
			$form_validation[] = array(
					'field' => 'user_email',
					'label' => 'email',
					'rules' => 'required|trim|valid_email|is_unique[users.user_email]'
			);
		}

        $this->load->library('form_validation');
        $this->form_validation->set_rules($form_validation);
        return ($this->form_validation->run() === false) ? false : true;
	}
	public function unique_mobile_number($mobile) {
	//	dd($_POST["user_id"]);
         $type = empty($_POST["user_id"])?"insert":"update";
        $current_id = !empty($_POST["user_id"])?base64_decode($_POST["user_id"]):false;
        $exist_number = $this->db->select('user_id,user_mobile')->where('user_mobile', $mobile)->get('users')->row();

        if ($type == 'insert') {
            return $exist_number ? FALSE : TRUE;
        } else {
            return ($exist_number && $exist_number->user_id != $current_id) ? FALSE : TRUE;
        }
    }

}
