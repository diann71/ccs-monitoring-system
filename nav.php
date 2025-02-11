<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body{
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .conts{
            background: white;
            border-radius: 0px;
            font-family: Arial, sans-serif;
        }
        nav{
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid black;
        }
        ul{
            text-transform: uppercase;
            display: flex;
            justify-content: flex-end;
            margin: 0;
            width: 100%;
            font-size: 18px;
            padding: 0;
            list-style-type: none;
            overflow: hidden;
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
            color: blue;
        }
        .ccs{
            display: flex;
            align-items: center;
            width: 50%;
        }
        #logo{
            width: 55px;
            padding-left: 15px; 
        }
        #wrds{
            font-weight: normal;
            margin-left: 20px;
        }
        .dropdown img{
            width: 40px;
            padding-right: 15px;
            padding-top: 5px;
        }
        .dropdown{
            margin: 10px;
            display: inline-block;
            
        }
        .dropdown button{
            background-color: blue;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }
        .dropdown a{
            color: black;
            display: block;
            text-decoration: none;
            padding: 10px 15px;
        }
        .dropdown .content{
            left: auto;
            right: 0;
            display: none;
            position: absolute;
            background-color: white;
            min-width: 100px;
            box-shadow: 2px 2px 5px black;
        }
        .dropdown:hover .content{
            display: block;
        }
        .dropdown a:hover{
            background-color: gray;
        }
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
    <div class="conts">
        <nav class="navbar">
            <div class="ccs">
                <img id="logo" src="images/ccs.png">
                <h1 id="wrds">CCS SIT-IN MONITORING SYSTEM</h1>
            </div>
            <ul>
                <li><a href="home.php" name="home">View Announcement</a></li>
                <li><a href="home.php" name="home">View Remaining Session</a></li>
                <li><a href="home.php" name="home">Sit-in Rules</a></li>
                <li><a href="home.php" name="home">Lab rules & Regulation</a></li>
                <li><a href="home.php" name="home">History</a></li>
                <li><a href="home.php" name="home">Reservation</a></li>
            </ul>
            <div class="dropdown">
                <img id="profile" src="images/profile.png">
                <div class="content">
                    <a href="profile.php">PROFILE</a>
                    <a href="logout.php" name="logout">LOGOUT</a>
                </div>
            </div>
        </nav>
    </div>
    <div>
    </div>

</body>
</html>