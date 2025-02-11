<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>CCS Sit-In Monitoring System</title>
</head>
<body class="bg-white font-sans">
    <?php if(isset($_SESSION["success"])): ?>
        <div class="bg-green-500 text-white p-2 text-center">
            <?php
               echo $_SESSION['success'];
               unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <!-- Navbar -->
    <nav class="bg-white shadow-md fixed w-full top-0 left-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Left Logo -->
                <div class="flex items-center float-left">
                    <img class="h-8 w-auto" src="images/ccs.png" alt="CCS Logo">
                    <h1 class="ml-3 text-lg font-semibold">CCS Sit-In Monitoring System</h1>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex justify-end flex-1 space-x-6">
                    <a href="home.php" class="hover:text-blue-600">View Announcement</a>
                    <a href="home.php" class="hover:text-blue-600">View Remaining Session</a>
                    <a href="home.php" class="hover:text-blue-600">Sit-in Rules</a>
                    <a href="home.php" class="hover:text-blue-600">Lab Rules & Regulations</a>
                    <a href="home.php" class="hover:text-blue-600">History</a>
                    <a href="home.php" class="hover:text-blue-600">Reservation</a>
                </div>

                <!-- Profile Section
                <div class="relative">
                    <button type="button" class="relative flex items-center space-x-2 rounded-full bg-gray-800 p-1 text-white focus:ring-2 focus:ring-white focus:ring-offset-2">
                        <img class="w-8 h-8 rounded-full" src="images/profile.png" alt="Profile">
                    </button>
                </div>
                -->
            </div>
        </div>
    </nav>

    <!-- Content Spacer to Avoid Overlap -->
    <div class="mt-16"></div>
</body>
</html>
