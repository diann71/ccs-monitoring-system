<?php 
ob_start();
session_start();

include "connector.php";
include "admin_nav.php";   

$result = mysqli_query($mysql, "SELECT * FROM students ORDER BY lastname ASC");

if(isset($_POST['delete'])){
    $idno = $_POST['idno'];

    $result = "DELETE FROM students WHERE idno = ?";
    $stmt = mysqli_prepare($mysql, $result);
    mysqli_stmt_bind_param($stmt, "i", $idno);
    mysqli_stmt_execute($stmt);

    if(mysqli_stmt_execute($stmt)){
        $_SESSION['success'] = "Student deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete student: " . mysqli_error($mysql);
    }

    mysqli_stmt_close($stmt);

    header("Location: student_list.php");
    exit();

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div class="flex justify-center pt-5 pb-10">
    <div class="grid grid-rows-1 gap-4 w-full">
        <div>
            <h1 class="bg-gray-600 text-white text-xl text-center py-2">Student List</h1>
        </div>
        <div class="grid grid-cols-9 text-center border-b-2 pb-2">
            <p class="font-bold text-center">ID</p>
            <p class="font-bold text-center">Lastname</p>
            <p class="font-bold text-center">Firstname</p>
            <p class="font-bold text-center">Midname</p>
            <p class="font-bold text-center">Course</p>
            <p class="font-bold text-center">Year</p>
            <p class="font-bold text-center">Username</p>
            <p class="font-bold text-center">Password</p>
            <p class="font-bold text-center">Action</p>
        </div>
        <!-- Add rows dynamically here -->

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            
            <div class="grid grid-cols-9 text-center border-b-2 pb-2">
                <p class=" text-center"><?php echo $row['idno']; ?></p>
                <p class=" text-center"><?php echo $row['lastname']; ?></p>
                <p class=" text-center"><?php echo $row['firstname']; ?></p>
                <p class=" text-center"><?php echo $row['midname']; ?></p>
                <p class=" text-center"><?php echo $row['course']; ?></p>
                <p class=" text-center"><?php echo $row['year']; ?></p>
                <p class=" text-center"><?php echo $row['username']; ?></p>
                <p class=" text-center"><?php echo $row['password']; ?></p>
                <form action='' method='post'>
                    <input type="hidden" name="idno" value="<?php echo $row['idno']; ?>">
                    <button type='submit' name='delete' class='bg-red-600 text-white py-1 px-3 rounded'>Delete</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>   
</div>
</body>
</html>