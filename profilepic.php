<?php 
ob_start(); // Start output buffering
session_start();

include "nav.php";
include "connector.php";
include "authenticator.php";

$idno = $_SESSION['idno'];

$result = mysqli_query($mysql, "SELECT `profile` FROM students WHERE idno = '$idno'");
$row = mysqli_fetch_assoc($result);

if($row){
    $profile = $row['profile'];
}

if(isset($_POST["submit"])){
    $idno = mysqli_real_escape_string($mysql, $_POST["idno"]);

    $profile = mysqli_real_escape_string($mysql, $_POST["profile"]);

    if(empty($profile)){
        $_SESSION['error'] = "All fields should be filled.";
        header("Location: edit.php"); // Reload the page with error
        exit();
    }
    else {
        $result = "UPDATE students SET `profile` = '$profile'  WHERE idno = '$idno'";
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
            <div class="bg-zinc-300 grid place-items-center">
                <div class="mt-10 mb-10 w-1/4 h-9/10 border border-black  rounded-3xl overflow-hidden">
                    <div class="w-full bg-blue-800 py-3">
                        <h1 class="w-full font-bold text-white text-center">Profile picture</h1>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col items-center">
                            <img class="mt-8 mb-4 w-48 h-48 rounded-full border border-black object-cover" src="uploads/<?php echo $profile; ?>" alt="">
                            <!-- File Input (No Preview) -->
                            <div class="flex gap-5">
                                <label class="cursor-pointer bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 mt-4">Choose photo<input type="file" name="profile" class="hidden" accept="image/*"></label>
                                <input type="submit" name="submit" value="Save Changes" class=" cursor-pointer bg-blue-600 text-white px-4 py-2 rounded-lg mt-4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </form>
</body>
</html>
