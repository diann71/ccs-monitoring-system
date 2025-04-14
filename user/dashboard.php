<?php session_start();

include "../database/connector.php";
include "../user/nav.php";
include "../database/authenticator.php";

$result = mysqli_query($mysql, "SELECT * FROM announcements");    
$row = mysqli_fetch_assoc($result);

if($row){
    $announcement_id = $row['announcement_id'];
    $title = $row['title'];
    $description = $row['description'];
    $created_at = $row['created_at'];

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>  
</head>
<body class="bg-gray-00 min-h-screen">
    <div class="max-w-7xl mx-auto p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Student Information Card -->
            <div class=" border shadow-2xl rounded-lg p-6">
                <div class="text-center pt-5">
                    <img class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-gray-500" src="../uploads/<?php echo $profile; ?>" alt="Profile Picture">
                    <h2 class="text-xl font-bold mt-4">Welcome,<?php echo ' ' . $firstname . ' ' . $lastname; ?>!</h2>
                </div>
                <div class="mt-6 space-y-2">
                    <p class="flex items-center gap-2">
                        <!-- ID Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="black" class="w-5 h-5 text-gray-600" viewBox="0 0 16 16">
                            <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zm4.5 0a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1zM8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6m5 2.755C12.146 12.825 10.623 12 8 12s-4.146.826-5 1.755V14a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1z"/>
                        </svg>
                        <span class="font-bold">ID No:</span> <?php echo $idno; ?>
                    </p>
                    <p class="flex items-center gap-2">
                        <!-- Book Icon for Course -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="black"  class="w-5 h-5 text-gray-600" viewBox="0 0 16 16">
                            <path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917z"/>
                            <path d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466z"/>
                        </svg>
                        <span class="font-bold">Course:</span> <?php echo $course; ?>
                    </p>
                    <p class="flex items-center gap-2">
                        <!-- Calendar Icon for Year -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="black" viewBox="0 0 24 24" stroke="black">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 4h10M5 11h14v10H5V11z" />
                        </svg>
                        <span class="font-bold">Year:</span> <?php echo $year; ?>
                    </p>
                    <p class="flex items-center gap-2">
                        <!-- Clock Icon for Sessions Remaining -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="black" class="w-5 h-5 bi bi-clock-fill" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
                        </svg>
                        <span class="font-bold">Sessions Remaining:</span> <?php echo $session; ?>
                    </p>
                </div>
                <div class="mt-10 text-center">
                    <a href="profile.php" class="inline-flex items-center bg-white text-blue-700 px-4 py-2 rounded-lg shadow hover:bg-gray-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 mr-1" viewBox="0 0 16 16">
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                        </svg>
                        View Profile
                    </a>
                </div>  
            </div>
            <!-- Announcements Card -->
            <div class="border shadow-2xl rounded-lg col-span-2 p-6">
                <h2 class="text-xl text-black font-bold mb-3">Recent Announcements</h2>
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    <?php 
                    $result = mysqli_query($mysql, "SELECT * FROM announcements ORDER BY created_at DESC");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='border-b pb-4'>";
                        echo "<h3 class='font-semibold'>" . htmlspecialchars($row['title']) . "</h3>";
                        echo "<p class='text-sm text-gray-600'>" . htmlspecialchars($row['description']) . "</p>";
                        echo "<p class='text-xs text-gray-500 mt-2'>Posted on " . htmlspecialchars($row['created_at']) . "</p>";
                        echo "</div>";
                    }
                    ?>
                </div>
                
            </div>
        </div>

        <!-- Rules and Recent History Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <!-- Rules and Regulations -->
            <div class="bg-white shadow-2xl rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4">Rules and Regulations</h2>
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    <h2 class="text-lg text-center font-bold">University of Cebu</h2>
                    <h2 class="text-base text-center  font-bold">COLLEGE OF INFORMATION & COMPUTER STUDIES</h2>
                    <div class=" space-y-4 text-sm">
                        <p>To avoid embarrassment and maintain camaraderie with your friends and superiors at our laboratories, please observe the following:</p>
                        <p>1. Maintain silence, proper decorum, and discipline inside the laboratory. Mobile phones, walkmans, and other personal pieces of equipment must be switched off.</p>
                        <p>2. Games are not allowed inside the lab. This includes computer-related games, card games, and other games that may disturb the operation of the lab.</p>
                        <p>3. Surfing the Internet is allowed only with the permission of the instructor. Downloading and installing of software are strictly prohibited.</p>
                        <p>4. Getting access to other websites not related to the course (especially pornographic and illicit sites) is strictly prohibited.</p>
                        <p>5. Deleting computer files and changing the set-up of the computer is a major offense.</p>
                        <p>6. Observe computer time usage carefully. A fifteen-minute allowance is given for each use. Otherwise, the unit will be given to those who wish to "sit-in".</p>
                        <p>7. Observe proper decorum while inside the laboratory.</p>
                        <p>- Do not get inside the lab unless the instructor is present.</p>
                        <p>- All bags, knapsacks, and the likes must be deposited at the counter.</p>
                        <p>- Follow the seating arrangement of your instructor.</p>
                        <p>- At the end of class, all software programs must be closed.</p>
                        <p>- Return all chairs to their proper places after using.</p>
                        <p>8. Chewing gum, eating, drinking, smoking, and other forms of vandalism are prohibited inside the lab.</p>
                        <p>9. Anyone causing a continual disturbance will be asked to leave the lab. Acts or gestures offensive to the members of the community, including public display of physical intimacy, are not tolerated.</p>
                        <p>10. Persons exhibiting hostile or threatening behavior such as yelling, swearing, or disregarding requests made by lab personnel will be asked to leave the lab.</p>
                        <p>11. For serious offenses, the lab personnel may call the Civil Security Office (CSU) for assistance.</p>
                        <p>12. Any technical problem or difficulty must be addressed to the laboratory supervisor, student assistant, or instructor immediately.</p>
                    </div>
                    <h1 class="text-base text-center  font-bold">DISCIPLINARY ACTION</h1>
                    <div class="space-y-4 text-sm">
                        <p>First Offense - The Head or the Dean or OIC recommends to the Guidance Center for a suspension from classes for each offender.</p>
                        <p>Second and Subsequent Offenses - A recommendation for a heavier sanction will be endorsed to the Guidance Center.</p>
                    </div>
                </div>
            </div>
            
            <?php
            // Fetch recent history for the logged-in user
            $query_completed = "SELECT sit_in.idno, sit_in.sitin_purpose, sit_in.time_in, sit_in.time_out 
                                FROM sit_in 
                                WHERE sit_in.idno = '$idno' AND sit_in.time_out IS NOT NULL 
                                ORDER BY sit_in.time_out DESC";
            $result_completed = mysqli_query($mysql, $query_completed);

            if (!$result_completed) {
                die("Query failed: " . mysqli_error($mysql));
            }
            ?>

            <!-- Recent History -->
            <div class="bg-white shadow-2xl rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4">Recent History</h2>
                <div class="overflow-y-auto max-h-96">
                    <div class="grid grid-cols-4 text-center border-b-2 pb-2 min-w-[600px] bg-gray-100 pt-2">
                        <p class="font-bold">ID</p>
                        <p class="font-bold">Purpose</p>
                        <p class="font-bold">Time In</p>
                        <p class="font-bold">Time Out</p>
                    </div>
                    <?php while ($row = mysqli_fetch_assoc($result_completed)): ?>
                        <div class="grid grid-cols-4 text-center border-b-2 py-2 min-w-[600px] gap-3">
                            <p class="text-sm pt-3"><?php echo $row['idno']; ?></p>
                            <p class="text-sm pt-3"><?php echo $row['sitin_purpose']; ?></p>
                            <p class="text-sm"><?php echo date('M d, Y - h:i A', strtotime($row['time_in'])); ?></p>
                            <p class="text-sm"><?php echo date('M d, Y - h:i A', strtotime($row['time_out'])); ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

