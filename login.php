<?php session_start();

include "connector.php";

    if(isset($_POST['submit'])){
        $username = mysqli_real_escape_string($mysql, $_POST["username"]);
        $password = mysqli_real_escape_string($mysql, $_POST["password"]);

        if(empty($username) || empty($password)) {
            $_SESSION["error"] = "All fields should be filled.";
            header('location: login.php');
            exit();
        }
        else{
            $result = mysqli_query($mysql, "SELECT * FROM students WHERE username = '$username' AND `password` ='$password' ") or die("Could not execute the select query.");
            $row = mysqli_fetch_assoc($result);

            if($row){
                $_SESSION['username'] = $row['username'];
                $_SESSION['idno'] = $row['idno'];

                $_SESSION['success'] = "Login Successfully!";
                header('Location: dashboard.php');
                exit();
            }
            else{
                $_SESSION["error"] = "User not Found";
                header('Location: login.php'); // Redirect to login page
                exit();
            } 
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container{
            width: 350px;
            height: 445px;
            background: white;
            padding: 20px;
            border-radius: 20px;    
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        
        input{
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 0;
        }
        input[type=button][value="Sign up"]{
            width: 40%;
            margin-top: 25px;
            margin-right: 18px;
            float: right;
            font-weight: bold;
            cursor: pointer;
        }
        input[type=button][value="Sign up"]:hover {
            background-color: blue;
            color: white;
        }
        input[type=submit]{
            width: 40%;
            margin-top: 25px;
            margin-left: 18px;
            float: left;
            font-weight: bold;
            cursor: pointer;
            border-radius: 20px;
        }
        input[type=button]{
            border-radius: 20px;
        }
        #logo{
            width: 35%;
            height: 22%;
            justify-content: center;
        }
        #css{
            width: 28%;
            height: 22%;
        }
        h1{     
            margin-top: 30px;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container">
    <?php if (isset($_SESSION['error'])): ?>
        <div style="background-color: red; color: white; padding: 10px;">
            <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']); // Clear the error message after displaying
            ?>
        </div>
    <?php endif; ?>     
    <?php if(isset($_SESSION['success'])): ?>
    <div style="background-color: green; color: white; padding: 10px;">
        <?php 
            echo $_SESSION['success']; 
            unset($_SESSION['success']); // Clear message after displaying
        ?>
    </div>
    <?php endif; ?>

        <img id="logo" src="images/logo.png">
        <img id="css" src="images/css.png">

        <!--<a href="#login-form">LOGIN</a>
        <a href="#signup-form">SIGN UP</a>-->
        <h1>CCS SIT-IN MONITORING SYSTEM</h1>
        <form action="login.php" method="post">
            <label for="username" style="margin-bottom: -20px;">Username</label><br>
            <input type="text" id="username" name="username" placeholder="Username" required><br>
            <label for="password" style="margin-bottom: -20px;">Password</label><br>
            <input type="password" id="password"name="password" placeholder="Password" required><br>
            <input type="submit" name="submit" value="Login">
        </form>
        
        <a href="register.php">
            <input type="button" value="Sign up">
        </a>
    </div>
</body>
</html>