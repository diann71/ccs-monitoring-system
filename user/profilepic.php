<?php 
ob_start();
session_start();

include "../user/nav.php";
include "../database/connector.php"; // Ensure this sets up $mysql
include "../database/authenticator.php";

$idno = $_SESSION['idno'];

// Fetch the current profile picture
$result = mysqli_query($mysql, "SELECT `profile` FROM students WHERE idno = '$idno'");
$row = mysqli_fetch_assoc($result);

$profile = $row ? $row['profile'] : 'default.png'; // Use 'default.png' if no profile exists

if(isset($_POST["submit"])){
    if(isset($_FILES["profile"]) && $_FILES["profile"]["error"] === UPLOAD_ERR_OK) {
        $target_dir = "../uploads/";  // Ensure this folder exists with write permissions
        $file_name = uniqid() . "_" . basename($_FILES["profile"]["name"]); // Unique filename
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            $_SESSION['error'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            header("Location: ../user/profilepic.php");
            exit();
        }

        // Move uploaded file
        if (move_uploaded_file($_FILES["profile"]["tmp_name"], $target_file)) {
            // Update database with new profile filename
            $updateQuery = "UPDATE students SET `profile` = '$file_name' WHERE idno = '$idno'";
            if (mysqli_query($mysql, $updateQuery)) {
                $_SESSION['success'] = "Profile picture updated successfully.";
                header("Location: ../user/profile.php");
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

    header("Location: ../user/profile.php");
    exit();
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center pt-20">
        <form action="profilepic.php" method="post" enctype="multipart/form-data" class="bg-white shadow-xl rounded-lg w-full max-w-md p-8">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Update Profile Picture</h1>
                <p class="text-gray-500 text-sm mt-2">Upload a new profile picture to personalize your account.</p>
            </div>
            <div class="flex flex-col items-center">
                <!-- Profile Image -->
                <div class="relative w-40 h-40">
                    <label for="profileInput" class="cursor-pointer">
                        <img id="profilePreview" class="w-full h-full rounded-full object-cover border-4 border-gray-500 shadow-lg" 
                            src="<?php echo file_exists("../uploads/".$profile) ? "../uploads/".$profile : "../uploads/default.png"; ?>" 
                            alt="Profile Picture">
                        <input type="file" id="profileInput" name="profile" class="hidden" accept="image/*" onchange="previewImage(event)">
                    </label>
                    <label for="profileInput" class="absolute bottom-0 right-0 bg-gray-500 text-white p-2 rounded-full shadow-md cursor-pointer hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="h-5 w-5" viewBox="0 0 16 16">
                            <path d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4z"/>
                            <path d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5m0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7M3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"/>
                        </svg>
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 mt-6">
                    <button type="submit" name="submit" 
                        class="flex items-center justify-center bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md hover:bg-blue-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Changes
                    </button>
                    <a href="profile.php" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg shadow-md hover:bg-gray-400 transition">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</body>
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("profilePreview").src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }
</script>
</html>