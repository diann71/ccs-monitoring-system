<?php 
ob_start();
session_start();

include "connector.php";
include "admin_nav.php";   

$result = mysqli_query($mysql, "SELECT * FROM students ORDER BY lastname ASC");

if(isset($_POST['delete'])){
    $idno = $_POST['idno'];

    $result = "DELETE FROM students WHERE idno = ?";
    $stmt = mysqli_prepare($mysql, $result);
    mysqli_stmt_bind_param($stmt, "i", $idno);
    mysqli_stmt_execute($stmt);

    if(mysqli_stmt_execute($stmt)){
        $_SESSION['success'] = "Student deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete student: " . mysqli_error($mysql);
    }

    mysqli_stmt_close($stmt);

    header("Location: student_list.php");
    exit();

}
if(isset($_POST['submit'])){
    $search = $_POST['search'];
    $query = "SELECT * FROM students WHERE idno LIKE '%$search%' OR lastname LIKE '%$search%' OR course LIKE '%$search%'";
    $result = mysqli_query($mysql, $query);
}
if(isset($_POST['sort_id'])){
    $query = "SELECT * FROM students ORDER BY idno ASC";
    $result = mysqli_query($mysql, $query);
}
if(isset($_POST['sort_names'])){
    $query = "SELECT * FROM students ORDER BY lastname ASC";
    $result = mysqli_query($mysql, $query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="flex justify-center pt-5 pb-10">
    <div class="grid grid-rows-1 gap-4 w-full px-20">
        <div class="flex justify-end ">
            <div class="pr-16">
                <form action="" method="post" class="flex items-center space-x-4">
                    <div>
                        <button name="sort_id" class="w-26 border border-solid p-2 font-semibold">
                            <i class="fas fa-sort-numeric-down"></i> Sort by Id
                        </button>
                        <button name="sort_name" class="w-36 border border-solid p-2 font-semibold">
                            <i class="fas fa-sort-alpha-down"></i> Sort by Name
                        </button>
                    </div>
                    <div>
                        <input type="text" name="search" placeholder="Enter ID, Name, or Course" 
                            class="pl-2 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="submit" name="submit" 
                            class="p-2 bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </form> 
            </div>
        </div>
        <div class="grid grid-cols-7 text-center border-b-2 pb-2 ">
            <p class="font-bold text-center">ID</p>
            <p class="font-bold text-center">Lastname</p>
            <p class="font-bold text-center">Firstname</p>
            <p class="font-bold text-center">Midname</p>
            <p class="font-bold text-center">Course</p>
            <p class="font-bold text-center">Year</p>
            <p class="font-bold text-center">Action</p>
        </div>
        <!-- Add rows dynamically here -->

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            
            <div class="grid grid-cols-7 text-center border-b-2 pb-2">
                <p class=" text-center"><?php echo $row['idno']; ?></p>
                <p class=" text-center"><?php echo $row['lastname']; ?></p>
                <p class=" text-center"><?php echo $row['firstname']; ?></p>
                <p class=" text-center"><?php echo $row['midname']; ?></p>
                <p class=" text-center"><?php echo $row['course']; ?></p>
                <p class=" text-center"><?php echo $row['year']; ?></p>
                <form action='' method='post'>
                    <input type="hidden" name="idno" value="<?php echo $row['idno']; ?>">
                    <button type='submit' name='delete' class='w-20 bg-red-600 text-white py-1 px-3 rounded'>Delete</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>   
</div>
</body>
</html>