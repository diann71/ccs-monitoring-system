<?php session_start();

include "nav.php";
include "connector.php";
include "authenticator.php";

if(!isset($_SESSION['idno'])){
    $_SESSION['error'] = "Please log in to view your profile.";
    header("Location: login.php"); 
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
    header("Location: dashboard.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <div class="mt-20 h-lvh place-items-center">
    <div class="w-1/4 h-1/2 border border-black shadow-lg rounded-3xl overflow-hidden">
        <div class="w-full bg-blue-800 py-3">
            <h1 class="w-full font-bold text-white text-center">Profile</h1>
        </div>
        <div class="flex flex-col items-center">
                    <!-- Display Current Profile Picture -->
                    <img class="mt-10 mb-4 w-24 h-24 rounded-full border border-black object-cover" src="uploads/<?php echo $profile_picture; ?>" alt="">
                    <h1 class="font-bold mb-7"> <?php echo $firstname . ' ' . $midname . ' ' . $lastname?></p>
                    
                    <!-- File Input (No Preview)
                    <label class="cursor-pointer bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 mt-4">
                        Choose Image
                        <input type="file" name="profile_picture" class="hidden" accept="image/*">
                    </label> -->
        </div>
        <div class="grid p-6">
            <a href="name.php" class="p-2 w-full border border-black text-black  hover:bg-gray-100 text-left">Name</a>
            <a href="username.php" class="p-2 w-full border border-black text-black  hover:bg-gray-100 text-left">Username</a>
            <a href="profilepic.php" class="p-2 w-full border border-black text-black  hover:bg-gray-100 text-left">Profile picture</a>
        </div>
        
    </div>

</div>
</body>
</html>