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
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <form action="rooms.php" method="post" class="bg-white shadow-lg rounded-lg p-8 w-full max-w-2xl">
            <h1 class="text-2xl font-bold text-center mb-6">Room Reservation</h1>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="font-semibold">Room ID:</p>
                    <p class="text-gray-700"><?php echo $room_id ?></p>
                </div>
                <div>
                    <p class="font-semibold">Room Name:</p>
                    <p class="text-gray-700"><?php echo $room_name ?></p>
                </div>
                <div>
                    <p class="font-semibold">Capacity:</p>
                    <p class="text-gray-700"><?php echo $capacity ?></p>
                </div>
                <div>
                    <p class="font-semibold">Status:</p>
                    <p class="text-gray-700"><?php echo $status ?></p>
                </div>
                <div>
                    <p class="font-semibold">Created At:</p>
                    <p class="text-gray-700"><?php echo $created_at ?></p>
                </div>
            </div>
            <button type="submit" name="submit" 
                class="mt-6 w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Reserve</button>
        </form>
    </div>
</body>
</html>