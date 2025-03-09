<?php 
ob_start();
session_start();

include "nav.php";
include "connector.php"; // Ensure this sets up $mysql
include "authenticator.php";

$idno = $_SESSION['idno'];

// Fetch the current profile picture
$result = mysqli_query($mysql, "SELECT `profile` FROM students WHERE idno = '$idno'");
$row = mysqli_fetch_assoc($result);

$profile = $row ? $row['profile'] : 'default.png'; // Use 'default.png' if no profile exists

if(isset($_POST["submit"])){
    if(isset($_FILES["profile"]) && $_FILES["profile"]["error"] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";  // Ensure this folder exists with write permissions
        $file_name = uniqid() . "_" . basename($_FILES["profile"]["name"]); // Unique filename
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            $_SESSION['error'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            header("Location: profilepic.php");
            exit();
        }

        // Move uploaded file
        if (move_uploaded_file($_FILES["profile"]["tmp_name"], $target_file)) {
            // Update database with new profile filename
            $updateQuery = "UPDATE students SET `profile` = '$file_name' WHERE idno = '$idno'";
            if (mysqli_query($mysql, $updateQuery)) {
                $_SESSION['success'] = "Profile picture updated successfully.";
                header("Location: profile.php");
                exit();
            } else {
                $_SESSION['error'] = "Database update failed.";
            }
        } else {
            $_SESSION['error'] = "Error uploading file.";
        }
    } else {
        $_SESSION['error'] = "No valid file uploaded.";
    }

    header("Location: profile.php");
    exit();
}

ob_end_flush();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="profilepic.php" method="post" enctype="multipart/form-data">
        <div class="grid place-items-center">
            <div class="mt-10 mb-10 w-1/4 h-9/10 border border-solid shadow-2xl  overflow-hidden">
                <div class="w-full bg-blue-800 py-3">
                    <h1 class="w-full font-bold text-white text-center">Profile picture</h1>
                </div>
                <div class="p-6">
                    <div class="flex flex-col items-center">
                        <!-- Profile Image -->
                        <img id="profilePreview" class="mt-8 mb-4 w-48 h-48 rounded-full object-cover" 
                            src="<?php echo file_exists("uploads/".$profile) ? "uploads/".$profile : "uploads/default.png"; ?>" 
                            alt="Profile Picture">

                        <!-- File Input -->
                        <div class="flex gap-5">
                            <label class="cursor-pointer bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 mt-4">
                                Choose photo
                                <input type="file" id="profileInput" name="profile" class="hidden" accept="image/*" onchange="previewImage(event)">
                            </label>
                            <input type="submit" name="submit" value="Save Changes" 
                                class="cursor-pointer bg-blue-600 text-white px-4 py-2 rounded-lg mt-4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</body>
</html>

<script>
    function previewImage(event) {
        const file = event.target.files[0]; // Get the selected file
        if (file) {
            const reader = new FileReader(); // Create FileReader
            reader.onload = function(e) {
                document.getElementById("profilePreview").src = e.target.result; // Update image preview
            }
            reader.readAsDataURL(file); // Read file as Data URL
        }
    }
</script>