<?php
session_start();
include "connector.php";
include "nav.php";


$result = mysqli_query($mysql, "SELECT * FROM rooms");
$row = mysqli_fetch_assoc($result);

if($row){
    $room_id = $row['room_id'];
    $room_name = $row['room_name'];
    $capacity = $row['capacity'];
    $status = $row['status'];
    $created_at = $row['created_at'];
}

if(isset($_POST['submit'])){
    $room_id = $_POST['room_id'];

    $result = mysqli_query($mysql, "INSERT INTO reservations (room_id) VALUES ('$room_id') ");
    $row = mysqli_fetch_assoc($result);

    if($row){
        echo "Done reserved";
    }


}
/*$idno = $_SESSION["idno"];
$result = mysqli_query($mysql, "SELECT =r FROM students WHERE idno = '$idno'");
$row = mysqli_fetch_assoc($result);

if ($row['session'] > 0) {
    $query = "UPDATE students SET session = session - 1 WHERE idno = '$idno' AND session > 0 ";
    mysqli_query($mysql, $query);
    echo "Session deducted successfully!";
} else {
    echo "No remaining sessions!";
}*/
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
    <form action="rooms.php" method="post">
        <div class="grid grid-cols-8 gap-x-10 gap-y-4 p-10">
            <!-- Reservation ID -->
            <div>
                <p class="font-semibold">Room ID:</p>
                <p><?php echo $room_id?></p>
            </div>
            
            <!-- Room ID -->
            <div>
                <p class="font-semibold">Room Name:</p>
                <p><?php echo $room_name?></p>
            </div>

            <!-- Idno -->
            <div>
                <p class="font-semibold">Capacity:</p>
                <p><?php echo $capacity?></p>
            </div>

            <!-- Start Time -->
            <div>
                <p class="font-semibold">Status:</p>
                <p><?php echo $status?></p>
            </div>

            <!-- End Time -->
            <div>
                <p class="font-semibold">Created at:</p>
                <p><?php echo $created_at?></p>
            </div>

            <button type="submit">Reserve</button>
        </div>
    </form>
</body>
</html>