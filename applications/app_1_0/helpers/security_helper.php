<?php 
/*
*   Author  :   Dheeraj jha
*   Email   :   Dheeraj@thedijje.com 
*   Company :   SeekGeeks
*
*/

function create_csrf(){
    /*
    *   Function will create an for input with csrf token value
    *   Functions needs to have session drive autoloaded
    */
    $randomString = random_string('alnum',20);
    $_SESSION[CSRF_TOKEN]   =   $randomString;
    
    echo "<input class='hidden d-none' type='hidden' name='security_token' value='".$randomString."'>";
}

function validate_csrf(){
    /*
    *   Function will validate from post method
    *   return false if not matched
    */
    if($_POST['security_token']!=$_SESSION[CSRF_TOKEN]){
        return false;
    }

    unset($_SESSION[CSRF_TOKEN]);
    return true;
}