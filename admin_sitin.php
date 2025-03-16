<?php 
session_start();

include "connector.php";
include "admin_nav.php";    

$query_current = "SELECT students.idno, students.lastname, students.firstname, students.midname, students.course, sit_in.year, sit_in.time_in 
            FROM sit_in
            JOIN students ON sit_in.idno = students.idno
            WHERE sit_in.time_out IS NULL";

$result_current = mysqli_query($mysql, $query_current);

$query_timeout = "SELECT students.idno, students.lastname, students.firstname, students.midname, students.course, students.year, sit_in.time_in, sit_in.time_out
            FROM sit_in
            JOIN students ON sit_in.idno = students.idno
            WHERE sit_in.time_out IS NULL";

$result_timeout = mysqli_query($mysql, $query_timeout);

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
        <?php while ($row = mysqli_fetch_assoc($result_current)): ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Midname</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Time In</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $row['idno']; ?></td>
                        <td><?php echo $row['lastname']; ?></td>
                        <td><?php echo $row['firstname']; ?></td>
                        <td><?php echo $row['midname']; ?></td>
                        <td><?php echo $row['course']; ?></td>
                        <td><?php echo $row['year']; ?></td>
                        <td><?php echo $row['time_in']; ?></td>
                    </tr>
                </tbody>
            </table>
        <?php endwhile; ?>
    </div>
</body>
</html>