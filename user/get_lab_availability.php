<?php
include "../database/connector.php";
header('Content-Type: application/json');

// Define labs (replace with DB query if needed)
$labs = ['524', '526', '528', '530', '542', '544', '517'];

$date = isset($_REQUEST['date']) ? $_REQUEST['date'] : null;
$time_slot = isset($_REQUEST['time_slot']) ? $_REQUEST['time_slot'] : null;

if (!$date || !$time_slot) {
    echo json_encode(["error" => "Missing date or time_slot"]);
    exit;
}

// Validate date format
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    echo json_encode(["error" => "Invalid date format"]);
    exit;
}

// Validate time slot format
$valid_slots = [
    "08:00-09:00", "09:00-10:00", "10:00-11:00", "11:00-12:00",
    "12:00-13:00", "13:00-14:00", "14:00-15:00", "15:00-16:00",
    "16:00-17:00", "17:00-18:00"
];
if (!in_array($time_slot, $valid_slots)) {
    echo json_encode(["error" => "Invalid time slot"]);
    exit;
}

// Check if date is in the past
if (strtotime($date) < strtotime(date('Y-m-d'))) {
    echo json_encode(["error" => "Cannot reserve for past dates"]);
    exit;
}

// Check if date is too far in the future (e.g., 30 days)
if (strtotime($date) > strtotime('+30 days')) {
    echo json_encode(["error" => "Cannot reserve more than 30 days in advance"]);
    exit;
}

$availability = [];
foreach ($labs as $lab) {
    $labEsc = mysqli_real_escape_string($mysql, $lab);
    $dateEsc = mysqli_real_escape_string($mysql, $date);
    $slotEsc = mysqli_real_escape_string($mysql, $time_slot);
    
    try {
        // Use prepared statement for security
        $sql = "SELECT status FROM lab_schedule WHERE lab=? AND date=? AND time_slot=? LIMIT 1";
        $stmt = mysqli_prepare($mysql, $sql);
        if (!$stmt) {
            throw new Exception("Database query failed");
        }

        mysqli_stmt_bind_param($stmt, "sss", $labEsc, $dateEsc, $slotEsc);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (!$result) {
            throw new Exception("Database query failed");
        }
        
        // If no schedule entry exists, lab is considered available by default
        if ($result->num_rows === 0) {
            $status = 'available';
        } else {
            $row = $result->fetch_assoc();
            $status = strtolower($row['status']);
            
            // Double check the status is valid
            if (!in_array($status, ['available', 'unavailable'])) {
                $status = 'available';
            }
        }

        // Additional check for existing reservations
        $reservation_sql = "SELECT COUNT(*) as count FROM reservations 
                          WHERE lab=? AND date=? AND time_slot=? 
                          AND status IN ('pending', 'approved')";
        $res_stmt = mysqli_prepare($mysql, $reservation_sql);
        if ($res_stmt) {
            mysqli_stmt_bind_param($res_stmt, "sss", $labEsc, $dateEsc, $slotEsc);
            mysqli_stmt_execute($res_stmt);
            $res_result = mysqli_stmt_get_result($res_stmt);
            if ($res_result && $row = $res_result->fetch_assoc()) {
                if ($row['count'] > 0) {
                    $status = 'unavailable';
                }
            }
        }
    } catch (Exception $e) {
        // If any error occurs, mark lab as unavailable
        $status = 'unavailable';
    }
    
    $availability[] = ["lab" => $lab, "status" => $status];
}

echo json_encode($availability); 