<?php
require APPPATH.'libraries/REST_Controller.php';
defined('BASEPATH') OR exit('No direct script access allowed');
//require APPPATH . 'libraries/REST_Controller.php';
// require APPPATH . 'libraries/Format.php';

class Login extends REST_controller {

    

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
            
            $message    =   array(
                "error_msg"   =>  "Invalid request/missing parameters",
                "success"    =>  false,
                "data"      =>  array()
            );

            $this->response($message);
        }

        $email      =   $data['email'];
        $password   =   md5($data['password']);

        $check_user =   $this->lib->get_row_array('users', array('email'=>$email, 'password'=>$password));

        if(!$check_user){

            $message    =   array(
                "error_msg"     =>  "Invalid Email/password",
                "success"       =>  false,

            );

            $this->response($message);
        }

        $token      =   $this->login->create_jwt($check_user->id);

        if(!$token){
            $message    =   array(
                "error_msg"     =>  "Unable to generate token at the moment, please try again soon",
                "success"       =>  false,
            );
    
            $this->response($message);    
        }

        $message    =   array(
            "error_msg"     =>  "",
            "success"       =>  true,
            "token"         =>  $token
        );

        $this->response($message);
        
    }

}