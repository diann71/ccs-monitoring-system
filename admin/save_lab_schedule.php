<?php
include "../database/connector.php";
session_start();

if (!isset($_POST['status'])) {
    header('Location: lab_schedule.php');
    exit();
}

$statusData = $_POST['status'];

foreach ($statusData as $lab => $dates) {
    foreach ($dates as $date => $slots) {
        foreach ($slots as $slot => $status) {
            $labEsc = mysqli_real_escape_string($mysql, $lab);
            $dateEsc = mysqli_real_escape_string($mysql, $date);
            $slotEsc = mysqli_real_escape_string($mysql, $slot);
            $statusEsc = mysqli_real_escape_string($mysql, $status);
            // Try to insert, or update if exists
            $sql = "INSERT INTO lab_schedule (lab, date, time_slot, status) VALUES ('$labEsc', '$dateEsc', '$slotEsc', '$statusEsc')
                    ON DUPLICATE KEY UPDATE status='$statusEsc'";
            $mysql->query($sql);
        }
    }
}

header('Location: lab_schedule.php?success=1');
exit(); 