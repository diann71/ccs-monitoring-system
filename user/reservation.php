<?php
ob_start();
session_start();
include "../user/nav.php";
include "../database/connector.php";
include "../database/authenticator.php";


$idno = $_SESSION['idno'];

// Handle form submission
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
    if ($stmt === false) {
        die("Prepare failed: " . mysqli_error($mysql));
    }
    mysqli_stmt_bind_param($stmt, "issssissssss", $idno, $firstname, $lastname, $middlename, $course, $year, $sit_in_purpose, $lab, $time_in, $pc_id, $date, $session);
    mysqli_stmt_execute($stmt);
    
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        // Create notification for admin
        require_once '../includes/notification_functions.php';
        $message = "New reservation request from student ID: " . $idno;
        createNotification('reservation', $message, null, 1); // Assuming admin_id is 1
        
        $_SESSION['show_modal'] = true;
        header("Location: reservation.php");
        exit();
    }
}

// Get PC list
$pc_query = "SELECT * FROM pcs ORDER BY pc_id ASC";
$stmt = mysqli_prepare($mysql, $pc_query);
if (!$stmt) {
    die("Prepare failed: " . mysqli_error($mysql));
}
mysqli_stmt_execute($stmt);
$result_pc = mysqli_stmt_get_result($stmt);

// Get labs for dropdown
$labs = ['524', '526', '528', '530', '542', '544', '517'];
$selected_lab = isset($_GET['lab']) ? $_GET['lab'] : '';
$pcs = [];
if ($selected_lab) {
    $stmt = $mysql->prepare("SELECT * FROM pcs WHERE lab = ?");
    $stmt->bind_param("s", $selected_lab);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $pcs[] = $row;
    }
    $stmt->close();
}

// Include nav.php after all session checks and header modifications

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
    <div id="successModal" class="modal" <?php if(isset($_SESSION['show_modal'])) echo 'style="display: block;"'; ?>>
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
        <div class="max-w-7xl mx-auto">
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

                <form method="POST" action="" class="space-y-6">
                    <input type="hidden" name="lab" value="<?= htmlspecialchars($selected_lab) ?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">ID Number <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-3 py-2 border rounded" value="<?php echo htmlspecialchars($_SESSION['idno']); ?>" readonly>
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                            <input type="text"class="w-full px-3 py-2 border rounded" value="<?php echo htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['midname'] . ' ' . $_SESSION['lastname']); ?>" readonly>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Course<span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-3 py-2 border rounded" value="<?php echo htmlspecialchars($_SESSION['course']); ?>" readonly>
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Year<span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-3 py-2 border rounded" value="<?php echo htmlspecialchars($_SESSION['year']); ?>" readonly>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Laboratory <span class="text-red-500">*</span></label>
                            <select name="lab" class="w-full px-3 py-2 border rounded" required>
                                <option value="">Select Laboratory</option>
                                <?php foreach ($labs as $lab): ?>
                                    <option value="<?= $lab ?>" <?= isset($_GET['lab']) && $_GET['lab'] == $lab ? 'selected' : '' ?>><?= $lab ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">PC <span class="text-red-500">*</span></label>
                            <select name="pc_id" class="w-full px-3 py-2 border rounded" required <?= isset($_GET['lab']) ? '' : 'disabled' ?>>
                                <option value="">Select PC</option>
                                <?php foreach ($pcs as $pc): ?>
                                    <option value="<?= $pc['pc_id'] ?>">
                                        <?= htmlspecialchars($pc['pc_name']) ?> (<?= ucfirst($pc['status']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Time In <span class="text-red-500">*</span></label>
                            <input type="time" name="time_in" class="w-full px-3 py-2 border rounded" min="08:00" max="17:00" step="1800"  required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                            <input type="date" name="date" class="w-full px-3 py-2 border rounded" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Session <span class="text-red-500">*</span></label>
                            <input type="text" name="session" class="w-full px-3 py-2 border rounded" value="<?php echo htmlspecialchars($row['session']); ?>" readonly>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow">Submit Reservation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reservation History Section -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow-2xl rounded-lg p-6">
                <h2 class="text-xl font-bold mb-6 text-black">Your Reservation History</h2>
                
                <!-- Filter Section -->
                <div class="mb-6">
                    <form method="GET" class="flex items-center gap-4">
                        <div>
                            <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Status:</label>
                            <select name="status_filter" id="status_filter" onchange="this.form.submit()" class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Reservations</option>
                                <option value="pending" <?= (isset($_GET['status_filter']) && $_GET['status_filter'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= (isset($_GET['status_filter']) && $_GET['status_filter'] == 'approved') ? 'selected' : '' ?>>Approved</option>
                                <option value="rejected" <?= (isset($_GET['status_filter']) && $_GET['status_filter'] == 'rejected') ? 'selected' : '' ?>>Rejected</option>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lab</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PC</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            // Fetch user's reservation history
                            $history_query = "SELECT r.*, p.pc_name 
                                            FROM reservations r 
                                            LEFT JOIN pcs p ON r.pc_id = p.pc_id 
                                            WHERE r.idno = ?";
                            
                            // Add status filter if selected
                            if (isset($_GET['status_filter']) && !empty($_GET['status_filter'])) {
                                $history_query .= " AND r.status = ?";
                            }
                            
                            $history_query .= " ORDER BY r.time_in DESC";
                            
                            $stmt = mysqli_prepare($mysql, $history_query);
                            
                            if (isset($_GET['status_filter']) && !empty($_GET['status_filter'])) {
                                mysqli_stmt_bind_param($stmt, "ss", $idno, $_GET['status_filter']);
                            } else {
                                mysqli_stmt_bind_param($stmt, "s", $idno);
                            }
                            
                            mysqli_stmt_execute($stmt);
                            $history_result = mysqli_stmt_get_result($stmt);

                            while ($row = mysqli_fetch_assoc($history_result)) {
                                $status_class = '';
                                switch(strtolower($row['status'])) {
                                    case 'pending':
                                        $status_class = 'bg-yellow-100 text-yellow-800';
                                        break;
                                    case 'approved':
                                        $status_class = 'bg-green-100 text-green-800';
                                        break;
                                    case 'rejected':
                                        $status_class = 'bg-red-100 text-red-800';
                                        break;
                                }
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo date('M d, Y - h:i A', strtotime($row['time_in'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($row['lab']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($row['pc_name']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?php echo htmlspecialchars($row['sit_in_purpose']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($row['session']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $status_class; ?>">
                                            <?php echo ucfirst(htmlspecialchars($row['status'])); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
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
            <?php unset($_SESSION['show_modal']); ?>
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('successModal');
            if (event.target == modal) {
                modal.style.display = 'none';
                <?php unset($_SESSION['show_modal']); ?>
            }
        }
    </script>
</body>
</html> 