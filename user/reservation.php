<?php
session_start();
include "../database/connector.php";
include "../user/nav.php";
include "../database/authenticator.php";

$idno = $_SESSION['idno'];

// Check if user is logged in
if(!isset($_SESSION['idno'])) {
    $_SESSION['error'] = "Please log in to make a reservation.";
    header("Location: ../auth/login.php");
    exit();
}

// Handle form submission

$pc_query = "SELECT * FROM pcs ORDER BY pc_name ASC";
$stmt = mysqli_prepare($mysql, $pc_query);
if (!$stmt) {
    die("Prepare failed: " . mysqli_error($mysql));
}
mysqli_stmt_execute($stmt);
$result_pc = mysqli_stmt_get_result($stmt);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $idno = $_SESSION['idno'];
    $firstname = $_SESSION['firstname'];
    $lastname = $_SESSION['lastname'];
    $middlename = $_SESSION['midname'];
    $course = $_SESSION['course'];
    $year = $_SESSION['year'];
    $sit_in_purpose = $_POST['sit_in_purpose'];
    $lab = $_POST['lab'];
    $time_in = date('Y-m-d H:i:s', strtotime($_POST['date'] . ' ' . $_POST['time_in']));
    $pc_id = $_POST['pc_id'];
    $date = $_POST['date'];
    $session = $_POST['session'];

    $query = "INSERT INTO reservations (idno, firstname, lastname, middlename, course, year, sit_in_purpose, lab, time_in, pc_id, date, session, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
    $stmt = mysqli_prepare($mysql, $query);

    mysqli_stmt_bind_param($stmt, "issssissssss", $idno, $firstname, $lastname, $middlename, $course, $year, $sit_in_purpose, $lab, $time_in, $pc_id, $date, $session);
    mysqli_stmt_execute($stmt);
    
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('successModal').style.display = 'block';
            });
        </script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Reservation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 400px;
            text-align: center;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <h2 class="text-xl font-bold mb-4">Reservation Submitted!</h2>
            <p class="mb-4">Your reservation has been successfully submitted.</p>
            <button onclick="closeModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                OK
            </button>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow-2xl rounded-lg p-6">
                <h1 class="text-xl font-bold text-center mb-6 text-black">Make a Room Reservation</h1>
                
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <?php 
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="space-y-4">
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">ID Number <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border rounded" value="<?php echo htmlspecialchars($_SESSION['idno']); ?>" readonly>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border rounded" value="<?php echo htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['midname'] . ' ' . $_SESSION['lastname']); ?>" readonly>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Course<span class="text-red-500">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border rounded" value="<?php echo htmlspecialchars($_SESSION['course']); ?>" readonly>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Year<span class="text-red-500">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border rounded" value="<?php echo htmlspecialchars($_SESSION['year']); ?>" readonly>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Sit-in Purpose <span class="text-red-500">*</span></label>
                        <select name="sit_in_purpose" class="w-full px-3 py-2 border rounded" required>
                            <option value="">Select Purpose</option>
                            <option value="C Programming">C Programming</option>
                            <option value="C#">C#</option>
                            <option value="Java">Java</option>
                            <option value="PHP">PHP</option>
                            <option value="Database">Database</option>
                            <option value="Digital & Logic Design">Digital & Logic Design</option>
                            <option value="Embedded Systems & IOT">Embedded Systems & IOT</option>
                            <option value="Python Programming">Python Programming</option>
                            <option value="System Integration & Architecture">System Integration & Architecture</option>
                            <option value="Computer Application">Computer Application</option>
                            <option value="Web Design & Development">Web Design & Development</option>
                            <option value="Project Management">Project Management</option>
                        </select>
                    </div>

                    <!-- Laboratory Selection Container -->
                    <div class="bg-white border rounded-lg p-6 shadow-sm">
                        <div class="flex items-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <h2 class="text-xl font-semibold text-gray-800">Select Laboratory</h2>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            <label class="relative">
                                <input type="radio" name="lab" value="524" class="peer sr-only" required>
                                <div class="p-4 border rounded-lg cursor-pointer hover:bg-blue-50 peer-checked:bg-blue-100 peer-checked:border-blue-500 transition-all">
                                    <div class="text-center">
                                        <div class="text-lg font-semibold">Lab 524</div>
                                        <div class="text-sm text-gray-500">Computer Lab</div>
                                    </div>
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="lab" value="526" class="peer sr-only">
                                <div class="p-4 border rounded-lg cursor-pointer hover:bg-blue-50 peer-checked:bg-blue-100 peer-checked:border-blue-500 transition-all">
                                    <div class="text-center">
                                        <div class="text-lg font-semibold">Lab 526</div>
                                        <div class="text-sm text-gray-500">Computer Lab</div>
                                    </div>
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="lab" value="528" class="peer sr-only">
                                <div class="p-4 border rounded-lg cursor-pointer hover:bg-blue-50 peer-checked:bg-blue-100 peer-checked:border-blue-500 transition-all">
                                    <div class="text-center">
                                        <div class="text-lg font-semibold">Lab 528</div>
                                        <div class="text-sm text-gray-500">Computer Lab</div>
                                    </div>
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="lab" value="530" class="peer sr-only">
                                <div class="p-4 border rounded-lg cursor-pointer hover:bg-blue-50 peer-checked:bg-blue-100 peer-checked:border-blue-500 transition-all">
                                    <div class="text-center">
                                        <div class="text-lg font-semibold">Lab 530</div>
                                        <div class="text-sm text-gray-500">Computer Lab</div>
                                    </div>
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="lab" value="542" class="peer sr-only">
                                <div class="p-4 border rounded-lg cursor-pointer hover:bg-blue-50 peer-checked:bg-blue-100 peer-checked:border-blue-500 transition-all">
                                    <div class="text-center">
                                        <div class="text-lg font-semibold">Lab 542</div>
                                        <div class="text-sm text-gray-500">Computer Lab</div>
                                    </div>
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="lab" value="544" class="peer sr-only">
                                <div class="p-4 border rounded-lg cursor-pointer hover:bg-blue-50 peer-checked:bg-blue-100 peer-checked:border-blue-500 transition-all">
                                    <div class="text-center">
                                        <div class="text-lg font-semibold">Lab 544</div>
                                        <div class="text-sm text-gray-500">Computer Lab</div>
                                    </div>
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="lab" value="517" class="peer sr-only">
                                <div class="p-4 border rounded-lg cursor-pointer hover:bg-blue-50 peer-checked:bg-blue-100 peer-checked:border-blue-500 transition-all">
                                    <div class="text-center">
                                        <div class="text-lg font-semibold">Lab 517</div>
                                        <div class="text-sm text-gray-500">Computer Lab</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- PC Selection Container -->
                    <div class="bg-white border rounded-lg p-6 shadow-sm">
                        <div class="flex items-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <h2 class="text-xl font-semibold text-gray-800">Select PC</h2>
                        </div>
                        <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
                            <?php while ($pc = mysqli_fetch_assoc($result_pc)): ?>
                                <label class="relative">
                                    <input type="radio" name="pc_id" value="<?php echo $pc['pc_id']; ?>" class="peer sr-only" required>
                                    <div class="aspect-square border rounded-lg cursor-pointer hover:bg-blue-50 peer-checked:bg-blue-100 peer-checked:border-blue-500 transition-all flex items-center justify-center">
                                        <div class="text-center">
                                            <div class="text-sm font-semibold"><?php echo htmlspecialchars($pc['pc_name']); ?></div>
                                        </div>
                                    </div>
                                </label>
                            <?php endwhile; ?>
                    </div>
                </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Time In <span class="text-red-500">*</span></label>
                        <input type="time" name="time_in" class="w-full px-3 py-2 border rounded" min="08:00" max="17:00" step="1800" required>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="date" class="w-full px-3 py-2 border rounded" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Session <span class="text-red-500">*</span></label>
                        <input type="number" name="session" class="w-full px-3 py-2 border rounded" value="<?php echo htmlspecialchars($row['session']); ?>" min="30" max="180" step="30" required readonly>
                </div>
                <div class="text-center">
                    <button type="submit" name="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow">Submit Reservation</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <script>
        // Set minimum datetime to current time
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('start_time').min = now.toISOString().slice(0, 16);
        document.getElementById('end_time').min = now.toISOString().slice(0, 16);

        // Update end time minimum when start time changes
        document.getElementById('start_time').addEventListener('change', function() {
            document.getElementById('end_time').min = this.value;
        });

        // Modal functions
        function closeModal() {
            document.getElementById('successModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('successModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html> 