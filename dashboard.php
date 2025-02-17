<?php session_start();

include "connector.php";
include "nav.php";
include "authenticator.php"

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
    </style>    
</head>
<body>
        <div class="p-6 flex flex-row gap-20 h-screen">
            <div class="mt-5 mb-10 w-1/3 h-1/2 border-2 border-solid shadow-lg rounded-lg ">
                <div class="w-full bg-blue-800 py-3">
                    <h1 class="w-full font-bold text-white text-center">Student Information</h1>
                </div>

                <div class="flex flex-col items-center">
                    <!-- Display Current Profile Picture -->
                    <img class="mt-8 mb-4 w-48 h-48 rounded-full border border-black object-cover" src="uploads/<?php echo $profile_picture; ?>" alt="">
                    
                    <!-- File Input (No Preview)
                    <label class="cursor-pointer bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 mt-4">
                        Choose Image
                        <input type="file" name="profile_picture" class="hidden" accept="image/*">
                    </label> -->
                </div>

                <div class="p-6">
                    <p><span class="font-bold">Id No:</span> <?php echo $idno; ?></p>
                    <p><span class="font-bold">Name:</span> <?php echo $firstname . ' ' . $midname . ' ' . $lastname?></p>
                    <p><span class="font-bold">Course:</span> <?php echo $course; ?></p>
                    <p><span class="font-bold">Year:</span> <?php echo $year; ?></p>
                </div>

                
            </div>

            <div class="mt-5 mb-10 w-1/3 h-1/2 border-2 border-solid shadow-lg rounded-lg">
                <div class="w-full bg-blue-800 py-3">
                    <h1 class="w-full font-bold text-white text-center">Announcements</h1>
                </div>
            </div>

            <div class="mt-5 mb-10 w-1/3 h-1/2 border-2 border-solid shadow-lg rounded-lg">
               <div class="w-full bg-blue-800 py-3">
                    <h1 class="w-full font-bold text-white text-center">Rules and Regulations</h1>
                </div>
            </div>

        </div>
</body>
</html>