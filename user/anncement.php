<?php session_start();

include "../user/nav.php";
include "../database/connector.php";
include "../database/authenticator.php";

if(!isset($_SESSION['idno'])){
    $_SESSION['error'] = "Please log in to view your profile.";
    header("Location: login.php"); 
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto p-6 mt-10">
        <div class="bg-white rounded-xl shadow-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">All Announcements</h2>
                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Admin Updates</span>
            </div>
            <div class="space-y-4 max-h-[600px] overflow-y-auto pr-2">
                <?php 
                $result = mysqli_query($mysql, "SELECT * FROM announcements ORDER BY created_at DESC");
                if ($result && mysqli_num_rows($result) > 0) {
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
                } else {
                    echo "<p class='text-center text-gray-500'>No announcements found.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>


