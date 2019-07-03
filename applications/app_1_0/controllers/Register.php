<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends Web_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	
	public function index()
	{
		// var_dump($this->config->item());
		// foreach ($variable as $key => $value) {
		// 	# code...
		// }
		// exit();
		// $this->output->enable_profiler(true);
		$this->load->view('welcome_message');

	}

	public function config($key)
	{
		if(!$key || $key==''){
			return false;
		}
		return $this->config->item($key);
	}
}
