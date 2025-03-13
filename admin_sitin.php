<?php 
session_start();
include "authenticator.php";
include "connector.php";
include "admin_nav.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div class="grid grid-cols-8 gap-x-10 gap-y-4 p-10">
        <!-- Reservation ID -->
        <div>
            <p class="font-semibold">Reservation ID:</p>
            <p><?php echo $idno?></p>
        </div>
        
        <!-- Room ID -->
        <div>
            <p class="font-semibold">Room ID:</p>
            <p><?php echo $lastname?></p>
        </div>

        <!-- Idno -->
        <div>
            <p class="font-semibold">Idno:</p>
            <p><?php echo $firstname?></p>
        </div>

        <!-- Start Time -->
        <div>
            <p class="font-semibold">Start Time:</p>
            <p><?php echo $midname?></p>
        </div>

        <!-- End Time -->
        <div>
            <p class="font-semibold">End Time:</p>
            <p><?php echo $course?></p>
        </div>

        <!-- Status -->
        <div>
            <p class="font-semibold">Status:</p>
            <p><?php echo $year?></p>
        </div>

        <!-- Created At -->
        <div>
            <p class="font-semibold">Created at:</p>
            <p><?php echo $created_at?></p>
        </div>

        <!-- Updated At -->
        <div>
            <p class="font-semibold">Updated at:</p>
            <p><?php echo $updated_at?></p>
        </div>
    </div>
</body>
</html>