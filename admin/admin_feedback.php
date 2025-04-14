<?php 
session_start();

include "../database/connector.php";
include "../admin/admin_nav.php";

$idno = $_SESSION["idno"];

$query = "SELECT feedback.*, students.lastname, students.firstname, students.midname, students.course, students.year 
          FROM feedback 
          JOIN students ON feedback.idno = students.idno 
          ORDER BY feedback.created_at DESC";

$result = mysqli_query($mysql, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Feedback</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow-2xl rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold">Student Feedback</h2>
                </div>
                
                <div class="overflow-x-auto">
                    <div class="grid grid-cols-5 text-center border-b-2 py-3 bg-gray-50">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">ID</p>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Name</p>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Course & Year</p>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Feedback</p>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Created</p>
                    </div>
                    <div class="overflow-y-auto max-h-[600px]">
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <div class="grid grid-cols-5 text-center border-b py-4 hover:bg-gray-50 transition">
                                <p class="text-sm text-gray-900"><?php echo htmlspecialchars($row['idno']); ?></p>
                                <p class="text-sm text-gray-900"><?php echo htmlspecialchars($row['lastname'] . ', ' . $row['firstname'] . ' ' . $row['midname']); ?></p>
                                <p class="text-sm text-gray-900"><?php echo htmlspecialchars($row['course'] . ' - ' . $row['year']); ?></p>
                                <div class="relative group">
                                    <button onclick='openFeedbackModal(<?php 
                                        echo json_encode($row['feedback_text']) . ', ' .
                                             json_encode($row['idno']) . ', ' .
                                             json_encode($row['lastname'] . ', ' . $row['firstname'] . ' ' . $row['midname']) . ', ' .
                                             json_encode(date('M d, Y - h:i A', strtotime($row['created_at'])));
                                    ?>)' 
                                        class="text-blue-600 hover:text-blue-800 text-sm">
                                        View Feedback
                                    </button>
                                </div>  
                                <p class="text-sm text-gray-900"><?php echo date('M d, Y - h:i A', strtotime($row['created_at'])); ?></p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback View Modal -->
    <div id="feedbackModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-4xl relative">
            <button onclick="closeFeedbackModal()" class="absolute top-0 right-0 m-4 text-gray-500 hover:text-gray-700 text-4xl">&times;</button>
            <div class="space-y-4">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-lg font-bold text-gray-800">Feedback Details</h2>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="grid grid-cols-3 gap-6 mb-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Student ID</p>
                            <p id="studentId" class="text-gray-900 text-sm"></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Student Name</p>
                            <p id="studentName" class="text-gray-900 text-sm"></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Date Submitted</p>
                            <p id="feedbackDate" class="text-gray-900 text-sm"></p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-500 mb-2">Feedback Message</p>
                        <p id="fullFeedback" class="text-gray-900 break-words whitespace-pre-wrap bg-white p-4 rounded border text-sm"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput')?.addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('.grid.grid-cols-5:not(.bg-gray-50)');
            
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        // Feedback modal functions
        function openFeedbackModal(feedback, studentId, studentName, feedbackDate) {
            try {
                const modal = document.getElementById('feedbackModal');
                const fullFeedback = document.getElementById('fullFeedback');
                const studentIdElem = document.getElementById('studentId');
                const studentNameElem = document.getElementById('studentName');
                const feedbackDateElem = document.getElementById('feedbackDate');

                if (fullFeedback) fullFeedback.textContent = feedback;
                if (studentIdElem) studentIdElem.textContent = studentId;
                if (studentNameElem) studentNameElem.textContent = studentName;
                if (feedbackDateElem) feedbackDateElem.textContent = feedbackDate;
                if (modal) modal.classList.remove('hidden');
            } catch (error) {
                console.error('Error opening modal:', error);
            }
        }

        function closeFeedbackModal() {
            const modal = document.getElementById('feedbackModal');
            if (modal) modal.classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('feedbackModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeFeedbackModal();
            }
        });
    </script>
</body>
</html>