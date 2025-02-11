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
        nav{
        }
        ul{
            display: flex;
            justify-content: flex-end;
            margin: 0;
            width: 100%;
            padding: 0;
            list-style-type: none;
            overflow: hidden;
            border-bottom: 1px solid black;
        }
        li{
            display: inline;
        }
        li a{
            margin-top: 9px;
            display: block;
            padding: 15px 16px;
            text-decoration: none;
            color: black;
            text-align: center;
        }
        li a:hover{
            background-color: white;
            color: blue
        }
        img{
            width: 55px;
            margin-right: 590px;
        }
        h1{
            display: inline-block;
            margin-left: 0;
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
                <img id="ccs" src="images/ccs.png">
                <li><a href="home.php" name="home">View Announcement</a></li>
                <li><a href="home.php" name="home">View Monitoring System</a></li>
                <li><a href="home.php" name="home">Sit-in Rules</a></li>
                <li><a href="home.php" name="home">Lab rules and Regulation</a></li>
            </ul>
        </nav>
    </div>
    <div>
    <a href="logout.php" name="logout">Logout</a>
    </div>

</body>
</html>