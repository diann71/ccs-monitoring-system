<?php 
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
            $_SESSION["error"] = "Announcement added successfully!";
        } else{
            $_SESSION["error"] = "Failed to add announcement: " . mysqli_error($mysql);
            header("Location: admin_annc.php");
            exit();
        }
    }
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
    <div class="flex justify-center pt-10 pb-10">
        <form action="admin_annc.php" method="post">
            <div class="w-[800px] p-20 border border-solid  shadow-2xl">
                <div class="pb-4">
                    <label for="title" class="block text-left text-base pb-2 font-semibold">Title</label>
                    <input type="text" name="title" class="w-full  text-base px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div class="pb-4">
                    <label for="description" class="block text-left text-base pb-2 font-semibold">Description</label>
                    <textarea name="description" class="w-full h-96 text-base px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-blue-400" style="resize: none;"></textarea>
                </div>
                <div class="">
                    <input type="submit" name="submit" value="Submit" class="w-[150px] bg-blue-600 text-white py-2 hover:bg-blue-700 cursor-pointer mb-2">
                </div>
            </div>
        </form>
    </div>
</body>
</html>