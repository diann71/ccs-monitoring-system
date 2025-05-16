<?php
// ob_start();
session_start();
// include "../user/nav.php";
include "../database/connector.php";
include "../database/authenticator.php";


$idno = $_SESSION['idno'];

// Server-side lab schedule check
function getTimeSlotString($time) {
    // Normalize to HH:MM
    $t = date('H:i', strtotime($time));
    $slots = [
        "08:00-09:00", "09:00-10:00", "10:00-11:00", "11:00-12:00",
        "12:00-13:00", "13:00-14:00", "14:00-15:00", "15:00-16:00",
        "16:00-17:00", "17:00-18:00"
    ];
    foreach ($slots as $slot) {
        if (strpos($slot, $t) === 0) return $slot;
    }
    return null;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // First, validate all required fields
    $required_fields = ['lab', 'date', 'time_in', 'pc_id', 'sit_in_purpose'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $_SESSION['error'] = 'All fields are required.';
            header('Location: reservation.php');
            exit();
        }
    }

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
    $session = $_SESSION['session'];

    // STRICT LAB AVAILABILITY CHECK
    $labEsc = mysqli_real_escape_string($mysql, $lab);
    $dateEsc = mysqli_real_escape_string($mysql, $date);
    $slotString = getTimeSlotString($_POST['time_in']);
    
    if (!$slotString) {
        $_SESSION['error'] = 'Invalid time slot selected.';
        header('Location: reservation.php');
        exit();
    }

    $slotEsc = mysqli_real_escape_string($mysql, $slotString);
    
    // Check if the lab exists in our valid labs list
    $valid_labs = ['524', '526', '528', '530', '542', '544', '517'];
    if (!in_array($lab, $valid_labs)) {
        $_SESSION['error'] = 'Invalid laboratory selected.';
        header('Location: reservation.php');
        exit();
    }

    // Check if date is valid and not in the past
    if (strtotime($date) < strtotime(date('Y-m-d'))) {
        $_SESSION['error'] = 'Cannot make reservations for past dates.';
        header('Location: reservation.php');
        exit();
    }

    // Check lab availability
    $sql = "SELECT status FROM lab_schedule WHERE lab=? AND date=? AND time_slot=? LIMIT 1";
    $stmt = mysqli_prepare($mysql, $sql);
    if (!$stmt) {
        $_SESSION['error'] = 'System error. Please try again.';
        header('Location: reservation.php');
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $labEsc, $dateEsc, $slotEsc);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // If no schedule entry exists or lab is unavailable, reject
    if (!$result || $result->num_rows === 0) {
        $_SESSION['error'] = 'This lab is not available for the selected date and time.';
        header('Location: reservation.php');
        exit();
    }

    $row = $result->fetch_assoc();
    if (strtolower($row['status']) !== 'available') {
        $_SESSION['error'] = 'This lab is unavailable for the selected date and time.';
        header('Location: reservation.php');
        exit();
    }

    // If we get here, the lab is available, proceed with reservation
    $query = "INSERT INTO reservations (idno, firstname, lastname, middlename, course, year, sit_in_purpose, lab, time_in, pc_id, date, session, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
    $stmt = mysqli_prepare($mysql, $query);
    if ($stmt === false) {
        $_SESSION['error'] = 'System error. Please try again.';
        header('Location: reservation.php');
        exit();
    }

    mysqli_stmt_bind_param($stmt, "issssissssss", $idno, $firstname, $lastname, $middlename, $course, $year, $sit_in_purpose, $lab, $time_in, $pc_id, $date, $session);
    
    if (!mysqli_stmt_execute($stmt)) {
        $_SESSION['error'] = 'Failed to create reservation. Please try again.';
        header('Location: reservation.php');
        exit();
    }

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        require_once '../includes/notification_functions.php';
        $message = "New reservation request from student ID: " . $idno;
        createNotification('reservation', $message, null, 1);
        $_SESSION['show_modal'] = true;
        header("Location: reservation.php");
        exit();
    } else {
        $_SESSION['error'] = 'Failed to create reservation. Please try again.';
        header('Location: reservation.php');
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

// After all PHP logic and before HTML output:
include "../user/nav.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Reservation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function handleLabChange() {
            const labSelect = document.getElementById('lab');
            const pcSelect = document.getElementById('pc_id');
            const selectedLab = labSelect.value;

            // Reset and disable PC select
            pcSelect.innerHTML = '<option value="">Select PC</option>';
            pcSelect.disabled = !selectedLab;

            if (selectedLab) {
                // Show loading state
                pcSelect.innerHTML = '<option value="">Loading PCs...</option>';
                
                // Fetch PCs for selected lab
                fetch(`get_pcs.php?lab=${selectedLab}`)
                    .then(response => response.json())
                    .then(pcs => {
                        pcSelect.innerHTML = '<option value="">Select PC</option>';
                        if (pcs.length === 0) {
                            pcSelect.innerHTML = '<option value="">No PCs available</option>';
                        } else {
                            pcs.forEach(pc => {
                                const option = document.createElement('option');
                                option.value = pc.pc_id;
                                option.textContent = `${pc.pc_name} (${pc.status})`;
                                
                                // If PC is in use, make it unselectable and gray it out
                                if (pc.status === 'Used') {
                                    option.disabled = true;
                                    option.style.color = '#999';
                                    option.style.backgroundColor = '#f3f4f6';
                                    option.style.cursor = 'not-allowed';
                                }
                                
                                pcSelect.appendChild(option);
                            });
                        }
                        pcSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error loading PCs:', error);
                        pcSelect.innerHTML = '<option value="">Error loading PCs</option>';
                    });
            }
        }

        // Add event listener when document is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const labSelect = document.getElementById('lab');
            labSelect.addEventListener('change', handleLabChange);
            
            // If a lab is already selected, load its PCs
            if (labSelect.value) {
                handleLabChange();
            }
        });

        function getTimeSlotString(time) {
            // Define your slots here (should match your backend)
            const slots = [
                "08:00-09:00", "09:00-10:00", "10:00-11:00", "11:00-12:00",
                "12:00-13:00", "13:00-14:00", "14:00-15:00", "15:00-16:00",
                "16:00-17:00", "17:00-18:00"
            ];
            for (let slot of slots) {
                if (slot.startsWith(time)) return slot;
            }
            return null;
        }

        // Add this new function for real-time validation
        function checkLabAvailability(lab, date, time) {
            return new Promise((resolve, reject) => {
                const slotString = getTimeSlotString(time);
                if (!slotString) {
                    reject('Invalid time slot');
                    return;
                }

                fetch(`get_lab_availability.php?date=${date}&time_slot=${slotString}`)
                    .then(response => response.json())
                    .then(labs => {
                        const labStatus = labs.find(l => l.lab === lab);
                        if (!labStatus || labStatus.status === 'unavailable') {
                            reject('Lab is unavailable');
                        } else {
                            resolve(true);
                        }
                    })
                    .catch(error => reject(error));
            });
        }

        // Modify the form submission to use AJAX
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const lab = document.getElementById('lab').value;
            const date = document.querySelector('input[name="date"]').value;
            const time = document.querySelector('input[name="time_in"]').value;
            
            if (!lab || !date || !time) {
                alert('Please fill in all required fields.');
                return;
            }

            // Disable submit button and show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = 'Checking availability...';

            // Check availability before submitting
            checkLabAvailability(lab, date, time)
                .then(() => {
                    // Double check availability one more time before submitting
                    return fetch(`get_lab_availability.php?date=${date}&time_slot=${getTimeSlotString(time)}`)
                        .then(response => response.json())
                        .then(labs => {
                            const labStatus = labs.find(l => l.lab === lab);
                            if (!labStatus || labStatus.status !== 'available') {
                                throw new Error('Lab is no longer available');
                            }
                        });
                })
                .then(() => {
                    // If still available, submit the form
                    submitButton.innerHTML = 'Submitting...';
                    this.submit();
                })
                .catch(error => {
                    alert(error);
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                });
        });

        // Enhance handleLabAvailability to update in real-time
        function handleLabAvailability() {
            const dateInput = document.querySelector('input[name="date"]');
            const timeInput = document.querySelector('input[name="time_in"]');
            const labSelect = document.getElementById('lab');
            const submitButton = document.querySelector('button[type="submit"]');
            
            if (!dateInput.value || !timeInput.value) {
                submitButton.disabled = true;
                return;
            }
            
            const slotString = getTimeSlotString(timeInput.value);
            if (!slotString) {
                submitButton.disabled = true;
                return;
            }
            
            // Show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = 'Checking availability...';
            
            fetch(`get_lab_availability.php?date=${dateInput.value}&time_slot=${slotString}`)
                .then(response => response.json())
                .then(labs => {
                    let hasAvailableLab = false;
                    Array.from(labSelect.options).forEach(option => {
                        if (!option.value) return;
                        const lab = labs.find(l => l.lab === option.value);
                        if (lab && lab.status === 'unavailable') {
                            option.disabled = true;
                            option.textContent = `${option.value} (Unavailable)`;
                            option.style.color = '#999';
                            option.style.backgroundColor = '#f3f4f6';
                            option.style.cursor = 'not-allowed';
                        } else {
                            option.disabled = false;
                            option.textContent = option.value;
                            option.style.color = '';
                            option.style.backgroundColor = '';
                            option.style.cursor = '';
                            hasAvailableLab = true;
                        }
                    });
                    submitButton.disabled = !hasAvailableLab;
                    submitButton.innerHTML = hasAvailableLab ? 'Submit Reservation' : 'No Labs Available';
                })
                .catch(error => {
                    console.error('Error checking availability:', error);
                    submitButton.disabled = true;
                    submitButton.innerHTML = 'Error Checking Availability';
                });
        }

        // Add event listeners for real-time updates
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.querySelector('input[name="date"]');
            const timeInput = document.querySelector('input[name="time_in"]');
            const labSelect = document.getElementById('lab');
            
            if (dateInput && timeInput) {
                dateInput.addEventListener('change', handleLabAvailability);
                timeInput.addEventListener('change', handleLabAvailability);
                labSelect.addEventListener('change', handleLabAvailability);
            }
        });

        // Add this JavaScript function after the existing handleLabAvailability function
        function validateForm() {
            const labSelect = document.getElementById('lab');
            const selectedOption = labSelect.options[labSelect.selectedIndex];
            const dateInput = document.querySelector('input[name="date"]');
            const timeInput = document.querySelector('input[name="time_in"]');
            
            if (!dateInput.value || !timeInput.value) {
                alert('Please select both date and time.');
                return false;
            }
            
            if (selectedOption.disabled) {
                alert('This lab is unavailable for the selected date and time. Please choose a different lab or time.');
                return false;
            }
            
            // Additional check for lab availability
            const slotString = getTimeSlotString(timeInput.value);
            if (!slotString) {
                alert('Invalid time slot selected.');
                return false;
            }
            
            return true;
        }
    </script>
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

                <form method="POST" action="" class="space-y-6" onsubmit="return validateForm()">
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
                            <select name="lab" id="lab" class="w-full px-3 py-2 border rounded" required onchange="handleLabChange()">
                                <option value="">Select Laboratory</option>
                                <?php foreach ($labs as $lab): ?>
                                    <option value="<?= $lab ?>" <?= isset($_GET['lab']) && $_GET['lab'] == $lab ? 'selected' : '' ?>><?= $lab ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Note: Labs marked as unavailable cannot be reserved for the selected date and time.</p>
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">PC <span class="text-red-500">*</span></label>
                            <select name="pc_id" id="pc_id" class="w-full px-3 py-2 border rounded" required disabled>
                                <option value="">Select PC</option>
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
                            <?php 
                            $sessionValue = $_SESSION['session'];
                            ?>
                            <input type="text" name="session" class="w-full px-3 py-2 border rounded" value="<?php echo $sessionValue; ?>" readonly>
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