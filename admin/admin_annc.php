<?php 
ob_start();
session_start();
include "../database/connector.php";
include "../database/admin_auth.php";
include "../admin/admin_nav.php";

if(isset($_POST["submit"])){
    $title = mysqli_real_escape_string($mysql, $_POST["title"]);
    $description = mysqli_real_escape_string($mysql, $_POST["description"]);

    if(empty($title) || empty($description)){
        $_SESSION["error"] = "All fields should be filled.";
        header("Location: ../admin/admin_annc.php");
        exit();
    } else{
        $result = "INSERT INTO announcements (title, description) VALUES ('$title', '$description')";
        $row = mysqli_query($mysql, $result);

        if($row){
            $_SESSION["success"] = "Announcement added successfully!";
        } else{
            $_SESSION["error"] = "Failed to add announcement: " . mysqli_error($mysql);
        }
        header("Location: ../admin/admin_annc.php");
        exit();
    }
}

// DELETE ANNOUNCEMENT
if (isset($_POST['delete'])) {
    $id = mysqli_real_escape_string($mysql, $_POST['announcement_id']);
    $delete_query = "DELETE FROM announcements WHERE announcement_id = '$id'";

    if (mysqli_query($mysql, $delete_query)) {
        $_SESSION['success'] = "Announcement deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete announcement: " . mysqli_error($mysql);
    }

    header("Location: admin_annc.php");
    exit();
}

// FETCH ANNOUNCEMENT TO EDIT
$editData = null;
if (isset($_POST['edit'])) {
    $id = mysqli_real_escape_string($mysql, $_POST['announcement_id']);
    $fetch_query = "SELECT * FROM announcements WHERE announcement_id='$id'";
    $result = mysqli_query($mysql, $fetch_query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $editData = mysqli_fetch_assoc($result);
    }
}

// UPDATE ANNOUNCEMENT
if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($mysql, $_POST['announcement_id']);
    $title = mysqli_real_escape_string($mysql, $_POST["title"]);
    $description = mysqli_real_escape_string($mysql, $_POST["description"]);

    if (empty($title) || empty($description)) {
        $_SESSION['error'] = "All fields should be filled.";
    } else {
        $update_query = "UPDATE announcements SET title='$title', description='$description' WHERE announcement_id='$id'";
        $row = mysqli_query($mysql, $update_query);
       
        if ($row) {
            $_SESSION['success'] = "Edited Successfully";
        } else {
            $_SESSION['error'] = "Failed to update announcement: " . mysqli_error($mysql);
        }
    }
    header("Location: admin_annc.php"); 
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Success/Error Messages -->
            <div class="mb-6">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Create/Edit Announcement Form -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-xl font-bold mb-4"><?php echo isset($editData) ? "Edit Announcement" : "Create New Announcement"; ?></h2>
                <form action="admin_annc.php" method="post" class="space-y-4">
                    <input type="hidden" name="announcement_id" value="<?php echo isset($editData) ? htmlspecialchars($editData['announcement_id']) : ''; ?>">
                    
                    <div>
                        <label for="title" class="block text-base font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" id="title" 
                            class="text-base w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            value="<?php echo isset($editData) ? htmlspecialchars($editData['title']) : ''; ?>"
                            placeholder="Enter announcement title">
                    </div>
                    
                    <div>
                        <label for="description" class="text-base block font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="text-base w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter announcement description"><?php echo isset($editData) ? htmlspecialchars($editData['description']) : ''; ?></textarea>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="submit" name="<?php echo isset($editData) ? 'update' : 'submit'; ?>" 
                            class="text-sm px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <?php if (isset($editData)): ?>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                <?php else: ?>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                <?php endif; ?>
                            </svg>
                            <?php echo isset($editData) ? 'Update Announcement' : 'Create Announcement'; ?>
                        </button>
                        <?php if (isset($editData)): ?>
                            <a href="admin_annc.php" class="text-sm px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Cancel
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Recent Announcements -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Recent Announcements</h2>
                <div class="space-y-4 max-h-[500px] overflow-y-auto">
                    <?php 
                    $result = mysqli_query($mysql, "SELECT * FROM announcements ORDER BY created_at DESC");
                    if ($result && mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)):
                    ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-500">Created at <?php echo date('M d, Y - h:i A', strtotime($row['created_at'])); ?></span>
                                <div class="flex space-x-2">
                                    <form action="" method="post" class="inline">
                                        <input type="hidden" name="announcement_id" value="<?php echo htmlspecialchars($row['announcement_id']); ?>">
                                        <button type="submit" name="edit" 
                                            class="text-base text-blue-700 rounded-md">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </form>
                                    <form action="" method="post" class="inline" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                        <input type="hidden" name="announcement_id" value="<?php echo htmlspecialchars($row['announcement_id']); ?>">
                                        <button type="submit" name="delete" 
                                            class="text-base text-red-700 rounded-md">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <h3 class="text-base font-medium text-gray-900 mb-2"><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p class="text-sm   bg-gray-50 text-gray-600 mb-4 border p-2"><?php echo htmlspecialchars($row['description']); ?></p>
                        </div>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <p class="text-gray-500 text-center py-4">No announcements found</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
