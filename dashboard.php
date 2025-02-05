<?php session_start();

include "connector.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body{
            margin: 0;
            padding: 0;
        }
        .container{
            background: white;
            border-radius: 0px;
            font-family: Arial, sans-serif;
        }
        a[name=logout]{
            padding: 10px;
            background-color: white;
            text-decoration: none;
            border: 1px solid #ccc;
            font-weight: bold;
            cursor: pointer;
            font-family: Arial, sans-serif;
        }
        a[name=logout]:hover{
            background-color: blue;
            color: white;
        }
        ul{
            width: 100%;
            padding: 0;
            background-color: blue;
            list-style-type: none;
            overflow: hidden;
        }
        li{
            display: inline;
            float: right;
        }
        li a{
            display: block;
            padding: 15px 16px;
            text-decoration: none;
            color: white;
            text-align: center;
        }
        li a:hover{
            background-color: white;
            color: blue
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if(isset($_SESSION["success"])): ?>
            <div style="background-color: green; color: white; padding: 10px;">
                <?php
                   echo $_SESSION['success'];
                   unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>
        <nav class="navbar">
            <ul>
                <li><a href="home.php" name="home">Home</a></li>
                <li><a href="home.php" name="home">Home</a></li>
                <li><a href="home.php" name="home">Home</a></li>
                <li><a href="home.php" name="home">Home</a></li>
            </ul>
        </nav>
        <h1>Welcome Student</h1>
        <a href="logout.php" name="logout">Logout</a>
    </div>
</body>
</html>