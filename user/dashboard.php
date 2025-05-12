<?php session_start();

include "../database/connector.php";
include "../user/nav.php";
include "../database/authenticator.php";

$result = mysqli_query($mysql, "SELECT * FROM announcements");    
$row = mysqli_fetch_assoc($result);

if($row){
    $announcement_id = $row['announcement_id'];
    $title = $row['title'];
    $description = $row['description'];
    $created_at = $row['created_at'];
}

// Fetch points for the current user
$points_query = "SELECT * FROM students WHERE idno = '$idno'";
$points_result = mysqli_query($mysql, $points_query);
$points_row = mysqli_fetch_assoc($points_result);
$points = $points_row ? $points_row['points'] : 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        }
        .card-hover {
            transition: transform 0.2s ease-in-out;
        }
        .card-hover:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto p-6">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Welcome back, <?php echo $firstname . ' ' . $lastname; ?>!</h1>
            <p class="text-gray-600 mt-2">Here's what's happening with your sit-in sessions today.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Student Information Card -->
            <div class="bg-white rounded-xl shadow-2xl p-6 card-hover">
                <div class="text-center">
                    <div class="relative inline-block">
                        <img class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-indigo-100" src="../uploads/<?php echo $profile; ?>" alt="Profile Picture">
                        <div class="absolute bottom-0 right-0 bg-green-500 rounded-full p-1.5 border-2 border-white">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-xl font-bold mt-4 text-gray-900"><?php echo $firstname . ' ' . $lastname; ?></h2>
                    <p class="text-gray-600"><?php echo $course; ?> - Year <?php echo $year; ?></p>
                </div>
                <div class="mt-6 space-y-4">
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="bg-indigo-100 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">ID Number</p>
                            <p class="text-lg font-semibold text-gray-900"><?php echo $idno; ?></p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="bg-indigo-100 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Sessions Remaining</p>
                            <p class="text-lg font-semibold text-gray-900"><?php echo $session; ?></p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="bg-indigo-100 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Points</p>
                            <p class="text-lg font-semibold text-gray-900"><?php echo $points; ?></p>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <a href="profile.php" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        View Profile
                    </a>
                </div>
            </div>

            <!-- Announcements Card -->
            <div class="bg-white rounded-xl shadow-2xl p-6 col-span-2 card-hover">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Recent Announcements</h2>
                    <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Latest Updates</span>
                </div>
                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                    <?php 
                    $result = mysqli_query($mysql, "SELECT * FROM announcements ORDER BY created_at DESC");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors duration-200'>";
                        echo "<div class='flex items-start justify-between'>";
                        echo "<div>";
                        echo "<h3 class='font-semibold text-gray-900'>" . htmlspecialchars($row['title']) . "</h3>";
                        echo "<p class='text-sm text-gray-600 mt-1'>" . htmlspecialchars($row['description']) . "</p>";
                        echo "</div>";
                        echo "<span class='text-xs text-gray-500 whitespace-nowrap ml-4'>" . date('M d, Y', strtotime($row['created_at'])) . "</span>";
                        echo "</div>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Rules and Recent History Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <!-- Rules and Regulations -->
            <div class="bg-white rounded-xl shadow-2xl p-6 card-hover">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Rules and Regulations</h2>
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Important</span>
                </div>
                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                    <div class="text-center mb-6">
                        <h2 class="text-lg font-bold text-gray-900">University of Cebu</h2>
                        <h2 class="text-base font-bold text-gray-700">COLLEGE OF INFORMATION & COMPUTER STUDIES</h2>
                    </div>
                    <div class="space-y-3 text-sm text-gray-600">
                        <p class="font-medium text-gray-900">To avoid embarrassment and maintain camaraderie with your friends and superiors at our laboratories, please observe the following:</p>
                        <div class="space-y-2">
                            <p class="flex items-start">
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-0.5 rounded-full mr-2">1</span>
                                Maintain silence, proper decorum, and discipline inside the laboratory. Mobile phones, walkmans, and other personal pieces of equipment must be switched off.
                            </p>
                            <p class="flex items-start">
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-0.5 rounded-full mr-2">2</span>
                                Games are not allowed inside the lab. This includes computer-related games, card games, and other games that may disturb the operation of the lab.
                            </p>
                            <p class="flex items-start">
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-0.5 rounded-full mr-2">3</span>
                                Surfing the Internet is allowed only with the permission of the instructor. Downloading and installing of software are strictly prohibited.
                            </p>
                            <p class="flex items-start">
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-0.5 rounded-full mr-2">4</span>
                                Getting access to other websites not related to the course (especially pornographic and illicit sites) is strictly prohibited.
                            </p>
                            <p class="flex items-start">
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-0.5 rounded-full mr-2">5</span>
                                Deleting computer files and changing the set-up of the computer is a major offense.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent History -->
            <div class="bg-white rounded-xl shadow-2xl p-6 card-hover">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Recent History</h2>
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Activity Log</span>
                </div>
                <div class="overflow-x-auto">
                    <div class="min-w-full">
                        <div class="bg-gray-50 rounded-lg">
                            <div class="grid grid-cols-4 text-center py-3 px-4 border-b border-gray-200">
                                <p class="font-semibold text-gray-900">ID</p>
                                <p class="font-semibold text-gray-900">Purpose</p>
                                <p class="font-semibold text-gray-900">Time In</p>
                                <p class="font-semibold text-gray-900">Time Out</p>
                            </div>
                            <div class="divide-y divide-gray-200 max-h-[400px] overflow-y-auto">
                                <?php 
                                $query_completed = "SELECT sit_in.idno, sit_in.sitin_purpose, sit_in.time_in, sit_in.time_out 
                                    FROM sit_in 
                                    WHERE sit_in.idno = '$idno' AND sit_in.time_out IS NOT NULL 
                                    ORDER BY sit_in.time_out DESC";
                                $result_completed = mysqli_query($mysql, $query_completed);

                                if ($result_completed && mysqli_num_rows($result_completed) > 0) {
                                    while ($row = mysqli_fetch_assoc($result_completed)) {
                                        echo "<div class='grid grid-cols-4 text-center py-3 px-4 hover:bg-gray-50'>";
                                        echo "<p class='text-sm text-gray-900'>" . htmlspecialchars($row['idno']) . "</p>";
                                        echo "<p class='text-sm text-gray-900'>" . htmlspecialchars($row['sitin_purpose']) . "</p>";
                                        echo "<p class='text-sm text-gray-600'>" . date('M d, Y - h:i A', strtotime($row['time_in'])) . "</p>";
                                        echo "<p class='text-sm text-gray-600'>" . date('M d, Y - h:i A', strtotime($row['time_out'])) . "</p>";
                                        echo "</div>";
                                    }
                                } else {
                                    echo "<div class='text-center py-4 text-gray-500'>No history records found</div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

