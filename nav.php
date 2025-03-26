<?php
include "connector.php";

$idno = $_SESSION["idno"];

$result = mysqli_query($mysql, "SELECT `profile` FROM students WHERE idno = '$idno'");
$row = mysqli_fetch_assoc($result);

if($row){
    $profile = $row["profile"];
}
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
        <div class="px-4 sm:px-6 lg:px-8">
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
                        <a href="dashboard.php" class="flex items-center justify-center h-12 px-3 py-2 text-base font-medium text-black hover:bg-blue-700 hover:text-white transition duration-300 mr">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-5 h-5 mr-2 bi bi-house-door-fill" viewBox="0 0 16 16">
                                <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5"/>
                            </svg>
                            Dashboard
                        </a>
                        <a href="#" class="flex items-center justify-center h-12 px-3 py-2 text-base font-medium text-black hover:bg-blue-700 hover:text-white transition duration-300">
                            <svg  class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-megaphone-fill" viewBox="0 0 16 16">
                                <path d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0zm-1 .724c-2.067.95-4.539 1.481-7 1.656v6.237a25 25 0 0 1 1.088.085c2.053.204 4.038.668 5.912 1.56zm-8 7.841V4.934c-.68.027-1.399.043-2.008.053A2.02 2.02 0 0 0 0 7v2c0 1.106.896 1.996 1.994 2.009l.496.008a64 64 0 0 1 1.51.048m1.39 1.081q.428.032.85.078l.253 1.69a1 1 0 0 1-.983 1.187h-.548a1 1 0 0 1-.916-.599l-1.314-2.48a66 66 0 0 1 1.692.064q.491.026.966.06"/>
                            </svg>
                            View Announcement
                        </a>
                        <!--a href="rooms.php" class="flex items-center justify-center h-12 px-3 py-2 text-base font-medium text-black hover:bg-blue-700 hover:text-white transition duration-300">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                            </svg>
                            Available Rooms
                        </a-->
                        <!--a href="#" class="flex items-center justify-center h-12 px-3 py-2 text-base font-medium text-black hover:bg-blue-700 hover:text-white transition duration-300">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            View Remaining Session
                        </a-->
                        <a href="#" class="flex items-center justify-center h-12 px-3 py-2 text-base font-medium text-black hover:bg-blue-700 hover:text-white transition duration-300">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Sit-in Rules
                        </a>
                        <a href="#" class="flex items-center justify-center h-12 px-3 py-2 text-base font-medium text-black hover:bg-blue-700 hover:text-white transition duration-300">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 0a2 2 0 100-4H7a2 2 0 100 4h10zm-6 4h6m2 0a2 2 0 100-4H7a2 2 0 100 4h10z" />
                            </svg>
                            Lab Rules & Regulation
                        </a>
                        <a href="#" class="flex items-center justify-center h-12 px-3 py-2 text-base font-medium text-black hover:bg-blue-700 hover:text-white transition duration-300">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h11M9 21V3m0 0L3 10m6-7l6 7" />
                            </svg>
                            History
                        </a>
                    </div>
                
                <!-- Profile Dropdown -->
                    <div class="relative">
                        <button id="profileMenu" class="flex items-center rounded-full bg-black-200 p-1 focus:ring-1 focus:ring-black">
                            <img class="size-8  rounded-full" src="uploads/<?php echo $profile; ?>" alt="">
                        </button>
                        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-32 border border-black rounded-md bg-white shadow-lg ring-1 ring-black/5">
                            <a href="profile.php" class="block px-4 py-2 font-medium text-sm text-gray-700 hover:bg-blue-800 hover:text-white">Profile</a>
                            <a href="logout.php" class="block px-4 py-2 font-medium text-sm text-gray-700 hover:bg-blue-800 hover:text-white">Sign out</a>
                        </div>
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
</body>
</html>

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