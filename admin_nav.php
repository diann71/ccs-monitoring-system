
<?php
include "connector.php";
?>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>CCS Sit-In Monitoring System</title>
</head>
<body class="bg-white font-sans ">
    <?php if(isset($_SESSION["success"])): ?>
        <div class="bg-green-500 text-black p-2 text-center">
            <?php
               echo $_SESSION['success'];
               unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>
<div class="min-h-full">
    <nav class="shadow">
        <div class="px-2">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="#">
                        <img src="images/ccs.jpg" alt="Logo" class="size-12">
                    </a>
                </div>

                <!-- Right side: Navigation Links and Profile Dropdown -->
                <div class="flex items-center ml-auto">
                    <!-- Navigation Links -->
                    <div class="hidden md:flex">
                        <a href="search.php" class="flex items-center justify-center h-12 px-3 py-2 text-base font-medium text-black hover:bg-blue-700 hover:text-white transition duration-300 mr">Search</a>
                        <a href="#" class="flex items-center justify-center h-12 px-3 py-2 text-base font-medium text-black hover:bg-blue-700 hover:text-white transition duration-300">Student List</a>
                        <a href="admin_annc.php" class="flex items-center justify-center h-12 px-3 py-2 text-base font-medium text-black hover:bg-blue-700 hover:text-white transition duration-300">Announcements</a>
                        <a href="admin_sitin.php" class="flex items-center justify-center h-12 px-3 py-2 text-base font-medium text-black hover:bg-blue-700 hover:text-white transition duration-300">Current Sit-in Record</a>
                            <a href="logout.php" class="flex items-center justify-center bg-red-600 h-12 px-3 py-2 text-base font-medium text-white hover:bg-red-700 hover:text-white">Sign out</a>

                    </div>
                
                <!-- Profile Dropdown -->
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobileMenuButton" class="md:hidden flex items-center bg-gray-800 p-2 rounded-md text-gray-400 hover:bg-gray-700 hover:text-white">
                    <svg id="menuOpenIcon" class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg id="menuCloseIcon" class="hidden size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden space-y-1 px-2 pb-3 sm:px-3">
            <a href="#" class="block rounded-md bg-gray-900 px-3 py-2 text-base font-medium text-white">Search</a>
            <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-700 hover:text-white">Student List</a>
            <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-700 hover:text-white">Announcements</a>
            <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-700 hover:text-white">Current Sit-in Record</a>
            <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-700 hover:text-white">Sign out</a>


            <div class="border-t border-gray-700 pt-4 pb-3">        
                <div class="mt-3 space-y-1 px-2">
                    <a href="profile.php" class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-700 hover:text-white">Profile</a>
                    <a href="logout.php" class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-700 hover:text-white">Log out</a>
                </div>
            </div>

        </div>
        

    </nav>

    <!-- Page Header
    <header class="bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Dashboard</h1>
        </div>
    </header>

    <!-- Main Content
    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <!-- Your content goes here
        </div>
        -->
    </main>
</div>
</body>
</html>

<script>
    document.getElementById('mobileMenuButton').addEventListener('click', function() {
        document.getElementById('mobileMenu').classList.toggle('hidden');
        document.getElementById('menuOpenIcon').classList.toggle('hidden');
        document.getElementById('menuCloseIcon').classList.toggle('hidden');
    }); 
</script>