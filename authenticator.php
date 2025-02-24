<?php 
include "connector.php";

$idno = $_SESSION["idno"];

$result = mysqli_query($mysql, "SELECT * FROM students WHERE idno = '$idno'");
$row = mysqli_fetch_assoc($result);

if($row){
    $idno = $row['idno'];
    $lastname = $row['lastname'];
    $firstname = $row['firstname'];
    $midname = $row['midname'];
    $course = $row['course'];
    $year = $row['year'];
    $profile = $row['profile'];
    $session = $row['session'];
}

?>  