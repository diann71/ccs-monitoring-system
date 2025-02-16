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
        <?php if(isset($_SESSION["success"])): ?>
            <div style="background-color: green; color: white; padding: 10px;">
                <?php
                   echo $_SESSION['success'];
                   unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>
        <div class="p-6 flex flex-row gap-2 h-screen">
            <div class="mt-10 mb-10 w-1/2 h-9/10 border-2 border-solid shadow-lg rounded-lg ">
                <div class="w-full bg-blue-800 py-3">
                    <h1 class="w-full font-bold text-white text-center">Student Information</h1>
                </div>
                <div class="p-6">
                    <p><span class="font-bold">Id No:</span> <?php echo $idno; ?></p>
                    <p><span class="font-bold">Name:</span> <?php echo $firstname . ' ' . $midname . ' ' . $lastname?></p>
                    <p><span class="font-bold">Course:</span> <?php echo $course; ?></p>
                    <p><span class="font-bold">Year:</span> <?php echo $year; ?></p>
                </div>

                
            </div>

            <div class="mt-10 mb-10 w-1/2 h-9/10 border-2 border-solid p-6 shadow-lg rounded-lg">
                
            </div>

            <div class="mt-10 mb-10 w-1/2 h-9/10 border-2 border-solid p-6 shadow-lg rounded-lg">

            </div>

        </div>
</body>
</html>