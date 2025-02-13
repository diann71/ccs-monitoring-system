<?php 
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
            $result = "UPDATE students SET idno = '$idno', lastname ='$lastname', firstname ='$firstname', midname ='$midname', course ='$course', `year` ='$year' ";
            $row = mysqli_query($mysql, $result);
           
            if($row) {
                $_SESSION['success'] = "Edited Successfully";
                header("Location: profile.php"); // Redirect to login page with success message
                exit();
            } else {
                $_SESSION['error'] = "Student not found!";
                header("Location: dashboard.php");
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
    <title>Edit</title>
</head>
<body>
        <form action="edit.php" method="post">
            <div class="h-lvh grid place-items-center">
                <div class="bg-white p-6 w-96 border-8 border-solid md:w-[700px] rounded-r-3xl">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="bg-red-500 text-white p-2 mb-4 rounded-md text-center">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="bg-green-500 text-white p-2 mb-4 rounded-md text-center">
                            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <h1 class="text-xl font-semibold text-center text-gray-800 p-3">EDIT PROFILE</h1>

                    <form action="register.php" method="post" class="mt-4">
                    <div class="justify-center grid grid-cols-1 md:grid-cols-1 gap-4">
                        <!-- Left Column -->
                        <div>
                            <label class="block text-gray-700 font-bold pb-1">ID No.</label>
                            <input type="text" name="idno" placeholder="ID Number" value="<?php echo $idno ?>" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2" required>

                            <label class="block text-gray-700 font-bold pb-1" >Last Name</label>
                            <input type="text" name="lastname" placeholder="Last Name" value="<?php echo $lastname ?>" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2" required>

                            <label class="block text-gray-700 font-bold pb-1">First Name</label>
                            <input type="text" name="firstname" placeholder="First Name" value="<?php echo $firstname ?>" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2" required>

                            <label class="block text-gray-700 font-bold pb-1">Middle Name</label>
                            <input type="text" name="midname" placeholder="Middle Name" value="<?php echo $midname ?>" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2" required>

                            <label class="block text-gray-700 font-bold pb-1">Course</label>
                            <select name="course" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-px"  required>
                                <option value="" disabled selected>Select a course</option>
                                <option value="1" <?php echo ($course == 'BSIT') ? 'selected' : ''; ?>>BSIT</option>
                                <option value="2" <?php echo ($course == 'BSCS') ? 'selected' : ''; ?>>BSCS</option>
                            </select>

                            <label class="block text-gray-700 mt-2 font-bold pb-1">Year Level</label>
                            <select name="year" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-px" required>
                                <option value="" disabled selected>Select a level</option>
                                <option value="1" <?php echo ($year == '1') ? 'selected' : ''; ?>>1</option>
                                <option value="2" <?php echo ($year == '2') ? 'selected' : ''; ?>>2</option>
                                <option value="3" <?php echo ($year == '3') ? 'selected' : ''; ?>>3</option>
                                <option value="4" <?php echo ($year == '4') ? 'selected' : ''; ?>>4</option>
                            </select>
                            <div class="flex justify-center mt-6">
                                <input type="submit" name="update" value="Update" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transition">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
</div>
</body>
</html>