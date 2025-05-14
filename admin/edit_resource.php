<?php
session_start();
include "../database/connector.php";
include "../database/authenticator.php";

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: resources.php");
    exit();
}

$id = $_GET['id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    // If new file is uploaded
    if (!empty($_FILES['file']['name'])) {
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
                // Delete old file
                $old_file_query = "SELECT file_path FROM resources WHERE id = ?";
                $stmt = mysqli_prepare($mysql, $old_file_query);
                mysqli_stmt_bind_param($stmt, "i", $id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $old_file = mysqli_fetch_assoc($result);
                
                if ($old_file && file_exists($old_file['file_path'])) {
                    unlink($old_file['file_path']);
                }
                
                // Update with new file
                $query = "UPDATE resources SET title = ?, description = ?, file_path = ? WHERE id = ?";
                $stmt = mysqli_prepare($mysql, $query);
                mysqli_stmt_bind_param($stmt, "sssi", $title, $description, $target_file, $id);
            } else {
                $_SESSION['error'] = "Error uploading file.";
                header("Location: edit_resource.php?id=" . $id);
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid file type. Allowed types: PDF, DOC, DOCX, TXT, ZIP, RAR";
            header("Location: edit_resource.php?id=" . $id);
            exit();
        }
    } else {
        // Update without changing file
        $query = "UPDATE resources SET title = ?, description = ? WHERE id = ?";
        $stmt = mysqli_prepare($mysql, $query);
        mysqli_stmt_bind_param($stmt, "ssi", $title, $description, $id);
    }
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Resource updated successfully!";
        header("Location: resources.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating resource: " . mysqli_error($mysql);
    }
}

// Fetch resource details
$query = "SELECT * FROM resources WHERE id = ?";
$stmt = mysqli_prepare($mysql, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$resource = mysqli_fetch_assoc($result);

if (!$resource) {
    header("Location: resources.php");
    exit();
}

// Now include the navigation after all potential redirects
include "../admin/admin_nav.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resource - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6">Edit Resource</h2>
                
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
                        <input type="text" name="title" value="<?php echo htmlspecialchars($resource['title']); ?>" class="w-full px-3 py-2 border rounded" required>
                    </div>
                    
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="4" class="w-full px-3 py-2 border rounded" required><?php echo htmlspecialchars($resource['description']); ?></textarea>
                    </div>
                    
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Current File</label>
                        <div class="flex items-center space-x-2">
                            <a href="<?php echo htmlspecialchars($resource['file_path']); ?>" target="_blank" class="text-blue-600 hover:text-blue-900">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                View Current File
                            </a>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">New File (Optional)</label>
                        <input type="file" name="file" class="w-full px-3 py-2 border rounded">
                        <p class="text-sm text-gray-500 mt-1">Leave empty to keep current file. Allowed types: PDF, DOC, DOCX, TXT, ZIP, RAR</p>
                    </div>
                    
                    <div class="flex justify-end space-x-4">
                        <a href="resources.php" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-2 rounded">
                            Cancel
                        </a>
                        <button type="submit" name="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded">
                            Update Resource
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 