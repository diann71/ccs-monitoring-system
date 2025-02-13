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

    <nav class="flex items-center justify-between border-b border-black p-4 shadow-md">
        <div class="flex items-center space-x-4">
            <img src="images/ccs.png" alt="Logo" class="w-14">
            <h1 class="text-2xl font-medium">CCS SIT-IN MONITORING SYSTEM</h1>
        </div>
        <ul class="hidden md:flex space-x-6 text-lg uppercase">
            <li><a href="home.php" class="hover:text-blue-600">View Announcement</a></li>
            <li><a href="home.php" class="hover:text-blue-600">View Remaining Session</a></li>
            <li><a href="home.php" class="hover:text-blue-600">Sit-in Rules</a></li>
            <li><a href="home.php" class="hover:text-blue-600">Lab Rules & Regulation</a></li>
            <li><a href="home.php" class="hover:text-blue-600">History</a></li>
            <li><a href="home.php" class="hover:text-blue-600">Reservation</a></li>
        </ul>
        <div class="relative">
            <img src="images/profile.png" alt="Profile" class="w-10 cursor-pointer" id="profileMenu">
            <div class="absolute right-0 mt-2 w-40 bg-white shadow-lg hidden" id="dropdownMenu">
                <a href="profile.php" class="block px-4 py-2 hover:bg-gray-200">PROFILE</a>
                <a href="logout.php" class="block px-4 py-2 hover:bg-gray-200">LOGOUT</a>
            </div>
        </div>
    </nav>

    <script>
        document.getElementById('profileMenu').addEventListener('click', function() {
            document.getElementById('dropdownMenu').classList.toggle('hidden');
        });
    </script>
</body>
</html>
