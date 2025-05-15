<?php
require_once 'includes/notification_functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notification_id'])) {
    $notification_id = intval($_POST['notification_id']);
    
    if (markNotificationAsRead($notification_id)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to mark notification as read']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?> 