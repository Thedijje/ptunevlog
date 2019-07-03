<?php
// use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
// require APPPATH . 'libraries/REST_Controller.php';
// require APPPATH . 'libraries/Format.php';

class Response extends Api_Controller {

    

	/**
	 * Constructor to initialise all values
	 */
	function __construct(){

		parent::__construct();
		
		
		$this->_error 		=   "Some error occured, please try again later";
		$this->_message 	=   "Request Completed Successfully";
		$this->_data 		=   array();

	}

    
	/**
	 * Function to call to end a successful API hit
     * The following variables can be changed:
     * @var $this->_lang for language keys
     * @var $this->_message for api message
     * @var $this->_data for data to be sent in array/object format
	 *  */	
	private function _bye(){
		//----------------------------DONT CHANGE ANYTHING BELOW --------------------
		//SUCCESS RETURNS FOR API
		$response_data		=	array(
			'language'	=>	$this->_lang,
			'message'	=>	$this->_message,
			'data'		=>	$this->_data,
			'response'	=>	"",
			'status'	=>	'success',
			'time'		=>	time()
		);
		$this->response($response_data, $this->_http_ok);
    }
    
    
	/**
	 * Function to call to end a successful API hit
     * The following variables can be changed:
     * @var $this->_lang for language keys
     * @var $this->_error for error message
     * @var $this->_data for data to be sent in array/object format
	 *  */
	private function _error_occured(){
        $this->_data     = array_merge($this->_data,array('message'=>$this->_error));

		$response_data		=	array(
			'language'	=>	$this->_lang,
			'message'	=>	$this->_error,
			'data'		=>	$this->_data,
			'response'	=>	"",
			'status'	=>	"error",
			'time'		=>	time()
		);
		$this->response($response_data, $this->_http_error);

	}

    
    public function index_get(){
        $this->response(array('status'=>'awesome'), REST_Controller::HTTP_OK);
    }

}