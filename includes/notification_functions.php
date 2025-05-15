<?php
require_once "../database/connector.php";

function createNotification($type, $message, $user_id = null, $admin_id = null) {
    global $mysql;
    $stmt = $mysql->prepare("INSERT INTO notifications (type, message, user_id, admin_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $type, $message, $user_id, $admin_id);
    return $stmt->execute();
}

function getUnreadNotifications($user_id = null, $admin_id = null) {
    global $mysql;
    $query = "SELECT * FROM notifications WHERE is_read = 0";
    
    if ($user_id) {
        $query .= " AND user_id = ?";
        $query .= " ORDER BY created_at DESC";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("i", $user_id);
    } elseif ($admin_id) {
        $query .= " AND admin_id = ?";
        $query .= " ORDER BY created_at DESC";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("i", $admin_id);
    } else {
        $query .= " ORDER BY created_at DESC";
        $stmt = $mysql->prepare($query);
    }
    
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function markNotificationAsRead($notification_id) {
    global $mysql;
    $stmt = $mysql->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
    $stmt->bind_param("i", $notification_id);
    return $stmt->execute();
}

function getNotificationCount($user_id = null, $admin_id = null) {
    global $mysql;
    $query = "SELECT COUNT(*) as count FROM notifications WHERE is_read = 0";
    
    if ($user_id) {
        $query .= " AND user_id = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("i", $user_id);
    } elseif ($admin_id) {
        $query .= " AND admin_id = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("i", $admin_id);
    } else {
        $stmt = $mysql->prepare($query);
    }
    
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['count'];
}
?> 