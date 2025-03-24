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
            $_SESSION["success"] = "Announcement added successfully!";
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
        <form action="admin_annc.php" method="post">
            <div class="w-[800px] border border-solid  shadow-2xl">
                <div class="bg-gray-700 py-2">
                    <h1 class="font-semibold text-white text-center text-xl">
                        Create Announcement
                    </h1>
                </div>
                <div class="p-10">
                    <div class="pb-4">
                        <label for="title" class="block text-left text-base pb-2 font-semibold">Title</label>
                        <input type="text" name="title" class="w-full  text-base px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div class="pb-4">
                        <label for="description" class="block text-left text-base pb-2 font-semibold">Description</label>
                        <textarea name="description" class="w-full h-24 text-base px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-blue-400" style="resize: none;"></textarea>
                    </div>
                    <div class="">
                        <input type="submit" name="submit" value="Submit" class="w-[150px] bg-blue-600 text-white py-2 hover:bg-blue-700 cursor-pointer mb-2">
                    </div>
                </div>
                <div class="px-10 pb-10">
                    <div class="border border-solid">
                        <div class="bg-gray-700 py-2">
                            <h1 class="font-semibold text-white text-center text-md">
                                Recent Announcements
                            </h1>
                        </div>
                        <div class="pt-5 p-10 h-96 overflow-y-auto"> 
                            <?php 
                                    $result = mysqli_query($mysql, "SELECT * FROM announcements ORDER BY created_at DESC ");

                                    while($row  = mysqli_fetch_assoc($result)){
                                        echo "<div class='p-2'>";
                                        echo "<div class='border border-solid p-5'>";
                                        echo "<h1 class='font-bold text-md'>" .  htmlspecialchars(strtoupper($row['title'])) . "</h1>";
                                        echo "<p class='     text-xs'>Created " . htmlspecialchars($row['created_at']) . "</p>";
                                        echo "<p class='pt-5 text-sm'>" . htmlspecialchars($row['description']) . "</p>";
                                        echo "<div class='pt-2 '>";
                                        echo "<button name='edit' class='w-16 border border-solid px-2 bg-green-600 text-white'>Edit</button>";
                                        echo "<button name='delete' class='w-16 border border-solid px-2 bg-red-600 text-white'>Delete</button>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</div>";
                                    }
                            ?>
                        </div>
                    </div>         
                </div>  
            </div>
        </form>
    </div>
</body>
</html>