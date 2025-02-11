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
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body{
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-6 rounded-2xl shadow-lg text-center w-96">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-500 text-white p-2 mb-4 rounded-md">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="bg-green-500 text-white p-2 mb-4 rounded-md">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <div class="flex flex-col md:flex-row items-center justify-center md:space-x-4">
            <img src="images/logo.png" class="w-[130px]">
            <img src="images/css.png" class="w-[105px] pb-2">
        </div>
        
        <h1 class="text-3xl font-bold mb-6">CCS SIT-IN MONITORING SYSTEM</h1>
        
        <form action="login.php" method="post" class="space-y-4">
            <div>
                <label for="username" class="block text-left text-base pb-1 font-semibold">Username</label>
                <input type="text" id="username" name="username" placeholder="Username" required 
                       class="w-full  text-base px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label for="password" class="block text-left text-base pb-1 font-semibold">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required 
                       class="w-full  text-base px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2">
            </div>
            <input type="submit" name="submit" value="Login" 
                   class="w-[150px] bg-blue-600 text-white py-2 rounded-3xl hover:bg-blue-700 cursor-pointer float-left mb-2">
        </form>
        
        <a href="register.php" class="block mt-4">
            <button class="w-[150px] bg-gray-200 text-black py-2 rounded-3xl hover:bg-gray-300 float-right mb-2">Sign up</button>
        </a>
    </div>
</body>
</html>
