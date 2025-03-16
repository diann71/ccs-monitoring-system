<?php 
session_start();
include "connector.php";
include "admin_nav.php";    

if(isset($_POST['submit'])){
    $search = $_POST['search'];
    $search_param = "%$search%";

    // Secure Query using Prepared Statements
    $query = "SELECT * FROM students WHERE idno LIKE ? OR lastname LIKE ? OR firstname LIKE ? OR midname LIKE ? OR course LIKE ? OR year LIKE ?";
    
    // Prepare statement
    $stmt = mysqli_prepare($mysql, $query);
    
    if (!$stmt) {
        die("Query Preparation Failed: " . mysqli_error($mysql));
    }

    // Bind Parameters
    mysqli_stmt_bind_param($stmt, "ssssss", $search_param, $search_param, $search_param, $search_param, $search_param, $search_param);

    // Execute Query 
    mysqli_stmt_execute($stmt);

    // Get Results
    $result = mysqli_stmt_get_result($stmt);

    // Check if there are results
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '
            <form action="name.php" method="post">
                <div class="grid pt-10 pb-30 h-full place-items-center ">
                    <div class=" w-1/4 h-9/10 border border-solid shadow-2xl overflow-hidden">
                        <div class="bg-blue-700 py-3">
                            <h1 class="w-full font-bold text-white text-center">Edit name</h1>
                        </div>
                        <div class="p-6">
                            <label class="block text-gray-700 font-bold pb-1">Idno</label>
                            <input type="text" name="idno" placeholder="Last Name" value="' . $row['idno'] . '" class="w-full border border-black p-2" disabled>
    
                            <label class="block text-gray-700 font-bold pb-1">Last Name</label>
                            <input type="text" name="lastname" placeholder="Last Name" value="' . $row['lastname'] . '" class="w-full border border-black p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2" disabled>
    
                            <label class="block text-gray-700 font-bold pb-1">First Name</label>
                            <input type="text" name="firstname" placeholder="First Name" value="' . $row['firstname'] . '" class="w-full border border-black p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2" disabled>
    
                            <label class="block text-gray-700 font-bold pb-1">Middle Name</label>
                            <input type="text" name="midname" placeholder="Middle Name" value="' . $row['midname'] . '" class="w-full border border-black p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2" disabled>
    
                            <label class="block text-gray-700 font-bold pb-1">Course</label>
                            <input type="text" name="course" placeholder="Course" value="' . $row['course'] . '" class="w-full border border-black p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2" disabled>
    
                            <label class="block text-gray-700 font-bold pb-1">Year</label>
                            <input type="text" name="year" placeholder="Year" value="' . $row['year'] . '" class="w-full border border-black p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2" disabled>
                            
                            <label class="block text-gray-700 font-bold pb-1">Sit-in Purpose</label>
                            <select name="sitin_purpose" class="w-full border border-black p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-10" requir>
                                <option value="Programming">Programming</option>
                                <option value="C">C</option>
                                <option value="C++">C++</option>
                            </select>

    
                            <div class="text-center">
                                <button type="submit" name="update" class="block px-4 py-2 w-full mb-5 bg-blue-600 rounded-3xl text-white text-bold hover:bg-blue-700 cursor-pointer">  
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>';
        }
    } else {
        echo "No students found.";
    }
    // Close Statement
    mysqli_stmt_close($stmt);
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
    <div class="flex items-center justify-center">
        <oiv class="border border-solid shadow-2xl w-1/4 h-48 text-center">
            <div class="mt-20">
                <form action="search.php" method="post">
                    <input type="text" class="border border-solid w-2/3 p-2" name="search" placeholder="Search">
                    <input type="submit" class="border border-solid px-3 p-2 bg-blue-600 text-white" name="submit" value="Search">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
