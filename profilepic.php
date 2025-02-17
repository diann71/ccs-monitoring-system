<?php 
ob_start(); // Start output buffering
session_start();

include "nav.php";
include "connector.php";
include "authenticator.php";

if(isset($_POST["update"])){
    $idno = mysqli_real_escape_string($mysql, $_POST["idno"]);
        
    $lastname = mysqli_real_escape_string($mysql, $_POST["lastname"]);
    $firstname = mysqli_real_escape_string($mysql, $_POST["firstname"]);
    $midname = mysqli_real_escape_string($mysql, $_POST["midname"]);
    $course = mysqli_real_escape_string($mysql, $_POST["course"]);
    $year = mysqli_real_escape_string($mysql, $_POST["year"]);

    if(empty($idno) || empty($lastname) || empty($firstname) || empty($midname) || empty($course) ||
    empty($year)){
        $_SESSION['error'] = "All fields should be filled.";
        header("Location: edit.php"); // Reload the page with error
        exit();
    }
    else {
        $result = "UPDATE students SET lastname ='$lastname', firstname ='$firstname', midname ='$midname', course ='$course', `year` ='$year'  WHERE idno = '$idno'";
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
    <form action="name.php" method="post">
            <div class=" grid place-items-center">
                <div class="mt-10 mb-10 w-1/4 h-9/10 g-white border border-black border-solid rounded-xl">
                    <div class="w-full bg-blue-800 py-3">
                        <h1 class="w-full font-bold text-white text-center">Profile picture</h1>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col items-center">
                            <!-- Display Current Profile Picture -->
                            <img class="mt-10 mb-4 w-24 h-24 rounded-full border border-black object-cover" src="uploads/<?php echo $profile_picture; ?>" alt="">
                            
                            <!-- File Input (No Preview) -->
                            <label class="cursor-pointer bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 mt-4">
                                Upload new photo
                                <input type="file" name="profile_picture" class="hidden" accept="image/*">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
    </form>
</body>
</html>
