<?php 
ob_start();
session_start();

include "../database/connector.php";
include "../admin/admin_nav.php";   

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

    header("Location: ../admin/student_list.php");
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
if(isset($_POST['session_reset'])){
    $idno = $_POST['idno'];
    $reset_session = "UPDATE students set session = 30 WHERE idno = ?";
    $stmt = mysqli_prepare($mysql, $reset_session);
    mysqli_stmt_bind_param($stmt, "s", $idno);
    mysqli_stmt_execute($stmt);
}
if(isset($_POST['all_session_reset'])){
    $idno = $_POST['idno'];
    $reset_session = "UPDATE students set session = 30";
    $stmt = mysqli_prepare($mysql, $reset_session);
    mysqli_stmt_execute($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex justify-center px-4 py-8">
        <div class="w-full max-w-7xl">
            <!-- Total Students Count -->
            <div class="bg-white shadow-2xl rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Total Students</h2>
                        <p class="text-3xl font-bold text-blue-600 mt-2"><?php echo mysqli_num_rows($result); ?></p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Search and Sort Controls -->
            <div class="bg-white shadow-2xl rounded-lg p-6 mb-6">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button onclick="toggleSortDropdown()" class="text-sm flex items-center px-4 py-2 bg-white text-gray-700 hover:bg-gray-50 transition shadow border border-gray-200">
                                <span>Sort by</span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="sortDropdown" class="hidden absolute left-0 mt-2 w-28 bg-white shadow-lg z-10 border border-gray-200 py-1">
                                <form action="" method="post" class="block">
                                    <button type="submit" name="sort_id" class="w-full text-left px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                                        ID
                                    </button>
                                </form>
                                <form action="" method="post" class="block">
                                    <button type="submit" name="sort_name" class="w-full text-left px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                                        Name
                                    </button>
                                </form>
                            </div>
                        </div>
                        <form action="" method="post" class="inline">
                            <input type="hidden" name="idno" value="<?php echo htmlspecialchars($row['idno']); ?>">
                            <button type="submit" name="all_session_reset" 
                                class="text-sm flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset All
                            </button>
                        </form>
                    </div>
                    <form action="" method="post" class="flex items-center space-x-2">
                        <div class="relative">
                            <input type="text" name="search" placeholder="Search by ID, Name, or Course" 
                                class="text-sm pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-96">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <button type="submit" name="submit" 
                            class="px-4 py-2 bg-blue-600 text-sm text-white rounded-lg hover:bg-blue-700 transition">
                            Search
                        </button>
                    </form>
                </div>
            </div>

            <!-- Student List Table -->
            <div class="bg-white shadow-2xl rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <div class="grid grid-cols-5 gap-4 p-4 bg-gray-50 border-b">
                        <div class="text-xs text-center font-medium text-gray-500 uppercase tracking-wider">ID</div>
                        <div class="text-xs text-center font-medium text-gray-500 uppercase tracking-wider">Name</div>
                        <div class="text-xs text-center font-medium text-gray-500 uppercase tracking-wider">Course & Year</div>
                        <div class="text-xs text-center font-medium text-gray-500 uppercase tracking-wider">Session</div>
                        <div class="text-xs text-center font-medium text-gray-500 uppercase tracking-wider">Actions</div>
                    </div>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="grid grid-cols-5 gap-4 p-4 border-b hover:bg-gray-50 transition">
                            <div class="text-sm text-center text-gray-900 flex items-center justify-center"><?php echo htmlspecialchars($row['idno']); ?></div>
                            <div class="text-sm text-center text-gray-900 flex items-center justify-center"><?php echo htmlspecialchars($row['lastname'] . ', ' . $row['firstname'] . ' ' . $row['midname']); ?></div>
                            <div class="text-sm text-center text-gray-900 flex items-center justify-center"><?php echo htmlspecialchars($row['course'] . ' - ' . $row['year']); ?></div>
                            <div class="text-sm text-center text-gray-900 flex items-center justify-center"><?php echo htmlspecialchars($row['session']); ?></div>
                            <div class="flex justify-center space-x-2">
                                <form action="" method="post" class="inline">
                                    <input type="hidden" name="idno" value="<?php echo htmlspecialchars($row['idno']); ?>">
                                    <button type="submit" name="session_reset" 
                                        class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition text-sm">
                                        <i class="fas fa-sync-alt mr-1"></i>Reset
                                    </button>
                                </form>
                                <form action="" method="post" class="inline" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                    <input type="hidden" name="idno" value="<?php echo htmlspecialchars($row['idno']); ?>">
                                    <button type="submit" name="delete" 
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition text-sm">
                                        <i class="fas fa-trash-alt mr-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sort Dropdown functions
        function toggleSortDropdown() {
            document.getElementById('sortDropdown').classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('sortDropdown');
            const button = document.querySelector('button[onclick="toggleSortDropdown()"]');
            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
