<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require FCPATH . 'vendor/autoload.php';


class Api_Controller extends REST_Controller {

	
	/**
	 * ### holds the 'login' key from 'login_lang'
	 */
	protected $_lang;
	
	/**
	 * #### default: Some error occured, please try again later
	 * @var string
	 */
	protected $_error;

	/**
	 * #### default: Request Completed Successfully
	 * @var string
	 */
	protected $_message;

	/**
	 * #### blank array to store response data
	 * @var mixed
	 */
	protected $_data;

	/**
	 * ### set value as 'logout' to logout any user in a case
	 * @var string
	 */
	protected $_logout = "";

	/**
     * To store return response code when error occurs
	 * ### default: 200
	 * @var integer
	 */
	public $_http_ok = REST_Controller::HTTP_OK;


	/**
     * To store return response code when error occurs
	 * ### default: 200
	 * @var integer
     * 
	 */
	public $_http_error = REST_Controller::HTTP_OK;


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
	protected function _bye(){
		//----------------------------DONT CHANGE ANYTHING BELOW --------------------
		//SUCCESS RETURNS FOR API
		$response_data		=	array(
			
			'message'	=>	$this->_message,
			'data'		=>	$this->_data,
			'success'	=>	true,
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
	protected function _error_occured(){
        $this->_data     = array_merge($this->_data,array('message'=>$this->_error));

		$response_data		=	array(

			'error_message'	=>	$this->_error,
			'data'		=>	$this->_data,
			'success'	=>	false,
			'time'		=>	time()
		);
		$this->response($response_data, $this->_http_error);

	}

    
}
