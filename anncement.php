<?php session_start();

include "nav.php";
include "connector.php";
include "authenticator.php";

if(!isset($_SESSION['idno'])){
    $_SESSION['error'] = "Please log in to view your profile.";
    header("Location: login.php"); 
    exit(); 
}


