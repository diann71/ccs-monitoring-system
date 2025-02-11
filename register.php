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
    <title>Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100 p-4">
    <!-- Left Side -->
    <div class="flex flex-col items-center  bg-white shadow-lg md:w-96 h-auto p-6 rounded-l-3xl">
         <div class="flex flex-col md:flex-row items-center justify-center md:space-x-4">
            <img src="images/logo.png" class="w-[130px]">
            <img src="images/css.png" class="w-[105px] pb-2">
        </div>
        <h1 class="text-xl font-semibold text-gray-800 pb-2 ">CCS SIT-IN MONITORING SYSTEM</h1>
        <p class="text-gray-600 text-center mt-2 text-1g  p-2">
            Hello and welcome to the Sit-In Monitoring System of the CSS Computer Laboratories.
            This system helps track and manage student sit-ins efficiently.
        </p>
    </div>

    <!-- Right Side -->
    <div class="bg-white  shadow-lg w-sm md:w-[700px] h-auto p-6 rounded-r-3xl">
        <h1 class="text-xl font-semibold text-center text-gray-800">CREATE ACCOUNT</h1>
        <img src="images/profile.png" class="w-12 mx-auto mt-2">
        <p class="text-center text-gray-600">Sign up to continue</p>

        <form action="register.php" method="post" class="mt-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Left Column -->
                <div>
                    <label class="block text-gray-700 font-bold pb-1">ID No.</label>
                    <input type="text" name="idno" placeholder="ID Number" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2" required>

                    <label class="block text-gray-700 font-bold pb-1" >Last Name</label>
                    <input type="text" name="lastname" placeholder="Last Name" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2" required>

                    <label class="block text-gray-700 font-bold pb-1">First Name</label>
                    <input type="text" name="firstname" placeholder="First Name" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2" required>

                    <label class="block text-gray-700 font-bold pb-1">Middle Name</label>
                    <input type="text" name="midname" placeholder="Middle Name" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2" required>
                </div>

                <!-- Right Column -->
                <div>
                    <label class="block text-gray-700 font-bold pb-1">Course</label>
                    <select name="course" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-px" required>
                        <option value="" disabled selected>Select a course</option>
                        <option value="1">BSIT</option>
                        <option value="2">BSCS</option>
                    </select>

                    <label class="block text-gray-700 mt-2 font-bold pb-1">Year Level</label>
                    <select name="year" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-px" required>
                        <option value="" disabled selected>Select a level</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>

                    <label class="block text-gray-700 mt-2 font-bold pb-1">Username</label>
                    <input type="text" name="username" placeholder="Username" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2" required>

                    <label class="block text-gray-700 font-bold pb-1">Password</label>
                    <input type="password" name="password" placeholder="Password" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
            </div>

            <div class="flex justify-center mt-6">
                <input type="submit" name="submit" value="Sign Up" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transition">
            </div>
        </form>
    </div>
</body>
</html>
