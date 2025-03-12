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
            header("Location: admin_annc.php");
            exit();
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
    <form action="admin_annc.php" method="post">
        <div>
            <label for="title">Title</label>
            <input type="text" name="title" class="">
        </div>
        <div>
            <label for="description">DDescription</label>
            <input type="text" name="description" class="">
        </div>
        <div class="">
            <input type="submit" name="submit" value="Submit" class="">
        </div>
    </form>
</body>
</html>