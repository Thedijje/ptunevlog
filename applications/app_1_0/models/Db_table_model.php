<?php
class Db_table_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }
    
    public function check($table_name){
        if (!$this->db->table_exists($table_name)){
            $file_name  =   FCPATH.'applications'.DIRECTORY_SEPARATOR.'sql'.DIRECTORY_SEPARATOR.$table_name.'.sql';
            if ($this->isEnabled('shell_exec')) {

                //execute the file
                $this->execute_mysql($file_name);
            }else{
                echo "Shell scripting not available. Execute ".$file_name." first";
                exit();
            }
        }
        return true;
    }
    

    private function isEnabled($func) {
        return is_callable($func) && false === stripos(ini_get('disable_functions'), $func);
    }

    
    public function execute_mysql($file_name){
        // $CI = & get_instance();
        $templine = '';
        // Read in entire file
        $lines = file($file_name);
        dd($lines, true);
        foreach($lines as $line) {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;
    
            // Add this line to the current templine we are creating
            $templine.=$line;
    
            // If it has a semicolon at the end, it's the end of the query so can process this templine
            if (substr(trim($line), -1, 1) == ';') {
                // Perform the query
                $this->db->query($templine);
                // Reset temp variable to empty
                $templine = '';
            }
        }
    }
    
}