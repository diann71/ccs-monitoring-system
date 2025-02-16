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
        <div class="bg-green-500 text-white p-2 text-center">
            <?php
               echo $_SESSION['success'];
               unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

<div class="min-h-full">
    <nav class="bg-white shadow">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <!-- Logo & Navigation Links -->
                <div class="flex items-center">
                    <a href="#">
                        <img src="images/ccs.png" alt="Logo" class="size-9">
                    </a>    
                    <div class="hidden md:flex ml-10 space-x-4">
                        <a href="dashboard.php" class="rounded-md bg-gray-700 px-3 py-2 text-sm font-medium text-white">Dashboard</a>
                        <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-700 hover:text-white">View Announcement</a>
                        <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-700 hover:text-white">View Remaining Session</a>
                        <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-700 hover:text-white">Sit-in Rules</a>
                        <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-700 hover:text-white">Lab Rules & Regulation</a>
                        <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-700 hover:text-white">History</a>
                        <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-700 hover:text-white">Reservation</a>
                    </div>
                </div>
                
                <!-- Profile Dropdown -->
                <div class="relative">
                    <button id="profileMenu" class="flex items-center rounded-full bg-gray-800 p-1 focus:ring-2 focus:ring-white">
                        <img src="images/profile.png" alt="Profile" class="size-8 rounded-full">
                    </button>
                    <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-32 rounded-md bg-white shadow-lg py-1 ring-1 ring-black/5">
                        <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-700 hover:text-white">Profile</a>
                        <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-700 hover:text-white">Sign out</a>
                    </div>
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
            <a href="#" class="block rounded-md bg-gray-900 px-3 py-2 text-base font-medium text-white">Dashboard</a>
            <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-700 hover:text-white">View Announcement</a>
            <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-700 hover:text-white">View Remaining Session</a>
            <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-700 hover:text-white">Sit-in Rules</a>
            <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-700 hover:text-white">Lab Rules & Regulation</a>
            <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-700 hover:text-white">History</a>
            <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-700 hover:text-white">Reservation</a>

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

<script>
    document.getElementById('profileMenu').addEventListener('click', function() {
        document.getElementById('dropdownMenu').classList.toggle('hidden');
    });
    
    document.getElementById('mobileMenuButton').addEventListener('click', function() {
        document.getElementById('mobileMenu').classList.toggle('hidden');
        document.getElementById('menuOpenIcon').classList.toggle('hidden');
        document.getElementById('menuCloseIcon').classList.toggle('hidden');
    }); 
</script>


    <!--<nav class="flex items-center justify-between border-b border-black p-4 shadow-md">
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
    </script>-->
</body>
</html>
