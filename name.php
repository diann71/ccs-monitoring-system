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
                        <h1 class="w-full font-bold text-white text-center">Edit name</h1>
                    </div>
                    <div class="p-6">
                        <label class="block text-gray-700 font-bold pb-1 " >Idno</label>
                        <input type="text" name="idno" placeholder="Last Name" value="<?php echo $idno ?>" class="w-full border border-black p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2" required>

                        <label class="block text-gray-700 font-bold pb-1 " >Last Name</label>
                        <input type="text" name="lastname" placeholder="Last Name" value="<?php echo $lastname ?>" class="w-full border border-black p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2" required>

                        <label class="block text-gray-700 font-bold pb-1">First Name</label>
                        <input type="text" name="firstname" placeholder="First Name" value="<?php echo $firstname ?>" class="w-full border border-black p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2" required>

                        <label class="block text-gray-700 font-bold pb-1">Middle Name</label>
                        <input type="text" name="midname" placeholder="Middle Name" value="<?php echo $midname ?>" class="w-full border border-black p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2" required>
                        <label class="block text-gray-700 font-bold pb-1">Course</label>
                        <select name="course" class="w-full border border-black p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-px" required>
                            <option value="" disabled selected>Select a course</option>
                            <option value="1" <?php echo ($course == 'BSIT') ? 'selected' : ''; ?>>BSIT</option>
                            <option value="2" <?php echo ($course == 'BSCS') ? 'selected' : ''; ?>>BSCS</option>
                        </select>

                        <label class="block text-gray-700 mt-2 font-bold pb-1">Year Level</label>
                        <select name="year" class="w-full border border-black p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-10" required>
                            <option value="" disabled selected>Select a level</option>
                            <option value="1" <?php echo ($year == '1') ? 'selected' : ''; ?>>1</option>
                            <option value="2" <?php echo ($year == '2') ? 'selected' : ''; ?>>2</option>
                            <option value="3" <?php echo ($year == '3') ? 'selected' : ''; ?>>3</option>
                            <option value="4" <?php echo ($year == '4') ? 'selected' : ''; ?>>4</option>
                        </select>
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
