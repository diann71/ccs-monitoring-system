<?php 
include "connector.php";

    if(isset($_SESSION['username'])){
        $idno = $_SESSION['idno'];
        $lastname = $_SESSION['lastname'];
        $firstname = $_SESSION['firstname'];
        $midname = $_SESSION['midname'];
        $course = $_SESSION['course'];
        $year = $_SESSION['year'];
        $password = $_SESSION['password'];
    }
    else{
        $_SESSION['error'] = "User not logged in";
    }
?>  