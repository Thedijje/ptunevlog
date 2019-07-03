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
                "message"   =>  "Invalid request/missing parameters",
                "status"    =>  "error",
                "data"      =>  array()
            );

            $this->response($message);
        }

        

        
    }

}