<?php 
include "connector.php";

$idno = $_SESSION["idno"];

$result = mysqli_query($mysql, "SELECT * FROM admin WHERE idno   = '$idno'");
$row = mysqli_fetch_assoc($result);

if($row){
    $idno = $row['idno'];
    $lastname = $row['lastname'];
    $firstname = $row['firstname'];
    $midname = $row['midname'];
}

?>  