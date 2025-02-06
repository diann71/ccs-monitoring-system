<?php 
session_start();
include 'connector.php';

if(isset($_POST["submit"])){
    $idno = mysqli_real_escape_string($mysql, $_POST["idno"]);
    $lastname = mysqli_real_escape_string($mysql, $_POST["lastname"]);
    $firstname = mysqli_real_escape_string($mysql, $_POST["firstname"]);
    $midname = mysqli_real_escape_string($mysql, $_POST["midname"]);
    $course = mysqli_real_escape_string($mysql, $_POST["course"]);
    $yearlevel = mysqli_real_escape_string($mysql, $_POST["year"]);
    $username = mysqli_real_escape_string($mysql, $_POST["username"]);
    $password = mysqli_real_escape_string($mysql, $_POST["password"]);

    if(empty($idno) || empty($lastname) || empty($firstname) || empty($midname) || empty($course) ||
       empty($yearlevel) || empty($username) || empty($password)){
        $_SESSION['error'] = "All fields should be filled.";
        header("Location: register.php"); // Reload the page with error
        exit();
    }
    else {
        $result = "INSERT INTO students (idno, lastname, firstname, midname, course, year, username, password) 
                  VALUES ('$idno','$lastname','$firstname','$midname','$course','$yearlevel','$username','$password')";
        
        if(mysqli_query($mysql, $result)){
            $_SESSION['success'] = "Registered Successfully";
            header("Location: login.php"); // Redirect to login page with success message
            exit();
        } else {
            $_SESSION['error'] = "Registration Failed!";
            header("Location: register.php");
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
    <title>Sign up</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .cont{
            background: white;
            margin-right: -24px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 350px;
            height: 530px;
            border-radius: 0;
            border-top-left-radius: 40px;
            border-bottom-left-radius: 40px;
            text-align: center;
            /*background: linear-gradient(to right, #441752, #DDA853);*/
        }
        .container{
            border-radius: 0;
            border-top-right-radius: 40px;
            border-bottom-right-radius: 40px;
            width: 700px;
            height: 530px;
            margin: 25px;
            background: white;
            padding: 20px;  
            box-shadow: 10px 5px 5px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .col{
            float: left;
        }
        .col1{
            float: right;
        }
        label{
            padding-left: 0px;
        }
        input{
            width: 300px;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 0;
        }
        input[type=submit]{
            align-items: center;
            margin-right: -48px;
            float: right;
            width: 20%;
            font-weight: bold;
            cursor: pointer;
            border-radius: 20px;
        }
        select {
            width: 320px;
            padding: 9px;
            background-color: white;
            float: left;
        }
        h1{
            margin-top: 20px;
            margin-bottom: 20px;
            align-items: center;
            font-size: 20px;
        }
        #side{
            padding-top: 100px;
            color: black;
            font-size: 25px;
        }
        pre{
            color: black;
            font-family: Arial, sans-serif;
        }
        p{
            font-weight: bold;
        }
        #profile{
            width: 10%;
            justify-content: center;
        }
        #logo{
            width: 30%;
            height: 19%;
        }
        #css{
            width: 30%;
            height: 20%;
        }
        .cont h1{
            margin-top: -50px;
        }
    </style>
</head>
<body>  
        <div class="cont">
            <img id="logo" src="images/logo.png">
            <img id="css" src="images/css.png">
            <h1 id="side">CCS SIT-IN MONITORING SYSTEM</h1>
            <pre>Hello and welcome to the Sit-In Monitoring 
System of the CSS Computer Laboratories. 
This system is designed to efficiently 
track and manage student sit-ins, 
ensuring proper usage of lab resources 
while maintaining a smooth and organized 
environment.

            </pre>
        </div>
        <div class="container">
        
            <h1>CREATE ACCOUNT</h1>
            <img id="profile" src="images/profile.png">
            <p>Sign up to continue</p>
            <form action="register.php" method="post">
                <div class="col">
                        <label style="margin-bottom: -20px;">Idno</label><br>
                        <input type="text" name="idno" placeholder="Id number" required><br>
                        <label style="margin-bottom: -20px;">Lastname</label><br>
                        <input type="text" name="lastname" placeholder="Lastname" required><br>
                        <label style="margin-bottom: -20px;">Firstname</label><br>
                        <input type="text" name="firstname" placeholder="Firstname" required><br>
                        <label style="margin-bottom: -20px;">Midname</label><br>
                        <input type="text" name="midname" placeholder="Midname" required><br>
                </div>
                <div class="col1">
                        <label style="margin-bottom: -10px;">Course</label><br>
                        <select style="margin-bottom: 10px;" name="course" required>
                            <option value="" disabled selected>Select a course</option>
                            <option value="1">BSIT</option>
                            <option value="2">BSCS</option>
                        </select>
                        <label style="margin-bottom: -10px;">Year Level</label><br>
                        <select name="year" required>
                            <option value="" disabled selected>Select a level</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                        <label style="margin-bottom: -20px; margin-top: 48px;">Username</label><br>
                        <input type="text" name="username" placeholder="Username" required><br>
                        <label style="margin-bottom: -20px;">Password</label><br>
                        <input type="password" name="password" placeholder="Password" required><br>
                </div>
                <input type="submit" name="submit" value="Sign up">
            </form>
        </div>
</body>
</html>
