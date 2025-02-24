<?php
session_start();

include "connector.php";
include "authenticator.php";
include "nav.php";

$idno = $_SESSION['idno'];

$result = mysqli_query($mysql, "SELECT * FROM reservations WHERE idno='$idno' ");
$row = mysqli_fetch_assoc($result);

if($row){
    $reservation_id = $row['reservation_id'];
    $room_id = $row['room_id'];
    $idno = $row['idno'];
    $start_time = $row['start_time'];
    $end_time = $row['end_time'];
    $status = $row['status'];
    $created_at = $row['created_at'];
    $updated_at = $row['updated_at'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Reservation</title>
</head>
<body>
    <div class="grid grid-cols-8 gap-x-10 gap-y-4 p-10">
        <!-- Reservation ID -->
        <div>
            <p class="font-semibold">Reservation ID:</p>
            <p><?php echo $reservation_id?></p>
        </div>
        
        <!-- Room ID -->
        <div>
            <p class="font-semibold">Room ID:</p>
            <p><?php echo $room_id?></p>
        </div>

        <!-- Idno -->
        <div>
            <p class="font-semibold">Idno:</p>
            <p><?php echo $idno?></p>
        </div>

        <!-- Start Time -->
        <div>
            <p class="font-semibold">Start Time:</p>
            <p><?php echo $start_time?></p>
        </div>

        <!-- End Time -->
        <div>
            <p class="font-semibold">End Time:</p>
            <p><?php echo $end_time?></p>
        </div>

        <!-- Status -->
        <div>
            <p class="font-semibold">Status:</p>
            <p><?php echo $status?></p>
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