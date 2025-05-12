<?php 
ob_start(); // Start output buffering
session_start();

include "../user/nav.php";
include "../database/connector.php";
include "../database/authenticator.php";

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
        header("Location: ../user/edit.php"); // Reload the page with error
        exit();
    }
    else {
        $result = "UPDATE students SET lastname ='$lastname', firstname ='$firstname', midname ='$midname', course ='$course', `year` ='$year'  WHERE idno = '$idno'";
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
    <title>Edit Name</title>
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center pt-20">
        <form action="name.php" method="post" class="bg-white shadow-2xl rounded-lg w-full max-w-2xl p-8">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Edit Name</h1>
                <p class="text-gray-600">Update your personal details below</p>
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">ID No</label>
                    <input type="text" name="idno" placeholder="ID Number" value="<?php echo $idno ?>" 
                        class="w-full border border-gray-300 p-3 rounded bg-gray-100" readonly>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Last Name</label>
                    <input type="text" name="lastname" placeholder="Last Name" value="<?php echo $lastname ?>" 
                        class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">First Name</label>
                    <input type="text" name="firstname" placeholder="First Name" value="<?php echo $firstname ?>" 
                        class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Middle Name</label>
                    <input type="text" name="midname" placeholder="Middle Name" value="<?php echo $midname ?>" 
                        class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Course</label>
                    <select name="course" 
                        class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                        <option value="" disabled selected>Select a course</option>
                        <option value="1" <?php echo ($course == 'BSIT') ? 'selected' : ''; ?>>BSIT</option>
                        <option value="2" <?php echo ($course == 'BSCS') ? 'selected' : ''; ?>>BSCS</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Year Level</label>
                    <select name="year" 
                        class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                        <option value="" disabled selected>Select a level</option>
                        <option value="1" <?php echo ($year == '1') ? 'selected' : ''; ?>>1</option>
                        <option value="2" <?php echo ($year == '2') ? 'selected' : ''; ?>>2</option>
                        <option value="3" <?php echo ($year == '3') ? 'selected' : ''; ?>>3</option>
                        <option value="4" <?php echo ($year == '4') ? 'selected' : ''; ?>>4</option>
                    </select>
                </div>
            </div>
            <div class="mt-8 flex justify-center gap-4">
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
