<?php 
session_start();

include "../database/connector.php";
include "../admin/admin_nav.php";    

// Get unique values for filters
$query_labs = "SELECT '524' as lab UNION SELECT '526' UNION SELECT '528' UNION SELECT '530' UNION SELECT '542' UNION SELECT '544' UNION SELECT '517'";
$query_purposes = "SELECT 'C Programming' as sitin_purpose UNION SELECT 'C#' UNION SELECT 'Java' UNION SELECT 'PHP' UNION SELECT 'Database' UNION SELECT 'Digital & Logic Design' UNION SELECT 'Embedded Systems & IOT' UNION SELECT 'Python Programming' UNION SELECT 'System Integration & Architecture' UNION SELECT 'Computer Application' UNION SELECT 'Web Design & Development' UNION SELECT 'Project Management'";
$result_labs = mysqli_query($mysql, $query_labs);
$result_purposes = mysqli_query($mysql, $query_purposes);

// Get the earliest record date
$query_earliest = "SELECT MIN(time_in) as earliest_date FROM sit_in";
$result_earliest = mysqli_query($mysql, $query_earliest);
$earliest_date = mysqli_fetch_assoc($result_earliest)['earliest_date'];

// Base query
$query = "SELECT students.idno, students.lastname, students.firstname, students.midname, students.course, students.year, 
          sit_in.sitin_purpose, sit_in.lab, sit_in.time_in, sit_in.time_out 
          FROM sit_in
          JOIN students ON sit_in.idno = students.idno
          WHERE sit_in.time_out IS NOT NULL";

// Apply filters if set
if (isset($_GET['start_date']) && !empty($_GET['start_date'])) {
    $start_date = mysqli_real_escape_string($mysql, $_GET['start_date']);
    $query .= " AND DATE(sit_in.time_in) >= '$start_date'";
} else {
    // Set default start date to earliest record
    $start_date = date('Y-m-d', strtotime($earliest_date));
    $query .= " AND DATE(sit_in.time_in) >= '$start_date'";
}

if (isset($_GET['end_date']) && !empty($_GET['end_date'])) {
    $end_date = mysqli_real_escape_string($mysql, $_GET['end_date']);
    $query .= " AND DATE(sit_in.time_in) <= '$end_date'";
} else {
    // Set default end date to present
    $end_date = date('Y-m-d');
    $query .= " AND DATE(sit_in.time_in) <= '$end_date'";
}

if (isset($_GET['lab']) && !empty($_GET['lab'])) {
    $lab = mysqli_real_escape_string($mysql, $_GET['lab']);
    $query .= " AND sit_in.lab = '$lab'";
}

if (isset($_GET['purpose']) && !empty($_GET['purpose'])) {
    $purpose = mysqli_real_escape_string($mysql, $_GET['purpose']);
    $query .= " AND sit_in.sitin_purpose = '$purpose'";
}

$query .= " ORDER BY sit_in.time_in DESC";
$result = mysqli_query($mysql, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sit-in Records</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow-2xl rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold">Sit-in Records</h2>
                    <div class="flex gap-2">
                        <button onclick="exportToExcel()" class="flex items-center px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M5.18 4.616a.5.5 0 0 1 .704.064L8 7.219l2.116-2.54a.5.5 0 1 1 .768.641L8.651 8l2.233 2.68a.5.5 0 0 1-.768.64L8 8.781l-2.116 2.54a.5.5 0 0 1-.768-.641L7.349 8 5.116 5.32a.5.5 0 0 1 .064-.704z"/>
                                <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                            </svg>
                            Excel
                        </button>
                        <button onclick="exportToCSV()" class="flex items-center px-4 py-2 bg-yellow-600 text-white text-sm rounded-lg hover:bg-yellow-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                            </svg>
                            CSV
                        </button>
                        <button onclick="exportToPDF()" class="flex items-center px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029z"/>
                            </svg>
                            PDF
                        </button>
                    </div>
                </div>

                <!-- Filters -->
                <form class="mb-6 grid grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime($earliest_date)); ?>" 
                               class="text-sm w-full rounded-md border p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="end_date" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d'); ?>"
                               class="text-sm w-full rounded-md border p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lab</label>
                        <select name="lab" class="text-sm w-full rounded-md border p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Labs</option>
                            <?php while ($row = mysqli_fetch_assoc($result_labs)): ?>
                                <option value="<?php echo htmlspecialchars($row['lab']); ?>" <?php echo (isset($_GET['lab']) && $_GET['lab'] == $row['lab']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($row['lab']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                        <select name="purpose" class="text-sm w-full rounded-md border p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Purposes</option>
                            <?php while ($row = mysqli_fetch_assoc($result_purposes)): ?>
                                <option value="<?php echo htmlspecialchars($row['sitin_purpose']); ?>" <?php echo (isset($_GET['purpose']) && $_GET['purpose'] == $row['sitin_purpose']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($row['sitin_purpose']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-span-4 flex justify-end">
                        <button type="submit" class="bg-blue-600 text-sm text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            Apply Filters
                        </button>
                    </div>
                </form>
                
                <!-- Summary Section -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h3 class="text-sm font-semibold text-gray-600 mb-2">Total Records</h3>
                            <p class="text-2xl font-bold text-blue-600"><?php echo mysqli_num_rows($result); ?></p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h3 class="text-sm font-semibold text-gray-600 mb-2">Date Range</h3>
                            <p class="text-sm text-gray-800">
                                <?php 
                                if (isset($_GET['start_date']) && !empty($_GET['start_date'])) {
                                    echo date('M d, Y', strtotime($_GET['start_date']));
                                } else {
                                    echo 'All time';
                                }
                                echo ' - ';
                                if (isset($_GET['end_date']) && !empty($_GET['end_date'])) {
                                    echo date('M d, Y', strtotime($_GET['end_date']));
                                } else {
                                    echo 'Present';
                                }
                                ?>
                            </p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h3 class="text-sm font-semibold text-gray-600 mb-2">Filters Applied</h3>
                            <div class="flex flex-wrap gap-2">
                                <?php if (isset($_GET['lab']) && !empty($_GET['lab'])): ?>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">Lab: <?php echo htmlspecialchars($_GET['lab']); ?></span>
                                <?php endif; ?>
                                <?php if (isset($_GET['purpose']) && !empty($_GET['purpose'])): ?>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Purpose: <?php echo htmlspecialchars($_GET['purpose']); ?></span>
                                <?php endif; ?>
                                <?php if (!isset($_GET['lab']) && !isset($_GET['purpose']) && !isset($_GET['start_date']) && !isset($_GET['end_date'])): ?>
                                    <span class="text-gray-500 text-xs">No filters applied</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <div class="grid grid-cols-7 text-center border-b-2 py-3 bg-gray-50">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">ID</p>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Name</p>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Course & Year</p>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</p>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Lab</p>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</p>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Time Out</p>
                    </div>
                    <div class="overflow-y-auto max-h-[600px]">
                        <?php while ($row = mysqli_fetch_assoc($result)): 
                            $time_in = strtotime($row['time_in']);
                            $time_out = strtotime($row['time_out']);
                        ?>
                            <div class="grid grid-cols-7 text-center border-b py-4 hover:bg-gray-50 transition">
                                <p class="text-sm text-gray-900"><?php echo htmlspecialchars($row['idno']); ?></p>
                                <p class="text-sm text-gray-900"><?php echo htmlspecialchars($row['lastname'] . ', ' . $row['firstname'] . ' ' . $row['midname']); ?></p>
                                <p class="text-sm text-gray-900"><?php echo htmlspecialchars($row['course'] . ' - ' . $row['year']); ?></p>
                                <p class="text-sm text-gray-900"><?php echo htmlspecialchars($row['sitin_purpose']); ?></p>
                                <p class="text-sm text-gray-900"><?php echo htmlspecialchars($row['lab']); ?></p>
                                <p class="text-sm text-gray-900"><?php echo date('M d, Y - h:i A', $time_in); ?></p>
                                <p class="text-sm text-gray-900"><?php echo date('M d, Y - h:i A', $time_out); ?></p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function getTableData() {
            const data = [];
            const headers = ['ID', 'Name', 'Course & Year', 'Purpose', 'Lab', 'Time In', 'Time Out'];
            data.push(headers);

            const rows = document.querySelectorAll('.grid.grid-cols-7:not(.bg-gray-50)');
            rows.forEach(row => {
                const rowData = [];
                const cells = row.querySelectorAll('p');
                cells.forEach(cell => {
                    rowData.push(cell.textContent.trim());
                });
                data.push(rowData);
            });

            return data;
        }

        function exportToExcel() {
            const data = getTableData();
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(data);
            XLSX.utils.book_append_sheet(wb, ws, 'Sit-in Records');
            XLSX.writeFile(wb, 'sitin_records.xlsx');
        }

        function exportToCSV() {
            const data = getTableData();
            let csvContent = data.map(row => row.join(',')).join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'sitin_records.csv';
            link.click();
        }

        function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'mm', 'a4');
            
            // Calculate center position
            const pageWidth = doc.internal.pageSize.width;
            const centerX = pageWidth / 2;
            
            // Add logos at the top
            doc.addImage('../images/logo.png', 'PNG', centerX - 25, 5, 20, 20);
            doc.addImage('../images/ccs.jpg', 'JPEG', centerX + 5, 5, 20, 20);
            
            // Add header text centered
            doc.setFontSize(16);
            doc.text('University of Cebu', centerX, 35, { align: 'center' });
            doc.setFontSize(14);
            doc.text('College of Computer Studies', centerX, 42, { align: 'center' });
            doc.setFontSize(12);
            doc.text('Sit-in Records Report', centerX, 49, { align: 'center' });
            
            const data = getTableData();
            
            doc.autoTable({
                head: [data[0]],
                body: data.slice(1),
                startY: 55,
                theme: 'grid',
                styles: {
                    fontSize: 8,
                    cellPadding: 2,
                },
                headStyles: {
                    fillColor: [0, 0, 255],
                    textColor: [255, 255, 255],
                    fontSize: 9,
                    fontStyle: 'bold',
                },
                alternateRowStyles: {
                    fillColor: [245, 245, 245]
                }
            });

            doc.save('sitin_records.pdf');
        }
    </script>
</body>
</html>

