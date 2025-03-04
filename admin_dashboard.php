<?php
session_start();
include "connector.php";
include "admin_nav.php";
include "admin_auth.php";

$idno = $_SESSION["idno"];

$result = mysqli_query($mysql, "SELECT * FROM `admin` WHERE idno = '$idno'");
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
