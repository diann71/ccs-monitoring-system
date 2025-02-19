

<?php 
ob_start(); // Start output buffering
session_start();

include "nav.php";
include "connector.php";
include "authenticator.php";

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
        header("Location: edit.php"); // Reload the page with error
        exit();
    }
    else {
        $result = "UPDATE students SET username ='$username', `password` ='$password' WHERE idno = '$idno'";
        $row = mysqli_query($mysql, $result);
       
        if($row) {
            $_SESSION['success'] = "Edited Successfully";
            header("Location: profile.php"); 
            exit();
        } else {
            $_SESSION['error'] = "Student not found!";
            header("Location: dashboard.php");
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
    <title>Document</title>
</head>
<body>
    <form action="username.php" method="post">
            <div class=" grid place-items-center">
                <div class="mt-10 mb-10 w-1/3 h-3/4  border border-black border-solid rounded-3xl">
                    <div class="p-6">
                        
                        <label class="block text-gray-700 font-bold pb-1 " >Username</label>
                        <input type="text" name="username" placeholder="Username" value="<?php echo $username ?>" class="w-full border border-black p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2" required>

                        <label class="block text-gray-700 font-bold pb-1 " >Password</label>
                        <input type="password" name="password" placeholder="Password" value="<?php echo $password ?>" class="w-full border border-black p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-5" required>
                        <div class="text-center">
                            <button type="submit" name="update" class=" block px-4 py-2 w-full mb-5 bg-blue-600 rounded-3xl text-white text-bold hover:bg-blue-700 cursor-pointer">  
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
    </form>
</body>
</html>