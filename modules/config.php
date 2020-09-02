<?php
//CONFIGURATION MODULE USED THROUGHOUT THE SYSTEM
$_CONFIG = array(

  'lang'          =>  'en',         // Language
  'sql_host'      =>  'localhost',  // SQL Hostname
  'sql_db'        =>  'uol-walking-tour',    // SQL Database
  'sql_user'      =>  'uol-admin',       // SQL Username
  'sql_password'  =>  '12345678',           // SQL Password
  'su_username'   =>  'admin',       // SuperUser username
  'server_path'   =>  '/home/ud8462avu8pk/public_html/app/uol-walking-tour'

);

// Language chooser
require 'lang-'.$_CONFIG['lang'].'.php';
$_LANG = $LANG[$_CONFIG['lang']];

// SQL connection starter
$db_hostname = $_CONFIG['sql_host'];
$db_database = $_CONFIG['sql_db'];
$db_username = $_CONFIG['sql_user'];
$db_password = $_CONFIG['sql_password'];
$con = mysqli_connect($db_hostname,$db_username, $db_password, $db_database);
if (!$con) die("Unable to connect to MySQL: ".mysqli_connect_error());

// Session starter
session_start();
mb_internal_encoding('UTF-8');

// Functions
    function throw_success($msg){
      echo '<div class="notification"><p class="success">'.$msg.'</p></div>';
    }
    function throw_error($msg){
      echo '<div class="notification"><p class="error">'.$msg.'</p></div>';
    }
function utf8ize($d) {
  if (is_array($d)) {
     foreach ($d as $k => $v) {
       $d[$k] = utf8ize($v);
     }
  } else if (is_string ($d)) {
     return utf8_encode($d);
  }
   return $d;
}

?>
