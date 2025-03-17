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
        echo "<script>alert('Time Out Successful!'); window.location.href='search.php';</script>";
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
    <div class="flex justify-center pt-10 pb-10">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Midname</th>
                    <th>Course</th>
                    <th>Year</th>
                    <th>Sit-in Purpose</th>
                    <th>Time In</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result_current)): ?>
                    <tr>
                        <td><?php echo $row['idno']; ?></td>
                        <td><?php echo $row['firstname']; ?></td>
                        <td><?php echo $row['lastname']; ?></td>
                        <td><?php echo $row['midname']; ?></td>
                        <td><?php echo $row['course']; ?></td>
                        <td><?php echo $row['year']; ?></td>
                        <td><?php echo $row['sitin_purpose']; ?></td>
                        <td><?php echo $row['time_in']; ?></td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="idno" value="<?php echo $row['idno']; ?>">
                                <button type="submit" name="timeout" class="px-4 py-2 bg-red-600 text-white rounded">Time Out</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
