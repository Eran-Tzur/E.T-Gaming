<?php

require_once 'database_config.php';

if( ! function_exists('old') ){
  
  /**
   * Keeps form inputs values.
  * @param string $fn field name.
  * @return string The form inputs value.
  */
  function old($fn){
    return $_REQUEST[$fn] ?? '';
  }
  
}

if( ! function_exists('csrf_token') ){
  
  /**
   * Generate and store hash random string.
  * @return string The random string.
  */
  function csrf_token(){
    $token = sha1( rand(1, 1000) . '$$' . date('H.i.s') . 'digg' );
    $_SESSION['csrf_token'] = $token;
    return $token;
  }
  
}

if( ! function_exists('user_verify') ){
  
  /**
   * Check if matchs to the auth logged in.
  * @return bool.
  */
  function user_verify(){
    
    $verify = false;
    
    if( isset($_SESSION['user_id']) ){
      
      if( isset($_SESSION['user_ip']) && $_SERVER['REMOTE_ADDR'] == $_SESSION['user_ip'] ){
        
        if( isset($_SESSION['user_agent']) && $_SERVER['HTTP_USER_AGENT'] == $_SESSION['user_agent'] ){
          
          $verify = true;

        }
        
      }
      
    }
    
    return $verify;
    
  }
  
}

if( ! function_exists('sess_start') ){
  
  /**
   * Start the session, optional change the session name.
  * @param [string] $name session name.
  * @return void.
  */
  function sess_start($name = null){

    if( $name ) session_name($name);
    session_start();
    session_regenerate_id();

  }
  
}

if( ! function_exists('email_exist') ){
  
  /**
   * Check if is taken.
  * @param $link mysqli object.
  * @param $email string The value to check.
  * @return bool.
  */
  function email_exist($link, $email){

    $exist = false;

    $sql = "SELECT email FROM users WHERE email = '$email'";
    $result = mysqli_query($link, $sql);

    if( $result && mysqli_num_rows($result) == 1 ){

      $exist = true;

    }

    return $exist;

  }
  
}