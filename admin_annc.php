<?php 
ob_start();
session_start();
include "connector.php";
include "admin_auth.php";
include "admin_nav.php";

if(isset($_POST["submit"])){
    $title = mysqli_real_escape_string($mysql, $_POST["title"]);
    $description = mysqli_real_escape_string($mysql, $_POST["description"]);

    if(empty($title) || empty($description)){
        $_SESSION["error"] = "All fields should be filled.";
        header("Location: admin_annc.php");
        exit();
    } else{
        $result = "INSERT INTO announcements (title, description) VALUES ('$title', '$description')";
        $row = mysqli_query($mysql, $result);

        if($row){
            $_SESSION["success"] = "Announcement added successfully!";
        } else{
            $_SESSION["error"] = "Failed to add announcement: " . mysqli_error($mysql);
        }
        header("Location: admin_annc.php");
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
</head>
<body>
    <div class="text-center">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-500 text-white p-2">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="bg-green-500 text-white p-2">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="flex justify-center pt-5 pb-10">
        <div class="w-[800px] border border-solid shadow-2xl">
            <!--div class="bg-gray-700 py-2">
                <h1 class="font-semibold text-white text-center text-xl">
                    <?php echo isset($editData) ? "Edit Announcement" : "Create Announcement"; ?>
                </h1>
            </div-->

            <div class="p-10">
                <form action="admin_annc.php" method="post">
                    <input type="hidden" name="announcement_id" value="<?php echo isset($editData) ? htmlspecialchars($editData['announcement_id']) : ''; ?>">
                    
                    <div class="pb-4">
                        <label for="title" class="block text-left text-base pb-2 font-semibold">Title</label>
                        <input type="text" name="title" class="w-full text-base px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-blue-400" 
                            value="<?php echo isset($editData) ? htmlspecialchars($editData['title']) : ''; ?>">
                    </div>
                    
                    <div class="pb-4">
                        <label for="description" class="block text-left text-base pb-2 font-semibold">Description</label>
                        <textarea name="description" class="w-full h-24 text-base px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-blue-400" style="resize: none;"><?php echo isset($editData) ? htmlspecialchars($editData['description']) : ''; ?></textarea>
                    </div>

                    <div>
                        <input type="submit" name="<?php echo isset($editData) ? 'update' : 'submit'; ?>" value="<?php echo isset($editData) ? 'Update' : 'Submit'; ?>" 
                            class="w-[150px] bg-blue-600 text-white py-2 hover:bg-blue-700 cursor-pointer mb-2">
                    </div>
                </form>
            </div>

            <div class="px-10 pb-10">
                <div class="border border-solid">
                    <div class="bg-gray-700 py-2">
                        <h1 class="font-semibold text-white text-center text-md">
                            Recent Announcements
                        </h1>
                    </div>
                    <div class='pt-5 p-10 h-96 overflow-y-auto'> 
                        <?php 
                            $result = mysqli_query($mysql, "SELECT * FROM announcements ORDER BY created_at DESC");

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<div class='p-2'>";
                                echo "<div class='border border-solid p-5'>";
                                echo "<h1 class='font-bold text-md'>" . htmlspecialchars(strtoupper($row['title'])) . "</h1>";
                                echo "<p class='text-xs'>Created " . htmlspecialchars($row['created_at']) . "</p>";
                                echo "<p class='pt-5 text-sm'>" . htmlspecialchars($row['description']) . "</p>";
                                echo "<div class='pt-2 flex gap-2'>";

                                // Edit Button
                                echo "<form action='' method='post'>";
                                echo "<input type='hidden' name='announcement_id' value='" . htmlspecialchars($row['announcement_id']) . "'>";
                                echo "<button type='submit' name='edit' class='w-16 border border-solid bg-green-600 text-white'>Edit</button>";
                                echo "</form>";

                                // Delete Button
                                echo "<form action='' method='post'>";
                                echo "<input type='hidden' name='announcement_id' value='" . htmlspecialchars($row['announcement_id']) . "'>";
                                echo "<button type='submit' name='delete' class='w-16 border border-solid bg-red-600 text-white'>Delete</button>";
                                echo "</form>";

                                echo "</div></div></div>";
                            }
                        ?>
                    </div>
                </div>         
            </div>
        </div>
    </div>
</body>
</html>
