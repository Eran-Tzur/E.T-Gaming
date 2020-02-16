<?php

require_once 'app/helpers.php';
sess_start('digg');

if( ! user_verify() ){
  
  header('location: /signin.php');
  exit;
  
}

$sm = '';
$pid = trim(filter_input(INPUT_GET, 'pid', FILTER_SANITIZE_STRING));

if( $pid && is_numeric($pid) ){
  
  $uid = $_SESSION['user_id'];
  $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);
  $pid = mysqli_real_escape_string($link, $pid);
  $sql = "DELETE FROM posts WHERE id = $pid AND user_id = $uid";
  $result = mysqli_query($link, $sql);
  
  if( $result && mysqli_affected_rows($link) == 1 ){
    
    $sm = '?sm=Post deleted';
    
  }
  
}

header('location: /blog.php' . $sm);
exit;