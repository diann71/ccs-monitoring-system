<?php

session_start();
include "../database/connector.php";
include "../database/authenticator.php";

// Handle file upload and resource creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    // File upload handling
    $target_dir = "../uploads/resources/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_name = basename($_FILES["file"]["name"]);
    $target_file = $target_dir . time() . '_' . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if file is valid
    $allowed_types = array('pdf', 'doc', 'docx', 'txt', 'zip', 'rar');
    if (in_array($file_type, $allowed_types)) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            // Insert into database
            $query = "INSERT INTO resources (title, description, file_path, upload_date) VALUES (?, ?, ?, NOW())";
            $stmt = mysqli_prepare($mysql, $query);
            mysqli_stmt_bind_param($stmt, "sss", $title, $description, $target_file);
            
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success'] = "Resource added successfully!";
            } else {
                $_SESSION['error'] = "Error adding resource: " . mysqli_error($mysql);
            }
        } else {
            $_SESSION['error'] = "Error uploading file.";
        }
    } else {
        $_SESSION['error'] = "Invalid file type. Allowed types: PDF, DOC, DOCX, TXT, ZIP, RAR";
    }
    
    header("Location: resources.php");
    exit();
}

/* if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $delete_query = "DELETE FROM resources WHERE id = ?";
    $stmt = mysqli_prepare($mysql, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Resource deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting resource: " . mysqli_error($mysql);
    }
 } */

// Fetch existing resources
$query = "SELECT * FROM resources ORDER BY upload_date DESC";
$result = mysqli_query($mysql, $query);

// Now include the navigation after all potential redirects
include "../admin/admin_nav.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Resources - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Add Resource Form -->
            <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
                <h2 class="text-2xl font-bold mb-6">Add New Resource</h2>
                
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        <?php 
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                        ?>
                    </div>
                <?php endif; ?>

                <?php if(isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <?php 
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" enctype="multipart/form-data" class="space-y-4">
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" class="w-full px-3 py-2 border rounded" required>
                    </div>
                    
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="4" class="w-full px-3 py-2 border rounded" required></textarea>
                    </div>
                    
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">File <span class="text-red-500">*</span></label>
                        <input type="file" name="file" class="w-full px-3 py-2 border rounded" required>
                        <p class="text-sm text-gray-500 mt-1">Allowed file types: PDF, DOC, DOCX, TXT, ZIP, RAR</p>
                    </div>
                    
                    <div class="text-right">
                        <button type="submit" name="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded">
                            Add Resource
                        </button>
                    </div>
                </form>
            </div>

            <!-- Resources List -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6">Resources List</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Upload Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['title']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($row['description']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Download
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo date('M d, Y', strtotime($row['upload_date'])); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="edit_resource.php?id=<?php echo $row['id']; ?>" 
                                       class="text-yellow-600 hover:text-yellow-900 mr-3">
                                        <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <!--button onclick="confirmDelete(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars(addslashes($row['title'])); ?>')"
                                            class="text-red-600 hover:text-red-900 focus:outline-none">
                                        <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button-->
                                    <a href="delete_resource.php?id=<?php echo $row['id']; ?>" 
                                       onclick="return confirm('Are you sure you want to delete this resource?')"
                                       class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
    function confirmDelete(id, title) {
        if (confirm('Are you sure you want to delete the resource "' + title + '"?')) {
            window.location.href = 'delete_resource.php?id=' + id;
        }
    }
    </script>
</body>
</html> 