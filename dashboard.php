<?php session_start();

include "connector.php";
include "nav.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
    </style>    
</head>
<body>
        <?php if(isset($_SESSION["success"])): ?>
            <div style="background-color: green; color: white; padding: 10px;">
                <?php
                   echo $_SESSION['success'];
                   unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>
</body>
</html>