<?php
ob_start();
session_start();

include "../database/connector.php";
include "../admin/admin_nav.php";
include "../database/authenticator.php";

// Admin approval/rejection logic
if (isset($_POST['approve'])) {
    $reservation_id = $_POST['approve'];
    $query = "UPDATE reservations SET status = 'Approved' WHERE reservation_id = ?";
    $stmt = mysqli_prepare($mysql, $query);
    mysqli_stmt_bind_param($stmt, 'i', $reservation_id);
    mysqli_stmt_execute($stmt);
    header("Location: admin_reservations.php"); // Redirect after action
}

if (isset($_POST['reject'])) {
    $reservation_id = $_POST['reject'];
    $query = "UPDATE reservations SET status = 'Rejected' WHERE reservation_id = ?";
    $stmt = mysqli_prepare($mysql, $query);
    mysqli_stmt_bind_param($stmt, 'i', $reservation_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: admin_reservations.php"); // Redirect after action
}

// Fetch reservations
$query = "SELECT * FROM reservations "; // You can change the sorting logic here as needed
$result = mysqli_query($mysql, $query);

if (!$result) {
    die('Error fetching reservations: ' . mysqli_error($mysql));
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Reservations</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-2xl font-bold mb-8 text-center text-blue-700">Manage Reservations</h1>
            <div class="space-y-6">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex-1 grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
                        <div class="font-semibold text-gray-600">Reservation ID:</div>
                        <div class="text-gray-900"><?= htmlspecialchars($row['reservation_id']) ?></div>
                        <div class="font-semibold text-gray-600">Student Name:</div>
                        <div class="text-gray-900"><?= htmlspecialchars($row['lastname'] . ', ' . $row['firstname']) ?></div>
                        <div class="font-semibold text-gray-600">Purpose:</div>
                        <div class="text-gray-900"><?= htmlspecialchars($row['sitin_purpose']) ?></div>
                        <div class="font-semibold text-gray-600">Lab:</div>
                        <div class="text-gray-900"><?= htmlspecialchars($row['lab']) ?></div>
                        <div class="font-semibold text-gray-600">PC:</div>
                        <div class="text-gray-900"><?= htmlspecialchars($row['pc_id']) ?></div>
                        <div class="font-semibold text-gray-600">Date:</div>
                        <div class="text-gray-900"><?= htmlspecialchars($row['date']) ?></div>
                        <div class="font-semibold text-gray-600">Time In:</div>
                        <div class="text-gray-900"><?= htmlspecialchars($row['time_in']) ?></div>
                        <div class="font-semibold text-gray-600">Status:</div>
                        <div class="text-gray-900">
                            <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                <?php
                                    if ($row['status'] === 'Approved') echo 'bg-green-100 text-green-700';
                                    elseif ($row['status'] === 'Rejected') echo 'bg-red-100 text-red-700';
                                    else echo 'bg-yellow-100 text-yellow-700';
                                ?>
                            ">
                                <?= htmlspecialchars($row['status']) ?>
                            </span>
                        </div>
                    </div>
                    <form method="post" class="flex flex-col md:flex-row gap-2 md:ml-6">
                        <button type="submit" name="approve" value="<?= $row['reservation_id'] ?>"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition font-medium shadow">
                            Approve
                        </button>
                        <button type="submit" name="reject" value="<?= $row['reservation_id'] ?>"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition font-medium shadow">
                            Reject
                        </button>
                    </form>
                </div>
            <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>
