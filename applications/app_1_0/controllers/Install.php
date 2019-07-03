<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Install extends Web_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Install_model', 'install');
	}
	
	public function index(){

        //  check for configuration table
        $config     =   $this->install->check('config');
        if($config){
            echo "\n\t-\t`config` table added.<br /><br />";
        }else{
            echo "\n\t-\t`config` table already added or not available.<br /><br />";
        }


        //  install contact table
        $contact     =   $this->install->check('contact');
        if($contact){
            echo "\n\t-\t`contact` table added.<br /><br />";
        }else{
            echo "\n\t-\t`contact` table already added or not available.<br /><br />";
        }


        //  install admin table
        $admin     =   $this->install->check('admin');
        if($admin){
            echo "\n\t-\t`admin` table added.<br /><br />";
        }else{
            echo "\n\t-\t`admin` table already added or not available.<br /><br />";
        }

        //  install admin_roles table
        $admin_roles     =   $this->install->check('admin_roles');
        if($admin_roles){
            echo "\n\t-\t`admin_roles` table added.<br /><br />";
        }else{
            echo "\n\t-\t`admin_roles` table already added or not available.<br /><br />";
        }


        //  install status table
        $status     =   $this->install->check('status');
        if($status){
            echo "\n\t-\t`status` table added.<br /><br />";
        }else{
            echo "\n\t-\t`status` table already added or not available.<br /><br />";
        }


        //  install users table
        $users     =   $this->install->check('users');
        if($users){
            echo "\n\t-\t`users` table added.<br /><br />";
        }else{
            echo "\n\t-\t`users` table already added or not available.<br /><br />";
        }
        exit();
	}
    
}
