<?php
session_start();
include "connector.php";
include "admin_nav.php";

if (isset($_POST['submit'])) {
    $search = $_POST['search'];
    $search_param = "%$search%";

    $query = "SELECT * FROM students WHERE idno LIKE ? OR lastname LIKE ? OR firstname LIKE ? OR midname LIKE ? OR course LIKE ? OR year LIKE ?";
    $stmt = mysqli_prepare($mysql, $query);
    
    if (!$stmt) {
        die("Query Preparation Failed: " . mysqli_error($mysql));
    }

    mysqli_stmt_bind_param($stmt, "ssssss", $search_param, $search_param, $search_param, $search_param, $search_param, $search_param);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        echo '<form action="" method="post">';
        while ($row = mysqli_fetch_assoc($result)) {
            echo ' 
            <div class="grid pt-10 pb-30 h-full place-items-center ">
                <div class="w-1/4 h-9/10 border border-solid shadow-2xl overflow-hidden">
                    <div class="bg-blue-700 py-3">
                        <h1 class="w-full font-bold text-white text-center">Sit-in Registration</h1>
                    </div>
                    <div class="p-6">
                        <input type="hidden" name="idno[]" value="' . $row['idno'] . '">
                        <label class="block text-gray-700 font-bold pb-1">ID No:</label>
                        <input type="text" value="' . $row['idno'] . '" class="w-full border border-black p-2" disabled>

                        <label class="block text-gray-700 font-bold pb-1">Last Name:</label>
                        <input type="text" value="' . $row['lastname'] . '" class="w-full border border-black p-2" disabled>

                        <label class="block text-gray-700 font-bold pb-1">First Name:</label>
                        <input type="text" value="' . $row['firstname'] . '" class="w-full border border-black p-2" disabled>

                        <label class="block text-gray-700 font-bold pb-1">Course:</label>
                        <input type="text" value="' . $row['course'] . '" class="w-full border border-black p-2" disabled>

                        <label class="block text-gray-700 font-bold pb-1">Sit-in Purpose</label>
                        <select name="sitin_purpose[]" class="w-full border border-black p-2 rounded mb-4">
                            <option value="Programming">Programming</option>
                            <option value="C">C</option>
                            <option value="C++">C++</optio  n>
                        </select>
                    </div>
                </div>
            </div>';
        }
        echo '<div class="text-center mt-4">
                <button type="submit" name="register_sitin" class="px-4 py-2 bg-blue-600 text-white rounded">Register Sit-in</button>
              </div>
              </form>';
    } else {
        echo "No students found.";
    }
    mysqli_stmt_close($stmt);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_sitin'])) {
    $idnos = $_POST['idno'];
    $sitin_purposes = $_POST['sitin_purpose'];

    foreach ($idnos as $index => $idno) {
        $sitin_purpose = $sitin_purposes[$index];

        if (!$mysql) {
            die("Database connection failed: " . mysqli_connect_error());
        }
        $studentQuery = "SELECT lastname, firstname, midname, course, year FROM students WHERE idno = ?";
        $studentStmt = mysqli_prepare($mysql, $studentQuery);
        mysqli_stmt_bind_param($studentStmt, "s", $idno);
        mysqli_stmt_execute($studentStmt);
        $studentResult = mysqli_stmt_get_result($studentStmt);

        if ($row = mysqli_fetch_assoc($studentResult)) {
            $lastname = $row['lastname'];
            $firstname = $row['firstname'];
            $midname = $row['midname'];
            $course = $row['course'];
            $year = $row['year'];
        } else {
            echo "<script>alert('Student ID $idno not found!');</script>";
            continue;
        }
        
        // Check if student is already sitting in
        $checkStmt = mysqli_prepare($mysql, "SELECT * FROM sit_in WHERE idno = ? AND time_out IS NULL");

        if (!$checkStmt) {
            die("Query Preparation Failed: " . mysqli_error($mysql));
        }

        mysqli_stmt_bind_param($checkStmt, "s", $idno);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);

        if ($checkResult->num_rows > 0) {
            echo "<script>alert('Student with ID $idno is still currently sitting in and has not logged out yet.'); window.location.href='admindashboard.php';</script>";
            continue;
        }

        // Insert into sit_in_records
        $insertStmt = mysqli_prepare($mysql, "INSERT INTO sit_in (idno, lastname, firstname, midname, course, year, sitin_purpose, time_in) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        if (!$insertStmt) {
            die("Query Preparation Failed: " . mysqli_error($mysql));
        }
        mysqli_stmt_bind_param($insertStmt, "sssssis", $idno, $lastname, $firstname, $midname, $course, $year, $sitin_purpose);
        mysqli_stmt_execute($insertStmt);
    }

    echo "<script>alert('Sit-in registered successfully!'); window.location.href='admin_dashboard.php';</script>";
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
    <div class="flex items-center justify-center">
        <div class="border border-solid shadow-2xl w-1/4 h-48 text-center">
            <div class="mt-20">
                <form action="" method="post">
                    <input type="text" class="border border-solid w-2/3 p-2" name="search" placeholder="Search">
                    <input type="submit" class="border border-solid px-3 p-2 bg-blue-600 text-white" name="submit" value="Search">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
