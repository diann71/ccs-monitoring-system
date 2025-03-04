<?php 
include "connector.php";

$idno = $_SESSION["idno"];

$result = mysqli_query($mysql, "SELECT * FROM admin WHERE admin_id = '$idno'");
$row = mysqli_fetch_assoc($result);

if($row){
    $idno = $row['admin_id'];
    $lastname = $row['lastname'];
    $firstname = $row['firstname'];
    $midname = $row['midname'];
}

?>  