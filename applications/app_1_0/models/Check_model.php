<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * Model to check standard input values and custom variable for each input
 */
class Check_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

    public function input($input_type = NULL, $value = NULL){
        if($input_type == NULL || $value == NULL){
            return false;
        }
        switch ($input_type) {
            case 'email':
                
                $value = filter_var($value, FILTER_SANITIZE_EMAIL);

                if (!filter_var($value, FILTER_VALIDATE_EMAIL)){
                    return false;
                }
                break;
            
            case 'username':
                //check for minimum 5 length and string sanitization and alphanumeric with underscore allowed

                if(!filter_var($value, FILTER_SANITIZE_STRING)){
                   return false;
                }else if( strlen($value) < 5){
                   return false;
                }else if( !preg_match('/^[a-zA-Z0-9_]*$/',$value) ){
                   return false;
                }
                break;
            
            default:
                return false;
        }
        return true;
    }
}