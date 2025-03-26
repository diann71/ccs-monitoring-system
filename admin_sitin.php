<?php 
session_start();

include "connector.php";
include "admin_nav.php";    

$query_current = "SELECT students.idno, students.lastname, students.firstname, students.midname, students.course, students.year, sit_in.sitin_purpose, sit_in.time_in 
            FROM sit_in
            JOIN students ON sit_in.idno = students.idno
            WHERE sit_in.time_out IS NULL";

$result_current = mysqli_query($mysql, $query_current);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['timeout'])) {
    $idno = $_POST['idno'];

    // Update time_out to the current time
    $update_query = "UPDATE sit_in SET time_out = NOW() WHERE idno = ? AND time_out IS NULL";
    $stmt = mysqli_prepare($mysql, $update_query);
    mysqli_stmt_bind_param($stmt, "s", $idno);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Time Out Successful!'); window.location.href='admin_sitin.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($mysql) . "');</script>";
    }

    mysqli_stmt_close($stmt);
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['timeout'])) {
    $idno = $_POST['idno'];

    // Update time_out to the current time
    $update_session = "UPDATE students SET session = session -1 WHERE idno = ?";
    $stmt = mysqli_prepare($mysql, $update_session);
    mysqli_stmt_bind_param($stmt, "s", $idno);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Time Out Successful!'); window.location.href='admin_sitin.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($mysql) . "');</script>";
    }

    mysqli_stmt_close($stmt);
}



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
    <div class="grid grid-rows-1 gap-4 w-full pt-5 px-20">
        <div class="grid grid-cols-9 text-center border-b-2 pb-2">
            <p class="font-bold text-center">ID</p>
            <p class="font-bold text-center">Lastname</p>
            <p class="font-bold text-center">Firstname</p>
            <p class="font-bold text-center">Midname</p>
            <p class="font-bold text-center">Course</p>
            <p class="font-bold text-center">Year</p>
            <p class="font-bold text-center">Sit-in Purpose</p>
            <p class="font-bold text-center">Time In</p>
            <p class="font-bold text-center">Action</p>
        </div>
        <!-- Add rows dynamically here -->

        <?php while ($row = mysqli_fetch_assoc($result_current)): ?>
            <div class="grid grid-cols-9 text-center border-b-2">
                <p class="text-center"><?php echo $row['idno']; ?></p>
                <p class="text-center"><?php echo $row['lastname']; ?></p>
                <p class="text-center"><?php echo $row['firstname']; ?></p>
                <p class="text-center"><?php echo $row['midname']; ?></p>
                <p class="text-center"><?php echo $row['course']; ?></p>
                <p class="text-center"><?php echo $row['year']; ?></p>
                <p class="text-center"><?php echo $row['sitin_purpose']; ?></p>
                <p class="text-center"><?php echo $row['time_in']; ?></p>
                <form action="" method="post">
                    <input type="hidden" name="idno" value="<?php echo $row['idno']; ?>">
                    <button type="submit" name="timeout" class="px-4 py-2 bg-red-600 text-white rounded">Time Out</button>
                </form>
             </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
