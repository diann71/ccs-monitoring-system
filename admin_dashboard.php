<?php
session_start();
include "connector.php";
include "admin_nav.php";
include "admin_auth.php";

$idno = $_SESSION["idno"];

$result = mysqli_query($mysql, "SELECT * FROM admin WHERE admin_id = '$idno'");    
$row = mysqli_fetch_assoc($result);
 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div class="">
       
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

