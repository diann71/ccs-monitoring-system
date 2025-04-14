<?php
include "../database/connection.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['idnos'] = $_POST['idno'];  
    $_SESSION['sitin_purpose'] = $_POST['sitin_purpose'];

    foreach ($idnos as $index => $idno) {
        $sitin_purpose = $sitin_purposes[$index];
        $lab = $lab[$index];
    
        $query = "SELECT * FROM sit_in WHERE idno = ? AND time_out IS NULL";

        $stmt = mysqli_prepare($mysql, $query);
        mysqli_stmt_bind_param($stmt, "s", $idno);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result->num_rows > 0) {
            echo "<script>alert('Student with ID $idno is still currently sitting in and has not logged out yet.'); 
            window.location.href='admin_dashboard.php';</script>";
            continue; 
        }

        $qry = "SELECT lastname, firstname, midname, course, year FROM students WHERE idno = ?";

        $stm = mysqli_prepare($mysql, $query);
        mysqli_stmt_bind_param($stm, "s", $idno);
        mysqli_stmt_execute($stm);
        $myresult = mysqli_stmt_get_result($stm);

        if ($row = mysqli_fetch_assoc($myresult)) {
            $name = $row['firstname'] . " " . $row['midname'] . " " . $row['lastname'];;
            $course = $row['course'];
            $course = $row['year'];

            // insert sitin record
            $insertStmt = mysqli_prepare($mysql, "INSERT INTO sit_in (idno, lastname, firstname, midname, course, year sitin_purpose, lab, time_in) VALUES (?, ?, ?, ?, ?, ?, ?NOW())");
            mysqli_stmt_bind_param($insertStmt, "sssss", $idno, $name, $course, $sitin_purpose, $lab);
            mysqli_stmt_execute($insertStmt);
        }
    }
}
?>