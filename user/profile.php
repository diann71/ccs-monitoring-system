<?php session_start();

include "../user/nav.php";
include "../database/connector.php";
include "../database/authenticator.php";

if(!isset($_SESSION['idno'])){
    $_SESSION['error'] = "Please log in to view your profile.";
    header("Location: ../auth/login.php"); 
    exit(); 
}

$idno = $_SESSION["idno"];

$result = mysqli_query($mysql, "SELECT * FROM students WHERE idno = '$idno'");
$row = mysqli_fetch_assoc($result);

if($row){
    $idno = $row['idno'];
    $lastname = $row['lastname'];
    $firstname = $row['firstname'];
    $midname = $row['midname'];
    $course = $row['course'];
    $year = $row['year'];
} else {
    $_SESSION['error'] = "Profile not found.";
    header("Location: ../user/dashboard.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Profile</title>
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center pt-20">
        <div class="bg-white shadow-2xl rounded-lg p-8 w-full max-w-md">
            <div class="flex flex-col items-center">
                <img class="mb-4 w-32 h-32 rounded-full object-cover" src="../uploads/<?php echo $profile; ?>" alt="Profile Picture">
                <h1 class="text-xl font-bold"><?php echo $firstname . ' ' . $midname . ' ' . $lastname ?></h1>
            </div>
            <div class="mt-6">
                <a href="name.php" class="block w-full text-left px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">Edit Name</a>
                <a href="username.php" class="block w-full text-left px-4 py-2 border border-gray-300 rounded hover:bg-gray-100 mt-2">Edit Username</a>
                <a href="profilepic.php" class="block w-full text-left px-4 py-2 border border-gray-300 rounded hover:bg-gray-100 mt-2">Change Profile Picture</a>
            </div>
        </div>
    </div>
</body>
</html>