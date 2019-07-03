<?php
require APPPATH.'libraries/REST_Controller.php';
defined('BASEPATH') OR exit('No direct script access allowed');
//require APPPATH . 'libraries/REST_Controller.php';
// require APPPATH . 'libraries/Format.php';

class Videos extends REST_controller {

    

	/**
	 * Constructor to initialise all values
	 */
	function __construct(){

		parent::__construct();
        $this->login->refresh_auth();
    }


    public function index_get()
    {
        die('hello');
    }

}