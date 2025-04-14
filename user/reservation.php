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
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-4xl">
            <h1 class="text-2xl font-bold text-center mb-6">Reservation Details</h1>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="font-semibold">Reservation ID:</p>
                    <p class="text-gray-700"><?php echo $reservation_id ?></p>
                </div>
                <div>
                    <p class="font-semibold">Room ID:</p>
                    <p class="text-gray-700"><?php echo $room_id ?></p>
                </div>
                <div>
                    <p class="font-semibold">ID No:</p>
                    <p class="text-gray-700"><?php echo $idno ?></p>
                </div>
                <div>
                    <p class="font-semibold">Start Time:</p>
                    <p class="text-gray-700"><?php echo $start_time ?></p>
                </div>
                <div>
                    <p class="font-semibold">End Time:</p>
                    <p class="text-gray-700"><?php echo $end_time ?></p>
                </div>
                <div>
                    <p class="font-semibold">Status:</p>
                    <p class="text-gray-700"><?php echo $status ?></p>
                </div>
                <div>
                    <p class="font-semibold">Created At:</p>
                    <p class="text-gray-700"><?php echo $created_at ?></p>
                </div>
                <div>
                    <p class="font-semibold">Updated At:</p>
                    <p class="text-gray-700"><?php echo $updated_at ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>