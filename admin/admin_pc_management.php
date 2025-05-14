<?php
ob_start();
session_start();
include "../database/connector.php";
include "../admin/admin_nav.php";
include "../database/authenticator.php";

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
            background: linear-gradient(135deg, #fef3c7 80%, #fde68a 100%);
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
                            <option value="">All Laboratories</option>
                            <?php foreach ($labs as $lab): ?>
                                <option value="<?php echo $lab; ?>" <?php echo $selected_lab === $lab ? 'selected' : ''; ?>>
                                    Lab <?php echo $lab; ?> (<?php echo $lab_counts[$lab]; ?>/50)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- PC Card Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            <?php while ($pc = $result->fetch_assoc()): ?>
                <?php
                    $status_class = 'status-available';
                    $icon_color = 'text-green-600';
                    if ($pc['status'] === 'in_use') {
                        $status_class = 'status-in_use';
                        $icon_color = 'text-red-500';
                    } elseif ($pc['status'] === 'maintenance') {
                        $status_class = 'status-maintenance';
                        $icon_color = 'text-yellow-500';
                    }
                ?>
                <div class="border-2 rounded-xl p-6 flex flex-col items-center justify-center shadow-md bg-white <?php echo $status_class; ?>">
                    <i class="fas fa-desktop <?php echo $icon_color; ?> text-4xl mb-3"></i>
                    <div class="font-bold text-lg text-gray-800 mb-1"><?php echo htmlspecialchars($pc['pc_name']); ?></div>
                    <div class="text-sm text-gray-500 mb-2">Lab <?php echo htmlspecialchars($pc['lab']); ?></div>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                        <?php
                            if ($pc['status'] === 'available') echo 'bg-green-100 text-green-700';
                            elseif ($pc['status'] === 'in_use') echo 'bg-red-100 text-red-700';
                            else echo 'bg-yellow-100 text-yellow-700';
                        ?>
                    ">
                        <?php echo ucfirst(str_replace('_', ' ', $pc['status'])); ?>
                    </span>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>