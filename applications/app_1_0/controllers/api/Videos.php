<?php
require APPPATH.'libraries/REST_Controller.php';
defined('BASEPATH') OR exit('No direct script access allowed');
//require APPPATH . 'libraries/REST_Controller.php';
// require APPPATH . 'libraries/Format.php';

class Videos extends Api_Controller {

    

	/**
	 * Constructor to initialise all values
	 */
	function __construct(){

		parent::__construct();
        $this->login->refresh_auth();
        $this->load->model(array('video_model'=>'videos'));
    }


    public function index_get()
    {
        $query          =   $this->input->get();
        
        $videos         =   $this->videos->list($query);
        $this->_data    =   $videos ?? new stdClass;
        $this->_bye();
    }

    public function detail_get()
    {
        $video_id   =   $this->input->get('id');

        if(!$video_id){

            $this->_error   =   "Empty video id";
            $this->_error_occured();

        }

        $videos         =   $this->videos->detail($video_id);
        $this->_data    =   $videos;
        $this->_bye();


    }

}