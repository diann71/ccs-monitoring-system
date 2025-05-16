<?php
ob_start();
session_start();
include "../database/connector.php";
include "../admin/admin_nav.php";
include "../database/authenticator.php";

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pc_id']) && isset($_POST['status'])) {
    $pc_id = $_POST['pc_id'];
    $status = $_POST['status'];
    
    // Validate status
    $valid_statuses = ['available', 'Used', 'maintenance'];
    if (in_array($status, $valid_statuses)) {
        $stmt = $mysql->prepare("UPDATE pcs SET status = ? WHERE pc_id = ?");
        $stmt->bind_param("si", $status, $pc_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Define available labs
$labs = ['524', '526', '528', '530', '542', '544', '517'];

// Get selected lab from filter
$selected_lab = isset($_GET['lab']) ? $_GET['lab'] : '';

// Build query based on filter
$query = "SELECT * FROM pcs";
if ($selected_lab) {
    $query .= " WHERE lab = '" . mysqli_real_escape_string($mysql, $selected_lab) . "'";
}
$query .= " ORDER BY pc_id ASC";

$result = mysqli_query($mysql, $query);
if (!$result) {
    die("Error fetching PCs: " . mysqli_error($mysql));
}

// Count PCs in each lab
$lab_counts = [];
foreach ($labs as $lab) {
    $count_query = "SELECT COUNT(*) as count FROM pcs WHERE lab = ?";
    $stmt = mysqli_prepare($mysql, $count_query);
    mysqli_stmt_bind_param($stmt, "s", $lab);
    mysqli_stmt_execute($stmt);
    $count_result = mysqli_stmt_get_result($stmt);
    $count_row = mysqli_fetch_assoc($count_result);
    $lab_counts[$lab] = $count_row['count'];
    mysqli_stmt_close($stmt);
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC Management - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .status-available {
            border-color: #22c55e;
            background: linear-gradient(135deg, #dcfce7 80%, #bbf7d0 100%);
        }
        .status-in_use {
            border-color: #f87171;
            background: linear-gradient(135deg, #fee2e2 80%, #fecaca 100%);
        }
        .status-maintenance {
            border-color: #fbbf24;
            background: #fef3c7;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Filter Form -->
        <div class="bg-white rounded-xl shadow-md mb-8">
            <div class="p-6">
                <form method="GET" class="flex gap-4 items-end">
                    <div class="w-64">
                        <label for="lab" class="block text-sm font-medium text-gray-700 mb-2">Filter by Laboratory</label>
                        <select name="lab" id="lab" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" onchange="this.form.submit()">
                            <?php foreach ($labs as $lab): ?>
                                <option value="<?php echo $lab; ?>" <?php echo $selected_lab === $lab ? 'selected' : ''; ?>>
                                    Lab <?php echo $lab; ?> (<?php echo $lab_counts[$lab]; ?>/50)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Lab Schedule Button -->
                    <a href="save_lab_schedule.php" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition mt-6" style="height:40px;line-height:24px;">
                        🗓️ Lab Schedule
                    </a>
                </form>
            </div>
        </div>

        <!-- PC Card Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
            <?php while ($pc = $result->fetch_assoc()): ?>
                <?php
                    $status_class = 'status-available';
                    $icon_color = 'text-green-600';
                    if ($pc['status'] === 'Used') {
                        $status_class = 'status-in_use';
                        $icon_color = 'text-red-500';
                    } elseif ($pc['status'] === 'Maintenance') {
                        $status_class = 'status-maintenance';
                        $icon_color = 'text-yellow-500';
                    }
                ?>
                <div class="border-2 rounded-xl p-4 flex flex-col items-center justify-center shadow-md bg-white <?php echo $status_class; ?> aspect-square relative">
                    <button onclick="openEditModal(<?php echo $pc['pc_id']; ?>, '<?php echo $pc['status']; ?>')" 
                            class="absolute top-2 right-2 group p-2 text-gray-500 hover:text-red-500 transition-colors duration-200">
                        <i class="fas fa-edit text-xl group-hover:rotate-12 transition-transform duration-200"></i>
                    </button>
                    <i class="fas fa-desktop <?php echo $icon_color; ?> text-3xl mb-2"></i>
                    <div class="font-bold text-base text-gray-800 mb-1"><?php echo htmlspecialchars($pc['pc_name']); ?></div>
                    <div class="text-sm text-gray-500 mb-2">Lab <?php echo htmlspecialchars($pc['lab']); ?></div>
                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold
                        <?php
                            if ($pc['status'] === 'Available') echo 'bg-green-500 text-black';
                            elseif ($pc['status'] === 'Used') echo 'bg-red-100 text-red-700';
                            else echo 'bg-yellow-100 text-yellow-700';
                        ?>
                    ">
                        <?php echo ucfirst(str_replace('_', ' ', $pc['status'])); ?>
                    </span>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-sm w-full mx-4">
            <h3 class="text-lg font-semibold mb-4">Edit PC Status</h3>
            <form method="POST" class="space-y-4" onsubmit="return handleSubmit(event)">
                <input type="hidden" name="pc_id" id="edit_pc_id">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="edit_status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="available">Available</option>
                        <option value="Used">In Use</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-sm w-full mx-4 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-3xl text-green-500"></i>
            </div>
            <h3 class="text-lg font-semibold mb-2">Success!</h3>
            <p class="text-gray-600 mb-6">PC status has been updated successfully.</p>
            <button onclick="closeSuccessModal()" class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                OK
            </button>
        </div>
    </div>

    <script>
        function openEditModal(pcId, currentStatus) {
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
            document.getElementById('edit_pc_id').value = pcId;
            document.getElementById('edit_status').value = currentStatus;
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }

        function showSuccessModal() {
            closeEditModal();
            document.getElementById('successModal').classList.remove('hidden');
            document.getElementById('successModal').classList.add('flex');
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.add('hidden');
            document.getElementById('successModal').classList.remove('flex');
            // Reload the page to show updated status
            window.location.reload();
        }

        function handleSubmit(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(() => {
                showSuccessModal();
            })
            .catch(error => {
                console.error('Error:', error);
            });

            return false;
        }

        // Close modals when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        document.getElementById('successModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeSuccessModal();
            }
        });
    </script>
</body>
</html>