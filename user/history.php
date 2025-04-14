<?php 
session_start();

include "../database/connector.php";
include "../user/nav.php";
include "../database/authenticator.php";

$idno = $_SESSION['idno'];

$query_completed = "SELECT students.idno, students.lastname, students.firstname, students.midname, students.course, students.year, sit_in.sitin_purpose, sit_in.time_in, sit_in.time_out 
            FROM sit_in
            JOIN students ON sit_in.idno = students.idno
            WHERE sit_in.time_out IS NOT NULL AND sit_in.idno = '$idno'
            ORDER BY sit_in.time_out DESC"; // Show latest records first
$result_completed = mysqli_query($mysql, $query_completed);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['feedback'])) {
    $idno = $_SESSION['idno'];  // Assuming student ID is stored in session
    $feedback = mysqli_real_escape_string($mysql, $_POST['feedback']); // Escape any special characters

    // Insert feedback into the database
    $insert_query = "INSERT INTO feedback (idno, feedback_text) 
                     VALUES ('$idno', '$feedback')";

    if (mysqli_query($mysql, $insert_query)) {
        echo "<script>alert('Feedback submitted successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($mysql) . "'); window.location.href='dashboard.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <div class="max-w-7xl mx-auto pt-5 pb-10 flex-grow">
        <div class="border shadow-2xl  w-full p-6">
            <h1 class="text-xl text-black font-bold mb-3">Sit-in History</h1>
            <div class="grid grid-cols-7 text-center bg-gray-200 border-b-2 py-3 font-semibold">
                <p class="font-medium text-xs text-gray-500 uppercase tracking-wider">ID</p>
                <p class="font-medium text-xs text-gray-500 uppercase tracking-wider">Full Name</p>
                <p class="font-medium text-xs text-gray-500 uppercase tracking-wider">Course & Year</p>
                <p class="font-medium text-xs text-gray-500 uppercase tracking-wider">Sit-in Purpose</p>
                <p class="font-medium text-xs text-gray-500 uppercase tracking-wider">Time In</p>
                <p class="font-medium text-xs text-gray-500 uppercase tracking-wider">Time Out</p>
                <p class="font-medium text-xs text-gray-500 uppercase tracking-wider">Action</p>
            </div>
            <div class="overflow-y-auto max-h-[700px]">
                <?php while ($row = mysqli_fetch_assoc($result_completed)): ?>
                    <div class="grid grid-cols-7 text-center border-b py-3 hover:bg-gray-50 transition">
                        <p class="text-gray-900 text-sm flex items-center justify-center"><?php echo $row['idno']; ?></p>
                        <p class="text-gray-900 text-sm flex items-center justify-center"><?php echo $row['lastname'] . ", " . $row['firstname'] . ' ' .  $row['midname'] ; ?></p>
                        <p class="text-gray-900 text-sm flex items-center justify-center"><?php echo $row['course'] . " - " . $row['year']; ?></p>
                        <p class="text-gray-900 text-sm flex items-center justify-center"><?php echo $row['sitin_purpose']; ?></p>
                        <p class="text-gray-900 text-sm flex items-center justify-center"><?php echo date('M d, Y - h:i A', strtotime($row['time_in'])); ?></p>
                        <p class="text-gray-900 text-sm flex items-center justify-center"><?php echo date('M d, Y - h:i A', strtotime($row['time_out'])); ?></p>
                        <div class="flex items-center justify-center">
                            <button onclick="openFeedbackModal()" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                Send Feedback
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- Feedback Modal -->
    <div id="feedbackModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">We value your feedback!</h2>
                <button onclick="closeFeedbackModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            <form action="" method="post">
                <p class="text-gray-600 mb-4">
                    Please let us know your thoughts about your experience. Your feedback helps us improve!
                </p>
                <label for="feedback" class="block text-gray-700 font-semibold mb-2">Your Feedback:</label>
                <textarea name="feedback" id="feedback" rows="3" maxlength="100" 
                    class="w-full border border-gray-300 p-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" 
                    placeholder="Write your feedback here (max 100 characters)"></textarea>
                <p class="text-sm text-gray-500 mt-1 text-right">
                    <span id="charCount">0</span>/100 characters
                </p>
                <div class="mt-6 flex justify-end gap-4">
                    <button type="submit" name="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-4 h-4 bi bi-send-fill" viewBox="0 0 16 16">
                            <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471z"/>
                        </svg>
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openFeedbackModal() {
            document.getElementById('feedbackModal').classList.remove('hidden');
        }

        function closeFeedbackModal() {
            document.getElementById('feedbackModal').classList.add('hidden');
        }

        // Character counter for feedback
        document.getElementById('feedback').addEventListener('input', function() {
            const charCount = this.value.length;
            document.getElementById('charCount').textContent = charCount;
            
            // Change color if approaching limit
            if (charCount >= 18) {
                document.getElementById('charCount').classList.add('text-red-500');
            } else {
                document.getElementById('charCount').classList.remove('text-red-500');
            }
        });
    </script>
</body>
</html>
