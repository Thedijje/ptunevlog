<?php
// require APPPATH.'libraries/REST_Controller.php';
defined('BASEPATH') OR exit('No direct script access allowed');
//require APPPATH . 'libraries/REST_Controller.php';
// require APPPATH . 'libraries/Format.php';

class Login extends Api_Controller {

    

	/**
	 * Constructor to initialise all values
	 */
	function __construct(){

		parent::__construct();
        
    }


    public function index_post()
    {
        $data   =   $this->input->post();
        if(!$data){
            
            
            $this->_error    =   "Invalid request or empty parameter";
            $this->_error_occured();
            
            
            
        }

        $email      =   $data['email'];
        $password   =   md5($data['password']);

        $check_user =   $this->lib->get_row_array('users', array('email'=>$email, 'password'=>$password));

        if(!$check_user){

            $this->_error    =   "Invalid Email/password";
            $this->_error_occured();
        }

        $token      =   $this->login->create_jwt($check_user->id);

        if(!$token){
               
            $this->_error    =   "Unable to generate token at the moment, please try again soon";
            $this->_error_occured();
        
        }
        $this->_data    =   array('token'=>$token);
        $this->_bye();
        
    }

}