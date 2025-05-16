<?php
session_start();
include "../database/connector.php";
include "../admin/admin_nav.php";
include "../database/authenticator.php";

// Define labs and time slots
$labs = ['524', '526', '528', '530', '542', '544', '517'];
$time_slots = [
    "08:00-09:00", "09:00-10:00", "10:00-11:00", "11:00-12:00",
    "12:00-13:00", "13:00-14:00", "14:00-15:00", "15:00-16:00",
    "16:00-17:00", "17:00-18:00"
];

// Get the week to display (default: current week or from GET param)
if (isset($_GET['start_date']) && strtotime($_GET['start_date'])) {
    $start_date = date('Y-m-d', strtotime('monday this week', strtotime($_GET['start_date'])));
} else {
    $start_date = date('Y-m-d', strtotime('monday this week'));
}
$dates = [];
for ($i = 0; $i < 6; $i++) {
    $dates[] = date('Y-m-d', strtotime("$start_date +$i days"));
}

// Fetch current schedule
$schedule = [];
$sql = "SELECT * FROM lab_schedule WHERE date BETWEEN '{$dates[0]}' AND '{$dates[5]}'";
$result = $mysql->query($sql);
while ($row = $result->fetch_assoc()) {
    $schedule[$row['lab']][$row['date']][$row['time_slot']] = $row['status'];
}

function to12Hour($timeRange) {
    list($start, $end) = explode('-', $timeRange);
    $start12 = date('g:i A', strtotime($start));
    $end12 = date('g:i A', strtotime($end));
    return "$start12 - $end12";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lab Schedule Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .available { background: #d1fae5; color: #065f46; }
        .unavailable { background: #fee2e2; color: #991b1b; }
        td { text-align: center; cursor: pointer; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-6">Lab Schedule Management</h2>
        <div class="flex items-center mb-4 gap-2">
            <?php $prev = date('Y-m-d', strtotime($start_date . ' -7 days')); ?>
            <?php $next = date('Y-m-d', strtotime($start_date . ' +7 days')); ?>
            <a href="?start_date=<?= $prev ?>" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Previous Week</a>
            <form method="get" class="inline-block">
                <input type="date" name="start_date" value="<?= $start_date ?>" class="border rounded px-2 py-1">
                <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Go</button>
            </form>
            <a href="?start_date=<?= $next ?>" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Next Week</a>
        </div>
        <form method="post" action="save_lab_schedule.php">
            <div class="overflow-x-auto">
                <table class="min-w-full border rounded-lg bg-white">
                    <thead>
                        <tr>
                            <th class="px-2 py-2 border-b">Lab</th>
                            <th class="px-2 py-2 border-b">Time Slot</th>
                            <?php foreach ($dates as $date): ?>
                                <th class="px-2 py-2 border-b">
                                    <?= date('l', strtotime($date)) ?><br><?= date('M d', strtotime($date)) ?>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($labs as $lab): ?>
                            <?php foreach ($time_slots as $slot): ?>
                                <tr>
                                    <?php if ($slot === $time_slots[0]): ?>
                                        <td class="border px-2 py-2 font-bold text-center" rowspan="<?= count($time_slots) ?>">Lab <?= $lab ?></td>
                                    <?php endif; ?>
                                    <td class="border px-2 py-2 text-center"><?= to12Hour($slot) ?></td>
                                    <?php foreach ($dates as $date): 
                                        $status = $schedule[$lab][$date][$slot] ?? 'available';
                                    ?>
                                        <td class="border px-2 py-2 text-center">
                                            <span class="inline-block w-full py-1 rounded cursor-pointer <?php echo $status; ?>" onclick="toggleStatus(this)">
                                                <?= ucfirst($status) ?>
                                                <input type="hidden" name="status[<?= $lab ?>][<?= $date ?>][<?= $slot ?>]" value="<?= $status ?>">
                                            </span>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <br>
            <button type="submit" class="mt-4 px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Save Changes</button>
        </form>
    </div>
    <script>
        function toggleStatus(span) {
            let input = span.querySelector('input[type=hidden]');
            if (span.classList.contains('available')) {
                span.classList.remove('available');
                span.classList.add('unavailable');
                span.textContent = 'Unavailable';
                input.value = 'unavailable';
            } else {
                span.classList.remove('unavailable');
                span.classList.add('available');
                span.textContent = 'Available';
                input.value = 'available';
            }
            span.appendChild(input);
        }
    </script>
</body>
</html> 