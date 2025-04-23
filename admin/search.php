<?php
session_start();
include "../database/connector.php";
include "../admin/admin_nav.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_sitin'])) {
    $idnos = $_POST['idno'];
    $sitin_purposes = $_POST['sitin_purpose'];
    $lab = $_POST['lab'];
    
    foreach ($idnos as $index => $idno) {
        $sitin_purpose = $sitin_purposes[$index];
        $lab = $lab[$index];
        

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
        $insertStmt = mysqli_prepare($mysql, "INSERT INTO sit_in (idno, lastname, firstname, midname, course, year, sitin_purpose, lab, time_in) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        if (!$insertStmt) {
            die("Query Preparation Failed: " . mysqli_error($mysql));
        }
        mysqli_stmt_bind_param($insertStmt, "sssssiss", $idno, $lastname, $firstname, $midname, $course, $year, $sitin_purpose, $lab);
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
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Search Form -->
            <div class="bg-white shadow-2xl rounded-lg p-6 mb-8">
                <form action="" method="post" class="flex items-center justify-center space-x-2">
                    <div class="relative w-full">
                        <input type="text" name="search" placeholder="Enter ID, Name, or Course" 
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                        <svg class="absolute left-3 top-3 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <button type="submit" name="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Search
                    </button>
                </form>
            </div>
            <!-- Search Results -->
            <?php if (isset($_POST['submit']) && !empty($_POST['search'])): ?>
                <?php
                $search = $_POST['search'];
                $search_param = "%$search%";
                $query = "SELECT * FROM students WHERE idno LIKE ? OR lastname LIKE ? OR firstname LIKE ? OR midname LIKE ? OR course LIKE ? OR year LIKE ? LIMIT 1";
                $stmt = mysqli_prepare($mysql, $query);
                
                if (!$stmt) {
                    die("Query Preparation Failed: " . mysqli_error($mysql));
                }

                mysqli_stmt_bind_param($stmt, "ssssss", $search_param, $search_param, $search_param, $search_param, $search_param, $search_param);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                mysqli_stmt_close($stmt);

                if ($result && mysqli_num_rows($result) > 0): ?>
                    <div class="bg-white shadow-2xl rounded-lg p-6">
                        <form action="" method="post">
                            <h2 class="text-xl font-semibold mb-4">Search Results</h2>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50 text-center">
                                        <tr>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Course & Year</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Session</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Lab</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td class="text-center px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['idno']); ?></td>
                                                <td class="text-center px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['lastname'] . ', ' . $row['firstname'] . ' ' . $row['midname']); ?></td>
                                                <td class="text-center px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['course'] . ' - ' . $row['year']); ?></td>
                                                <td class="text-center px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($row['session']); ?></td>
                                                <td class="text-center px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <select name="sitin_purpose[]" class="border border-gray-300 p-2 rounded-md bg-white">
                                                        <option value="C Programming">C Programming</option>
                                                        <option value="C#">C#</option>
                                                        <option value="Java">Java</option>
                                                        <option value="PHP">PHP</option>
                                                        <option value="Database">Database</option>
                                                        <option value="Digital & Logic Design">Digital & Logic Design</option>
                                                        <option value="Embedded Systems & IOT">Embedded Systems & IOT</option>
                                                        <option value="Python Programming">Python Programming</option>
                                                        <option value="System Integration & Architecture">System Integration & Architecture</option>
                                                        <option value="Computer Application">Computer Application</option>
                                                        <option value="Web Design & Development">Web Design & Development</option>
                                                        <option value="Project Management">Project Management</option>
                                                    </select>
                                                </td>
                                                <td class="text-center px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <select name="lab[]" class="w-full border border-gray-300 p-2 rounded-md bg-white">
                                                        <option value="524">524</option>
                                                        <option value="526">526</option>
                                                        <option value="528">528</option>
                                                        <option value="530">530</option>
                                                        <option value="542">542</option>
                                                        <option value="544">544</option>
                                                        <option value="517">517</option>
                                                    </select>
                                                </td>
                                                <td class="text-center px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <form action="" method="post" class="inline">
                                                        <input type="hidden" name="idno[]" value="<?php echo htmlspecialchars($row['idno']); ?>">
                                                        <button type="submit" name="register_sitin" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                                                            Register Sit-in
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="bg-white shadow-2xl rounded-lg p-6 text-center">
                        <p class="text-gray-500">No students found matching your search criteria.</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>