<?php
session_start();
include "connector.php";
include "admin_nav.php";

$hideSearch = false; // Default state

if (isset($_POST['submit'])) {
    $search = $_POST['search'];
    $search_param = "%$search%";

    if(empty($search)){
        echo "<script>alert('Please enter a ID, Name or Course!');window.location.href='search.php';</script>";
    } else {

        
    

        $query = "SELECT * FROM students WHERE idno LIKE ? OR lastname LIKE ? OR firstname LIKE ? OR midname LIKE ? OR course LIKE ? OR year LIKE ? LIMIT 1";
        $stmt = mysqli_prepare($mysql, $query);
        
        if (!$stmt) {
            die("Query Preparation Failed: " . mysqli_error($mysql));
        }

        mysqli_stmt_bind_param($stmt, "ssssss", $search_param, $search_param, $search_param, $search_param, $search_param, $search_param);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $hideSearch = true; // Hide search after results appear
            echo '<form action="" method="post">';
            while ($row = mysqli_fetch_assoc($result)) {
                echo ' 
                    <div class="grid pt-10 pb-30 h-full place-items-center">
                        <div class="w-full max-w-lg border border-gray-300 shadow-lg bg-white rounded-lg overflow-hidden">
                            <div class="bg-gray-600 py-2">
                                <h1 class="text-white text-center text-xl font-bold">Sit-in Registration</h1>
                            </div>
                            <div class="p-6">
                                <input type="hidden" name="idno[]" value="' . $row['idno'] . '">

                                <label class="block text-gray-700 font-semibold pb-1">ID No:</label>
                                <input type="text" value="' . $row['idno'] . '" class="w-full border border-gray-300 p-2 rounded-md bg-gray-100" disabled>

                                <label class="block text-gray-700 font-semibold pb-1 mt-4">Last Name:</label>
                                <input type="text" value="' . $row['lastname'] . '" class="w-full border border-gray-300 p-2 rounded-md bg-gray-100" disabled>

                                <label class="block text-gray-700 font-semibold pb-1 mt-4">First Name:</label>
                                <input type="text" value="' . $row['firstname'] . '" class="w-full border border-gray-300 p-2 rounded-md bg-gray-100" disabled>

                                <label class="block text-gray-700 font-semibold pb-1 mt-4">Course:</label>
                                <input type="text" value="' . $row['course'] . '" class="w-full border border-gray-300 p-2 rounded-md bg-gray-100" disabled>

                                <label class="block text-gray-700 font-semibold pb-1 mt-4">Sit-in Purpose:</label>
                                <select name="sitin_purpose[]" class="w-full border border-gray-300 p-2 rounded-md bg-white">
                                    <option value="Programming">Programming</option>
                                    <option value="Research">Research</option>
                                    <option value="Networking">Networking</option>
                                </select>

                                <label class="block text-gray-700 font-semibold pb-1 mt-4">Session:</label>
                                <input type="text" value="' . $row['session'] . '" class="w-full border border-gray-300 p-2 rounded-md bg-gray-100" disabled>
                            </div>

                            <div class="text-center pb-5">
                                <button type="submit" name="register_sitin" class="px-4 py-2 bg-blue-600 text-white rounded">Sit-in</button>
                            </div>
                        </div>
                    </div> 
                </form>';
            }
        } else {
            echo "No students found.";
        }
        mysqli_stmt_close($stmt);
    }
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
            echo "<script>alert('Student with ID $idno is still currently sitting in and has not logged out yet.'); window.location.href='search.php';</script>";
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
    <title>Search Students</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        window.onload = function() {
            var hideSearch = <?php echo json_encode($hideSearch); ?>;
            if (hideSearch) {
                document.getElementById("search-container").style.display = "none";
            }
        };
    </script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="flex items-center justify-center">
        <div id="search-container" class="bg-white shadow-2xl rounded-lg p-6 w-96">
            <form action="" method="post" class="mt-4">
                <input type="text" name="search" placeholder="Enter ID, Name, or Course" 
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" name="submit" 
                    class="mt-3 w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Search</button>
            </form>
        </div>
    </div>
</body>
</html>
