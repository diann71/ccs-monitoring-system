<?php 
session_start();

include "../database/connector.php";
include "../admin/admin_nav.php";    

$query_current = "SELECT students.idno, students.lastname, students.firstname, students.midname, students.course, students.year, sit_in.sitin_purpose, sit_in.lab, sit_in.time_in 
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
    <title>Current Sit-in</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow-2xl rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Current Sit-in</h2>
                <div class="overflow-x-auto">
                    <div class="grid grid-cols-7 text-center border-b-2 py-3 bg-gray-50">
                        <p class=" text-xs text-center font-medium text-gray-500 uppercase tracking-wider">ID</p>
                        <p class="text-xs text-center font-medium text-gray-500 uppercase tracking-wider">Name</p>
                        <p class="text-xs text-center font-medium text-gray-500 uppercase tracking-wider">Course & Year</p>
                        <p class="text-xs text-center font-medium text-gray-500 uppercase tracking-wider">Purpose</p>
                        <p class="text-xs text-center font-medium text-gray-500 uppercase tracking-wider">Lab</p>
                        <p class="text-xs text-center font-medium text-gray-500 uppercase tracking-wider">Time In</p>
                        <p class="text-xs text-center font-medium text-gray-500 uppercase tracking-wider">Action</p>
                    </div>
                    <div class="overflow-y-auto max-h-[600px]">
                        <?php while ($row = mysqli_fetch_assoc($result_current)): ?>
                            <div class="grid grid-cols-7 text-center border-b py-4 hover:bg-gray-50 transition gap-5">
                                <p class="text-sm text-center text-gray-900 flex items-center justify-center"><?php echo htmlspecialchars($row['idno']); ?></p>
                                <p class="text-sm text-center text-gray-900 flex items-center justify-center"><?php echo htmlspecialchars($row['lastname'] . ', ' . $row['firstname'] . ' ' . $row['midname']); ?></p>
                                <p class="text-sm text-center text-gray-900 flex items-center justify-center"><?php echo htmlspecialchars($row['course'] . ' - ' . $row['year']); ?></p>
                                <p class="text-sm text-center text-gray-900 flex items-center justify-center"><?php echo htmlspecialchars($row['sitin_purpose']); ?></p>
                                <p class="text-sm text-center text-gray-900 flex items-center justify-center"><?php echo htmlspecialchars($row['lab']); ?></p>
                                <p class="text-sm text-center text-gray-900 flex items-center justify-center"><?php echo date('M d, Y - h:i A', strtotime($row['time_in'])); ?></p>
                                <div class="flex items-center justify-center">
                                    <form action="" method="post" class="inline">
                                        <input type="hidden" name="idno" value="<?php echo htmlspecialchars($row['idno']); ?>">
                                        <button type="submit" name="timeout" class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                                            Time Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
