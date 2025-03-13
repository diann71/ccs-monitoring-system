<?php session_start();

include "connector.php";
include "nav.php";
include "authenticator.php";

$result = mysqli_query($mysql, "SELECT * FROM announcements");    
$row = mysqli_fetch_assoc($result);

if($row){
    $announcement_id = $row['announcement_id'];
    $title = $row['title'];
    $description = $row['description'];
    $created_at = $row['created_at'];

}
/*if ($row['session'] > 0) {
        $query = "UPDATE students SET session = session - 1 WHERE idno = '$idno'";
        mysqli_query($mysql, $query);
} else {
    echo "No remaining sessions!";
}*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
    </style>    
</head>
<body>
    <div class="">
        <div class="p-6 flex flex-col lg:flex-row gap-6 lg:gap-20 h-auto lg:h-screen">
            <!-- Student Information Card -->
            <div class="mt-14 mb-10 w-full lg:w-1/3 h-[30rem] border shadow-2xl overflow-hidden">
                <div class="w-full bg-blue-700 py-3">
                    <h1 class="w-full font-bold text-white text-center">Student Information</h1>
                </div>

                <div class="flex flex-col items-center">
                    <!-- Display Current Profile Picture -->
                    <img class="mt-8 mb-4 w-48 h-48 rounded-full object-cover" src="uploads/<?php echo $profile; ?>" alt="">
                </div>

                <div class="p-6">
                    <p><span class="font-bold">Hi Wleco</span> <?php echo $idno; ?></p>
                    <p><span class="font-bold">Name:</span> <?php echo $firstname . ' ' . $midname . ' ' . $lastname?></p>
                    <p><span class="font-bold">Course:</span> <?php echo $course; ?></p>
                    <p><span class="font-bold">Year:</span> <?php echo $year; ?></p>
                    <p><span class="font-bold">Sessions:</span> <?php echo $session; ?></p>
                </div>
        </div>

        <!-- Announcements Card -->
        <div class="overflow-y-auto mt-14 mb-10 w-full lg:w-1/3 h-[30rem] border shadow-2xl overflow-hidden">
            <div class="sticky top-0 w-full bg-blue-700 py-3">
                <h1 class="w-full font-bold text-white text-center">Announcements</h1>
            </div>
            <div class="p-5">
                <?php 
                   $result = mysqli_query($mysql, "SELECT * FROM announcements ORDER BY created_at DESC LIMIT 5");

                    while($row  = mysqli_fetch_assoc($result)){
                        echo "<h1 class='font-bold pb-2'>" .  htmlspecialchars($row['title']) . "</h1>";
                        echo "<div class='border border-solid'>";
                        echo "<p class='p-5'>" . htmlspecialchars($row['description']) . "</p>";
                        echo "</div>";
                     }
                ?>
            </div>
        </div>

        <!-- Rules and Regulations Card -->
        <div class="overflow-y-auto h-[30rem] mt-14 mb-10 w-full lg:w-1/3 border shadow-2xl overflow-hidden">
            <div class="sticky top-0 left-0 w-full bg-blue-700 py-3">
                <h1 class="w-full font-bold text-white text-center">Rules and Regulations</h1>
            </div>
            <div class="pt-2 p-5">
                <h2 class="font-bold text-center">University of Cebu</h2>
                <h2 class="font-bold text-center">COLLEGE OF INFORMATION & COMPUTER STUDIES</h2>
                <h1 class="font-bold pt-4">LABORATORY RULES AND REGULATIONS</h1>
                <p>To avoid embarrassment and maintain camaraderie with your friends and superiors at our laboratories, please observe the following:</p>
                <p class="pt-4">1. Maintain silence, proper decorum, and discipline inside the laboratory. Mobile phones, walkmans and other personal pieces of equipment must be switched off.</p>
                <p class="pt-4">2. Games are not allowed inside the lab. This includes computer-related games, card games and other games that may disturb the operation of the lab.</p>
                <p class="pt-4">3. Surfing the Internet is allowed only with the permission of the instructor. Downloading and installing of software are strictly prohibited.</p>
                <p class="pt-4">4. Getting access to other websites not related to the course (especially pornographic and illicit sites) is strictly prohibited.</p>
                <p class="pt-4">5. Deleting computer files and changing the set-up of the computer is a major offense.</p>
                <p class="pt-4">6. Observe computer time usage carefully. A fifteen-minute allowance is given for each use. Otherwise, the unit will be given to those who wish to "sit-in".</p>
                <p class="pt-4">7. Observe proper decorum while inside the laboratory.
                    <p> - Do not get inside the lab unless the instructor is present.</p>
                    <p> - All bags, knapsacks, and the likes must be deposited at the counter</p>
                    <p> - Follow the seating arrangement of your instructor.</p>
                    <p> - At the end of class, all software programs must be closed.</p>
                    <p> - Return all chairs to their proper places after using.</p></p>
                <p class="pt-4">8. Chewing gum, eating, drinking, smoking, and other forms of vandalism are prohibited inside the lab.</p>
                <p class="pt-4">9. Anyone causing a continual disturbance will be asked to leave the lab. Acts or gestures offensive to the members of the community, including public display of physical intimacy, are not tolerated.</p>
                <p class="pt-4">10. Persons exhibiting hostile or threatening behavior such as yelling, swearing, or disregarding requests made by lab personnel will be asked to leave the lab.</p>
                <p class="pt-4">11. For serious offense, the lab personnel may call the Civil Security Office (CSU) for assistance.</p>
                <p class="pt-4">12. Any technical problem or difficulty must be addressed to the laboratory supervisor, student assistant or instructor immediately</p>

                <h1 class="font-bold pt-5">DISCIPLINARY ACTION</h1>
                <p class="pt-4">First Offense - The Head or the Dean or OIC recommends to the Guidance Center for a suspension from classes for each offender.</p>
                <P class="pt-4">Second and Subsequent Offenses - A recommendation for a heavier sanction will be endorsed to the Guidance Center.</P>
            </div>
        </div>
    </div>

    </div>
<!--<div class="bg-white py-10">
    <div class=" px-6 lg:px-8">
        <div class="grid grid-cols-2 bg-white">
            <div class="p-8">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    Welcome to CSS Sit-in Monitoring System
                </h2>
                <p class="mt-4 text-gray-500">
                    The walnut wood card tray is precision milled to perfectly fit a stack of Focus cards. The powder-coated steel divider separates active cards from new ones, or can be used to archive important task lists.
                </p>

                <dl class="mt-16 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 sm:gap-y-16 lg:gap-x-8">
                    <div class="border-t border-gray-200 pt-4">
                        <dt class="font-medium text-gray-900">Origin</dt>
                        <dd class="mt-2 text-sm text-gray-500">Designed by Good Goods, Inc.</dd>
                    </div>
                    <div class="border-t border-gray-200 pt-4">
                        <dt class="font-medium text-gray-900">Material</dt>
                        <dd class="mt-2 text-sm text-gray-500">Solid walnut base with rare earth magnets and powder-coated steel card cover</dd>
                    </div>
                    <div class="border-t border-gray-200 pt-4">
                        <dt class="font-medium text-gray-900">Dimensions</dt>
                        <dd class="mt-2 text-sm text-gray-500">6.25&quot; x 3.55&quot; x 1.15&quot;</dd>
                    </div>
                    <div class="border-t border-gray-200 pt-4">
                        <dt class="font-medium text-gray-900">Finish</dt>
                        <dd class="mt-2 text-sm text-gray-500">Hand sanded and finished with natural oil</dd>
                    </div>
                    <div class="border-t border-gray-200 pt-4">
                        <dt class="font-medium text-gray-900">Includes</dt>
                        <dd class="mt-2 text-sm text-gray-500">Wood card tray and 3 refill packs</dd>
                    </div>
                    <div class="border-t border-gray-200 pt-4">
                        <dt class="font-medium text-gray-900">Considerations</dt>
                        <dd class="mt-2 text-sm text-gray-500">Made from natural materials. Grain and color vary with each item.</dd>
                    </div>
                </dl>
            </div>  
            <div class="flex items-center justify-center">
                <h1 class="text-3xl font-bold text-gray-900">asda</h1>
            </div>
        </div>


        <div class="mt-20 grid max-w-2xl grid-cols-3 gap-20 pt-10 border-t border-gray-400  lg:mx-0 lg:max-w-none lg:grid-cols-3">
            <div class="flex items-center max-w-xl flex-col items-start justify-between">
                <div class="mt-20 border border-gray-300 w-96 h-[30rem]">
                    <div class="text-center bg-blue-700 p-2">
                        <h3 class="text-lg font-semibold text-white h-1/2">Student Information</h3>
                    </div>
                    <div class="flex flex-col items-center">
                            <img class="mt-9 mb-3 w-40 h-40 rounded-full border border-black object-cover" src="uploads/<?//php echo $profile; ?>" alt="">
                            <p class="font-semibold">Hi, <?php echo $firstname?>!</p>
                    </label> 
                </div>
                    <div class="p-6">
                        <div class="pt-1">
                            <p><span class="font-bold">Id No:</span> <?php echo $idno; ?></p>
                            <p><span class="font-bold">First Name:</span> <?php echo $firstname;?></p>
                            <p><span class="font-bold">Mid Name:</span> <?php echo $midname; ?></p>
                            <p><span class="font-bold">Last Name:</span> <?php echo $lastname; ?></p>
                            <p><span class="font-bold">Course:</span> <?php echo $course; ?></p>
                            <p><span class="font-bold">Year:</span> <?php echo $year; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border border-gray-300 h-[40rem]">
                <div class="text-center bg-blue-700 p-2">
                    <h3 class="text-lg font-semibold text-white h-1/2">Announcements</h3>
                </div>
            </div>
            <div class="border border-gray-300 h-[40rem]">
                <div class="text-center bg-blue-700 p-2">
                    <h3 class="text-lg font-semibold text-white h-1/2">Rules And Regulations</h3>
                </div>
                
            </div>
        </div>
    </div>
</div>
    div class=" ">
        <div class=" p-6 flex flex-row gap-20 h-screen">
            <div class="mt-5 mb-10 w-1/3 h-1/2 border border-black shadow-2xl rounded-3xl overflow-hidden ">
                <div class="w-full bg-blue-700 py-3">
                    <h1 class="w-full font-bold text-white text-center">Student Information</h1>
                </div>

                <div class="flex flex-col items-center">
                    <img class="mt-8 mb-4 w-48 h-48 rounded-full border border-black object-cover" src="uploads/<?//php echo $profile; ?>" alt="">
                    
                    <label class="cursor-pointer bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 mt-4">
                        Choose Image
                        <input type="file" name="profile_picture" class="hidden" accept="image/*">
                    </label> 
                </div>

                <div class="p-6">
                    <p><span class="font-bold">Id No:</span> <?//php echo $idno; ?></p>
                    <p><span class="font-bold">Name:</span> <?//php echo $firstname . ' ' . $midname . ' ' . $lastname?></p>
                    <p><span class="font-bold">Course:</span> <?//php echo $course; ?></p>
                    <p><span class="font-bold">Year:</span> <?//php echo $year; ?></p>
                </div>

                
            </div>

            <div class="mt-5 mb-10 w-1/3 h-1/2 border border-black shadow-2xl rounded-3xl overflow-hidden">
                <div class="w-full bg-blue-700 py-3">
                    <h1 class="w-full font-bold text-white text-center">Announcements</h1>
                </div>
            </div>

            <div class="mt-5 mb-10 w-1/3 h-1/2 border border-black shadow-2xl rounded-3xl overflow-hidden">
               <div class="w-full bg-blue-700 py-3">
                    <h1 class="w-full font-bold text-white text-center">Rules and Regulations</h1>
                </div>
            </div>

        </div>
    </div>
 -->
</body>
</html>

