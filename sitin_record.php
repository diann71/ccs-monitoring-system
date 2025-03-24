<?php 
session_start();

include "connector.php";
include "admin_nav.php";    

$query_completed = "SELECT students.idno, students.lastname, students.firstname, students.midname, students.course, students.year, sit_in.sitin_purpose, sit_in.time_in, sit_in.time_out 
            FROM sit_in
            JOIN students ON sit_in.idno = students.idno
            WHERE sit_in.time_out IS NOT NULL
            ORDER BY sit_in.time_out DESC"; // Show latest records first
$result_completed = mysqli_query($mysql, $query_completed);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
</head>
<body>
<div class="flex justify-center pt-2 pb-10">
    <div class="grid grid-rows-1 gap-4 w-full">
        <div>
            <h1 class="bg-gray-600 text-white text-xl text-center py-2">Sit-in Record</h1>
        </div>
        <div class="grid grid-cols-9 text-center border-b-2 pb-2">
            <p class="font-bold text-center">ID</p>
            <p class="font-bold text-center">Lastname</p>
            <p class="font-bold text-center">Firstname</p>
            <p class="font-bold text-center">Midname</p>
            <p class="font-bold text-center">Course</p>
            <p class="font-bold text-center">Year</p>
            <p class="font-bold text-center">Sit-in Purpose</p>
            <p class="font-bold text-center">Time In</p>
            <p class="font-bold text-center">Time Out</p>
        </div>
        <!-- Add rows dynamically here -->

        <?php while ($row = mysqli_fetch_assoc($result_completed)): ?>
            <div class="grid grid-cols-9 text-center border-b-2 pb-2">
                <p class="text-center"><?php echo $row['idno']; ?></p>
                <p class="text-center"><?php echo $row['lastname']; ?></p>
                <p class="text-center"><?php echo $row['firstname']; ?></p>
                <p class="text-center"><?php echo $row['midname']; ?></p>
                <p class="text-center"><?php echo $row['course']; ?></p>
                <p class="text-center"><?php echo $row['year']; ?></p>
                <p class="text-center"><?php echo $row['sitin_purpose']; ?></p>
                <p class="text-center"><?php echo $row['time_in']; ?></p>
                <p class="text-center"><?php echo $row['time_out']; ?></p>
             </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>

