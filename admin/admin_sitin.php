<?php 
session_start();

include "../database/connector.php";
include "../admin/admin_nav.php";
include "../database/admin_auth.php";    

$query_current = "SELECT students.idno, students.lastname, students.firstname, students.midname, students.course, students.year, students.session, students.points, sit_in.sitin_purpose, sit_in.lab, sit_in.time_in 
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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rewards'])) {
    $idno = $_POST['idno'];

    // Fetch current points for the student
    $points_query = "SELECT points FROM students WHERE idno = ?";
    $stmt = mysqli_prepare($mysql, $points_query);
    mysqli_stmt_bind_param($stmt, "s", $idno);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $points);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Update time_out to the current time
    $update_session = "UPDATE students SET points = points + 1 WHERE idno = ?";
    $stmt = mysqli_prepare($mysql, $update_session);
    mysqli_stmt_bind_param($stmt, "s", $idno);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Time Out Successful!'); window.location.href='admin_sitin.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($mysql) . "');</script>";
    }

    mysqli_stmt_close($stmt);

    if($points % 3 == 0){
        $update_query = "UPDATE students SET session = session + 1 WHERE idno = ?";
        $stmt = mysqli_prepare($mysql, $update_query);
        mysqli_stmt_bind_param($stmt, "s", $idno);
        
        // Execute the update query to increment the session
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Session incremented!'); window.location.href='admin_sitin.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($mysql) . "');</script>";
        }
    }
    
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Sit-in</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
    <body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow-2xl rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Current Sit-in</h2>
                <div class="overflow-x-auto">
                    <div class="grid grid-cols-[1fr_2fr_1fr_1.5fr_1fr_1.5fr_1fr_1fr_1.5fr] text-center">
                        <!-- Header -->
                        <div class="border-b-2 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center justify-center">ID</div>
                        <div class="border-b-2 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center justify-center">Name</div>
                        <div class="border-b-2 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center justify-center">Course & Year</div>
                        <div class="border-b-2 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center justify-center">Purpose</div>
                        <div class="border-b-2 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center justify-center">Lab</div>
                        <div class="border-b-2 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center justify-center">Time In</div>
                        <div class="border-b-2 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center justify-center">Session</div>
                        <div class="border-b-2 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center justify-center">Points</div>
                        <div class="border-b-2 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center justify-center">Action</div>
                        <!-- Data Rows -->
                        <?php while ($row = mysqli_fetch_assoc($result_current)): ?>
                            <div class="border-b py-4 flex items-center justify-center text-sm text-gray-900"><?php echo htmlspecialchars($row['idno']); ?></div>
                            <div class="border-b py-4 flex items-center justify-center text-sm text-gray-900"><?php echo htmlspecialchars($row['lastname'] . ', ' . $row['firstname'] . ' ' . $row['midname']); ?></div>
                            <div class="border-b py-4 flex items-center justify-center text-sm text-gray-900"><?php echo htmlspecialchars($row['course'] . ' - ' . $row['year']); ?></div>
                            <div class="border-b py-4 flex items-center justify-center text-sm text-gray-900"><?php echo htmlspecialchars($row['sitin_purpose']); ?></div>
                            <div class="border-b py-4 flex items-center justify-center text-sm text-gray-900"><?php echo htmlspecialchars($row['lab']); ?></div>
                            <div class="border-b py-4 flex items-center justify-center text-sm text-gray-900"><?php echo date('M d, Y - h:i A', strtotime($row['time_in'])); ?></div>
                            <div class="border-b py-4 flex items-center justify-center text-sm text-gray-900"><?php echo htmlspecialchars($row['session']); ?></div>
                            <div class="border-b py-4 flex items-center justify-center text-sm text-gray-900"><?php echo htmlspecialchars($row['points']); ?></div>
                            <div class="border-b py-4 flex items-center justify-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <form action="" method="post" class="inline">
                                        <input type="hidden" name="idno" value="<?php echo htmlspecialchars($row['idno']); ?>">
                                        <button type="submit" name="rewards"
                                            class="inline-flex items-center px-3 py-1 text-sm rounded
                                                bg-green-600 text-white hover:bg-green-700
                                                transition-colors duration-150">
                                            <i class="fas fa-gift mr-1"></i>
                                            Rewards
                                        </button>
                                    </form>
                                    <form action="" method="post" class="inline">
                                        <input type="hidden" name="idno" value="<?php echo htmlspecialchars($row['idno']); ?>">
                                        <button type="submit" name="timeout"
                                            class="inline-flex items-center px-3 py-1 text-sm rounded
                                                bg-red-600 text-white hover:bg-red-700
                                                transition-colors duration-150">
                                            <i class="fas fa-sign-out-alt mr-1"></i>
                                            Timeout
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
