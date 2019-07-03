<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require FCPATH . 'vendor/autoload.php';


class Web_Controller extends CI_Controller {

    
	function __construct(){
        parent::__construct();
		$this->_settings	=	$this->lib->config_list();
	}
	
	/**
	 * Function to render for the from website
	 */
    protected function _render($view,   $data = array(),	$render=FALSE){
		
		$data['config'] 	=   $this->_settings;
        
        if($render){
            return $this->load->view($view,$data);
		}
		
		
        $this->load->view('front/includes/header',$data);
		$this->load->view($view,$data);
		$this->load->view('front/includes/footer',$data);

	}
	
	/**
	 * Function to render admin panel if any
	 */
    protected function _render_admin($view,   $data = array(),	$render=FALSE){

		$data['config'] 	=   $this->_settings;
        
        if($render){
            return $this->load->view($view,$data);
		}
		
        $this->load->view('admin/includes/header',$data);
		$this->load->view($view,$data);
		$this->load->view('admin/includes/footer',$data);

	}
	
	/**
	 * Function to render user panel if any
	 */
    protected function _render_user($view,   $data = array(),	$render=FALSE){

		$data['config'] 	=   $this->_settings;
        
        if($render){
            return $this->load->view($view,$data);
		}
		
        $this->load->view('user/includes/header',$data);
		$this->load->view($view,$data);
		$this->load->view('user/includes/footer',$data);
	}

}

class Api_Controller extends REST_Controller {

	
	/**
	 * ### holds the 'login' key from 'login_lang'
	 */
	private $_lang;
	
	/**
	 * #### default: Some error occured, please try again later
	 * @var string
	 */
	private $_error;

	/**
	 * #### default: Request Completed Successfully
	 * @var string
	 */
	private $_message;

	/**
	 * #### blank array to store response data
	 * @var mixed
	 */
	private $_data;

	/**
	 * ### set value as 'logout' to logout any user in a case
	 * @var string
	 */
	private $_logout = "";

	/**
     * To store return response code when error occurs
	 * ### default: 200
	 * @var integer
	 */
	private $_http_ok = REST_Controller::HTTP_OK;


	/**
     * To store return response code when error occurs
	 * ### default: 200
	 * @var integer
     * 
	 */
	private $_http_error = REST_Controller::HTTP_OK;


	/**
	 * Constructor to initialise all values
	 */
	function __construct(){
        parent::__construct();
        
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

    
}
