<?php
session_start();
include "../user/nav.php";
include "../database/connector.php";
include "../database/authenticator.php";

// Fetch all resources
$query = "SELECT * FROM resources ORDER BY upload_date DESC";
$result = mysqli_query($mysql, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resources - CCS Monitoring System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6">Available Resources</h2>
                
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                        <?php echo htmlspecialchars($row['title']); ?>
                                    </h3>
                                    <p class="text-gray-600 text-sm mb-4">
                                        <?php echo htmlspecialchars($row['description']); ?>
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500">
                                            <?php echo date('M d, Y', strtotime($row['upload_date'])); ?>
                                        </span>
                                        <a href="<?php echo htmlspecialchars($row['file_path']); ?>" 
                                           target="_blank"
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No resources available</h3>
                        <p class="mt-1 text-sm text-gray-500">Check back later for new resources.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html> 