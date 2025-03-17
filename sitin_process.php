<?php
include "connection.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idnos = $_POST['idno'];
    $sitin_purposes = $_POST['sitin_purpose'];

    foreach ($idnos as $index => $idno) {
        $sitin_purpose = $sitin_purposes[$index];
    
        $query = "SELECT * FROM sit_in_records WHERE idno = ? AND time_out IS NULL";

        $stmt = mysqli_prepare($mysql, $query);
        mysqli_stmt_bind_param($stmt, "s", $idno);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result->num_rows > 0) {
            echo "<script>alert('Student with ID $idno is still currently sitting in and has not logged out yet.'); window.location.href='admindashboard.php';</script>";
            continue; 
        }

        $qry = "SELECT fname, lname, course FROM studentinfo WHERE idno = ?";

        $stm = mysqli_prepare($mysql, $query);
        mysqli_stmt_bind_param($stm, "s", $idno);
        mysqli_stmt_execute($stm);
        $myresult = mysqli_stmt_get_result($stm);

        if ($row = mysqli_fetch_assoc($myresult)) {
            $name = $row['firstname'] . " " . $row['lastname'];
            $course = $row['course'];

            // insert sitin record
            $insertStmt = mysqli_prepare($mysql, "INSERT INTO sit_in (idno, lastname, firstname, midname, course, sitin_purpose, time_in) VALUES (?, ?, ?, ?, NOW())");
            mysqli_stmt_bind_param($insertStmt, "ssss", $idno, $name, $course, $sitin_purpose);
            mysqli_stmt_execute($insertStmt);
        }
    }
}
?>