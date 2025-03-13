<?php 
session_start();

include "connector.php";
include "admin_nav.php";

if(isset($_POST['submit'])){
    $result = "SELECT * FROM students WHERE idno LIKE "
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
</head>
<body>
    <form action="search.php" method="post">
        <div>
            <label>Search</label>
            <input type="text" name="search">
            <input type="submit" name="submit" value="submit">
        </div>
    </form>
</body>
</html>