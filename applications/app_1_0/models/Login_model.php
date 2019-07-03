<?php
class Login_model extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->helper('jwt');
    }


    /**
     * 
     * token key which should be the variable containing auth token
     * need to pass in header
     * 
     *  */
    private $token_key  =   'auth_token';


    /**
     * 
     * Create jwt token using jwt helper
     * @param int user_id
     * @return string token if success
     * @return false if failure
     * 
     *  */
    public function create_jwt($user_id)
    {
        $user_info  =   $this->lib->get_row_array('users', array('id'=>$user_id));

        if(!$user_info){
            log_message('error','Unable to generate jwt for user#'.$user_id.', no record found');
            return false;
        }


        $payload    =   array(
                        'email'=>$user_info->email,
                        'id'=>$user_info->id
                        );

        return create_jwt($payload);
    }


    public function verify_jwt($token)
    {
        return verify_jwt($token);
    }


    /**
     * 
     * 
     *  */
    public function refresh_auth()
    {
        $apache_header 	=	$this->input->request_headers();
        $token_key      =   $this->token_key;

        if(!isset($apache_header[$token_key])){

			log_message('error','No auth header found or invalid auth header');
            $this->token_expire();
            
        }
        
        $token_value    =   $apache_header[$token_key];

        if(!verify_jwt($token_value)){

            $this->token_expire();
        
        }


        
    }

    private function token_expire(){
        $message    =   array(
            "success"   =>  false,
            "error_message" =>  "Token expired or incorrect"
        );

        $this->output->set_status_header(200);
        $this->output->set_content_type('application/json');
        echo json_encode($message);
        exit();
    }
}