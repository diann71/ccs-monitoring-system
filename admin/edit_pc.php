<?php
ob_start();
session_start();
include "../database/connector.php";
include "../admin/admin_nav.php";
include "../database/authenticator.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Get PC ID from URL
$pc_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get PC details
$stmt = mysqli_prepare($mysql, "SELECT * FROM pcs WHERE pc_id = ?");
if ($stmt === false) {
    die("Prepare failed: " . mysqli_error($mysql));
}
mysqli_stmt_bind_param($stmt, "i", $pc_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pc = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// If PC not found, redirect to PC management
if (!$pc) {
    header("Location: admin_pc_management.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pc_name = $_POST['pc_name'];
    $lab = $_POST['lab'];
    $status = $_POST['status'];

    $stmt = mysqli_prepare($mysql, "UPDATE pcs SET pc_name = ?, lab = ?, status = ? WHERE pc_id = ?");
    if ($stmt === false) {
        die("Prepare failed: " . mysqli_error($mysql));
    }
    mysqli_stmt_bind_param($stmt, "sssi", $pc_name, $lab, $status, $pc_id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: admin_pc_management.php?success=1");
        exit();
    } else {
        $error = "Error updating PC: " . mysqli_error($mysql);
    }
    mysqli_stmt_close($stmt);
}

// Get all labs for the dropdown
$labs = ['524', '526', '528', '530', '542', '544', '517'];

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit PC - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <?php include 'admin_nav.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit PC</h2>

            <?php if (isset($error)): ?>
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <form method="POST">
                        <div class="mb-4">
                            <label for="pc_name" class="block text-sm font-medium text-gray-700 mb-2">PC Name</label>
                            <input type="text" 
                                   id="pc_name" 
                                   name="pc_name" 
                                   value="<?php echo htmlspecialchars($pc['pc_name']); ?>" 
                                   required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div class="mb-4">
                            <label for="lab" class="block text-sm font-medium text-gray-700 mb-2">Lab</label>
                            <select id="lab" 
                                    name="lab" 
                                    required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <?php foreach ($labs as $lab): ?>
                                    <option value="<?php echo $lab; ?>" 
                                        <?php echo $pc['lab'] === $lab ? 'selected' : ''; ?>>
                                        Lab <?php echo $lab; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" 
                                    name="status" 
                                    required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="available" <?php echo $pc['status'] === 'available' ? 'selected' : ''; ?>>Available</option>
                                <option value="in_use" <?php echo $pc['status'] === 'in_use' ? 'selected' : ''; ?>>In Use</option>
                                <option value="maintenance" <?php echo $pc['status'] === 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                            </select>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Update PC
                            </button>
                            <a href="admin_pc_management.php" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 