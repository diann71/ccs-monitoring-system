<?php session_start();

include "nav.php";
include "connector.php";
include "authenticator.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <div class="h-screen grid place-items-center">
    <div class="w-96 border-2 border-solid p-6 shadow-lg rounded-lg ">
        <p>Id No: <?php echo $idno; ?></p>
        <p>Last Name: <?php echo $lastname;?></p>
        <p>First Name: <?php echo $firstname;?></p>
        <p>Middle Name: <?php echo $midname;?></p>
        <p>Course: <?php echo $course;?></p>
        <p>Year: <?php echo $year;?></p>
    </div>
    <div>
         <a href="edit.php" class="block px-4 py-2 hover:bg-gray-200">Edit</a>
    </div>
</div>
</body>
</html>