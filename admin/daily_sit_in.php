<?php 
session_start();

include "../database/connector.php";
include "../admin/admin_nav.php";    


// Query to get all completed sit-in records for today
$query = "SELECT students.idno, students.lastname, students.firstname, students.midname, students.course, students.year, 
          sit_in.sitin_purpose, sit_in.lab, sit_in.time_in, sit_in.time_out 
          FROM sit_in
          JOIN students ON sit_in.idno = students.idno
          WHERE DATE(sit_in.time_in) = CURDATE() 
          AND sit_in.time_out IS NOT NULL
          ORDER BY sit_in.time_in DESC";

$result = mysqli_query($mysql, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Sit-in Records</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow-2xl rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold">Daily Sit-in Records</h2>
                    <p class="text-sm text-gray-600"><?php echo date('F d, Y'); ?></p>
                </div>
                
                <div class="overflow-x-auto">
                    <div class="grid grid-cols-7 text-center border-b-2 py-3 bg-gray-50">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">ID</p>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Name</p>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Course & Year</p>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</p>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Lab</p>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</p>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Time Out</p>
                    </div>
                    <div class="overflow-y-auto max-h-[600px]">
                        <?php while ($row = mysqli_fetch_assoc($result)): 
                            $time_in = strtotime($row['time_in']);
                            $time_out = strtotime($row['time_out']);
                        ?>
                            <div class="grid grid-cols-7 text-center border-b py-4 hover:bg-gray-50 transition">
                                <p class="text-sm text-gray-900"><?php echo htmlspecialchars($row['idno']); ?></p>
                                <p class="text-sm text-gray-900"><?php echo htmlspecialchars($row['lastname'] . ', ' . $row['firstname'] . ' ' . $row['midname']); ?></p>
                                <p class="text-sm text-gray-900"><?php echo htmlspecialchars($row['course'] . ' - ' . $row['year']); ?></p>
                                <p class="text-sm text-gray-900"><?php echo htmlspecialchars($row['sitin_purpose']); ?></p>
                                <p class="text-sm text-gray-900"><?php echo htmlspecialchars($row['lab']); ?></p>
                                <p class="text-sm text-gray-900"><?php echo date('h:i A', $time_in); ?></p>
                                <p class="text-sm text-gray-900"><?php echo date('h:i A', $time_out); ?></p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 