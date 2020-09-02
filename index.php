<?php
  require 'modules/config.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Content Management System - UoL Artwork</title>
    <link rel="stylesheet" href="css/main.css" />
    <meta name="description" content="Backend system for the University of Liverpool Artwork Walking Tour App">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="theme-color" content="#2979ff" />
    <link rel="shortcut icon" href="https://www.liverpool.ac.uk/images/favicon.ico">
    <link rel="apple-touch-icon" href="www.liverpool.ac.uk/images/apple-touch-icon.png" />
    <link rel="icon" sizes="192x192" href="www.liverpool.ac.uk/images/apple-touch-icon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500" rel="stylesheet">
  </head>
  <body>

    <?php

    if(isset($_GET["logout"])){ // If user want to log out

        session_destroy();
        throw_success($_LANG['LOGOUT_SUCCESS']);

    }


    if ((!@$_SESSION['is_logged'] || isset($_GET["logout"])) && @$_GET['p'] != 'register') {  // If user is not logged in

      include('modules/login.php');
  echo 'YES';
    } else if(!isset($_GET['p']) || @$_GET['p'] == 'main'){ // If user is logged in and main page

      include('pages/main.php');

    } else if(file_exists("pages/".$_GET['p'].".php")){ // If other page

      include("pages/".$_GET['p'].".php");

    } else{ // If 404

      http_response_code(404);
      die;

    }

    mysqli_close($con);

    ?>

   </body>
</html>
