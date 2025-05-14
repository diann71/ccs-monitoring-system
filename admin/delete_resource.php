<?php
session_start();
include "../database/connector.php";
include "../database/authenticator.php";

// Check if ID is provided
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "No resource ID provided.";
    header("Location: resources.php");
    exit();
}

$id = $_GET['id'];

// First get the file path
$query = "SELECT file_path FROM resources WHERE id = ?";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$resource = mysqli_fetch_assoc($result);

if ($resource) {
    // Delete the file if it exists
    if (file_exists($resource['file_path'])) {
        unlink($resource['file_path']);
    }
    
    // Delete from database
    $delete_query = "DELETE FROM resources WHERE id = ?";
    $stmt = mysqli_prepare($mysql, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Resource deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting resource: " . mysqli_error($mysql);
    }
} else {
    $_SESSION['error'] = "Resource not found.";
}

header("Location: resources.php");
exit();