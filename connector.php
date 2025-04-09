<?php

$servername = "localhost:3307";
$databasename = "students";
$username = "root";
$password = "";

$mysql = mysqli_connect($servername,$username, $password, $databasename);
if (!$mysql) {
    die("Connection failed: " . mysqli_connect_error());
}
?>  