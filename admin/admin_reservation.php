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
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['approval_success'] = true;
    }
    
    mysqli_stmt_close($stmt);
    header("Location: admin_reservation.php");
    exit();
}

if (isset($_POST['reject'])) {
    $reservation_id = $_POST['reject'];
    $query = "UPDATE reservations SET status = 'Rejected' WHERE reservation_id = ?";
    $stmt = mysqli_prepare($mysql, $query);
    mysqli_stmt_bind_param($stmt, 'i', $reservation_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['reject_success'] = true;
    }
    
    mysqli_stmt_close($stmt);
    header("Location: admin_reservation.php");
    exit();
}

// Fetch reservations
$query = "SELECT * FROM reservations WHERE status = 'pending'"; // You can change the sorting logic here as needed
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Current Reservations</h1>
        
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course & Year</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lab</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= htmlspecialchars($row['reservation_id']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($row['lastname'] . ', ' . $row['firstname']) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($row['course']) ?> - <?= htmlspecialchars($row['year']) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <?= htmlspecialchars($row['sit_in_purpose']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($row['lab']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('M d, Y - h:i A', strtotime($row['time_in'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    <?php echo $row['status'] === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 
                                        ($row['status'] === 'Approved' ? 'bg-green-100 text-green-800' : 
                                        'bg-red-100 text-red-800'); ?>">
                                    <?= htmlspecialchars($row['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form method="post" class="inline-flex gap-2">
                                    <button type="submit" name="approve" value="<?= $row['reservation_id'] ?>"
                                        class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="submit" name="reject" value="<?= $row['reservation_id'] ?>"
                                        class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Reservation History Section -->
        <h1 class="text-2xl font-bold text-gray-800 mb-6 mt-12">Reservation History</h1>
        
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course & Year</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lab</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php 
                    // Fetch all reservations except pending ones
                    $history_query = "SELECT * FROM reservations WHERE status != 'pending' ORDER BY time_in DESC";
                    $history_result = mysqli_query($mysql, $history_query);
                    
                    while ($row = mysqli_fetch_assoc($history_result)) { ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= htmlspecialchars($row['reservation_id']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($row['lastname'] . ', ' . $row['firstname']) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($row['course']) ?> - <?= htmlspecialchars($row['year']) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <?= htmlspecialchars($row['sit_in_purpose']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($row['lab']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('M d, Y - h:i A', strtotime($row['time_in'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    <?php echo $row['status'] === 'Approved' ? 'bg-green-100 text-green-800' : 
                                        'bg-red-100 text-red-800'; ?>">
                                    <?= htmlspecialchars($row['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Improved Success Modal -->
    <div id="successModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100">
                    <i class="fas fa-check-circle text-2xl text-emerald-500"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-5">Success!</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">The reservation has been approved successfully.</p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="closeModal"
                            class="button-hover px-4 py-2 bg-emerald-500 text-white text-base font-medium rounded-lg
                                   shadow-sm hover:bg-emerald-600 focus:outline-none focus:ring-4 focus:ring-emerald-200">
                        Continue
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-times-circle text-2xl text-red-500"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-5">Reservation Rejected</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">The reservation has been rejected successfully.</p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="closeRejectModal"
                            class="button-hover px-4 py-2 bg-red-500 text-white text-base font-medium rounded-lg
                                   shadow-sm hover:bg-red-600 focus:outline-none focus:ring-4 focus:ring-red-200">
                        Continue
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this before closing body tag -->
    <script>
        // Show modal function
        function showModal() {
            document.getElementById('successModal').classList.remove('hidden');
        }

        // Hide modal function
        function hideModal() {
            document.getElementById('successModal').classList.add('hidden');
            window.location.href = 'admin_reservation.php';
        }

        // Close modal when clicking the OK button
        document.getElementById('closeModal').addEventListener('click', hideModal);

        // Close modal when clicking outside
        document.getElementById('successModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideModal();
            }
        });

        <?php if (isset($_SESSION['approval_success'])): ?>
            showModal();
            <?php unset($_SESSION['approval_success']); ?>
        <?php endif; ?>

        // Show reject modal function
        function showRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        // Hide reject modal function
        function hideRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            window.location.href = 'admin_reservation.php';
        }

        // Close reject modal when clicking the OK button
        document.getElementById('closeRejectModal').addEventListener('click', hideRejectModal);

        // Close reject modal when clicking outside
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideRejectModal();
            }
        });

        <?php if (isset($_SESSION['reject_success'])): ?>
            showRejectModal();
            <?php unset($_SESSION['reject_success']); ?>
        <?php endif; ?>
    </script>
</body>
</html>
