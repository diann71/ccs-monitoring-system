<?php session_start();

include "../user/nav.php";
include "../database/connector.php";
include "../database/authenticator.php";

if(!isset($_SESSION['idno'])){
    $_SESSION['error'] = "Please log in to view your profile.";
    header("Location: login.php"); 
    exit(); 
}


