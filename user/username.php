<?php 
ob_start(); // Start output buffering
session_start();

include "../user/nav.php";
include "../database/connector.php";
include "../database/authenticator.php";

$idno = $_SESSION["idno"];

$result = mysqli_query($mysql, "SELECT username, password FROM students WHERE idno = '$idno'");
$row = mysqli_fetch_assoc($result);

if($row){
    $username = $row['username'];
    $password = $row['password'];
}

if(isset($_POST["update"])){
    $username = mysqli_real_escape_string($mysql, $_POST["username"]);
    $password = mysqli_real_escape_string($mysql, $_POST["password"]);

    if(empty($username) || empty($password)){
        $_SESSION['error'] = "All fields should be filled.";
        header("Location: ../user/edit.php"); // Reload the page with error
        exit();
    }
    else {
        $result = "UPDATE students SET username ='$username', `password` ='$password' WHERE idno = '$idno'";
        $row = mysqli_query($mysql, $result);
       
        if($row) {
            $_SESSION['success'] = "Edited Successfully";
            header("Location: ../user/profile.php"); 
            exit();
        } else {
            $_SESSION['error'] = "Student not found!";
            header("Location: ../user/dashboard.php");
            exit();
        }
    }
}
ob_end_flush(); // End output buffering
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Update Username</title>
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center pt-20">
        <form action="username.php" method="post" class="bg-white shadow-2xl rounded-lg w-full max-w-md p-6">
            <div class="text-center pt-2 mb-5">
                <h1 class="text-2xl font-bold text-gray-800">Update Username</h1>
                <p class="text-gray-600">Edit your username and password below</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Username</label>
                <input type="text" name="username" placeholder="Username" value="<?php echo $username ?>" 
                    class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Password</label>
                <input type="password" name="password" placeholder="Password" value="<?php echo $password ?>" 
                    class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>
            <div class="flex justify-center gap-4">
                <button type="submit" name="update" 
                    class="flex items-center justify-center bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold shadow hover:bg-blue-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save
                </button>
                <a href="profile.php" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg shadow-md hover:bg-gray-400 transition">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>