<?php
include "../database/connector.php";

header('Content-Type: application/json');

if (!isset($_GET['lab'])) {
    echo json_encode([]);
    exit;
}

$lab = mysqli_real_escape_string($mysql, $_GET['lab']);

// Get all PCs for the lab, including their status
$query = "SELECT pc_id, pc_name, status FROM pcs WHERE lab = ? ORDER BY pc_id ASC";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, "s", $lab);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$pcs = [];
while ($row = mysqli_fetch_assoc($result)) {
    $pcs[] = [
        'pc_id' => $row['pc_id'],
        'pc_name' => $row['pc_name'],
        'status' => $row['status']
    ];
}

echo json_encode($pcs);
mysqli_stmt_close($stmt);
?> 