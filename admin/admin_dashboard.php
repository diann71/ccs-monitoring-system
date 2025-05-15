<?php
  session_start();
  include "../database/connector.php";
  include "../admin/admin_nav.php";
  include "../database/admin_auth.php";
  
  $idno = $_SESSION["idno"];
  
  $result = mysqli_query($mysql, "SELECT * FROM admin WHERE admin_id = '$idno'");    
  $row = mysqli_fetch_assoc($result);
   
  // Get total users
  $sql = "SELECT COUNT(*) AS total_users FROM students";
  $result = $mysql->query($sql);
  $row = $result->fetch_assoc();
  $totalUsers = $row['total_users'];
  
 
  // Get announcements
  $announcements = mysqli_query($mysql, "SELECT * FROM announcements ORDER BY created_at DESC");
  
  // Get completed sit-in records
  $query_completed = "SELECT students.idno, students.lastname, students.firstname, students.midname, students.course, students.year, sit_in.sitin_purpose, sit_in.time_in, sit_in.time_out 
            FROM sit_in
            JOIN students ON sit_in.idno = students.idno
            WHERE sit_in.time_out IS NOT NULL
            ORDER BY sit_in.time_out DESC";
  $sitInRecords = mysqli_query($mysql, $query_completed);
  
  ?>  
  <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Admin Dashboard</title>
      <script src="https://cdn.tailwindcss.com"></script>
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>
  <body class="bg-gradient-to-br from-gray-100 to-blue-100 min-h-screen">
      <div class="max-w-7xl mx-auto p-4">
          <!-- Stats and Chart Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 items-stretch">
              <!-- Left Column: Hello Admin + Total Users (stacked) -->
              <div class="flex flex-col h-full gap-8">
                  <!-- Hello Admin Card -->
                  <div class="flex-1 bg-gradient-to-br from-indigo-500 to-purple-600 p-8 rounded-2xl shadow-2xl flex flex-col justify-center items-center border border-indigo-200 hover:shadow-3xl hover:scale-[1.02] transition-all duration-300 cursor-pointer">
                      <div class="mb-3">
                          <svg class="w-12 h-12 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                          </svg>
                      </div>
                      <h2 class="text-3xl font-extrabold text-white mb-2 tracking-tight">Hello, Admin!</h2>
                      <p class="text-white/90 text-lg">Welcome back to your dashboard.</p>
                  </div>
                  <!-- Total Users Card -->
                  <div class="flex-1 bg-gradient-to-br from-blue-500 to-blue-600 p-8 rounded-2xl shadow-2xl flex flex-col justify-center items-center border border-blue-200 hover:shadow-3xl hover:scale-[1.02] transition-all duration-300 cursor-pointer">
                      <a href="student_list.php" class="w-full h-full flex flex-col justify-center items-center">
                          <div class="flex items-center justify-between w-full mb-3">
                              <div>
                                  <h3 class="text-white text-sm font-medium">Total Users</h3>
                                  <p class="text-4xl font-extrabold text-white mt-2 drop-shadow"><?php echo $totalUsers; ?></p>
                              </div>
                              <div class="bg-white/20 p-3 rounded-full">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                  </svg>
                              </div>
                          </div>
                          <div class="mt-4 flex items-center text-white/80 text-sm w-full">
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                              </svg>
                              <span>Total registered students </span>
                          </div>
                      </a>
                  </div>
              </div>

              <!-- Right Column: Leaderboard -->
              <div class="bg-white p-8 rounded-2xl shadow-2xl flex flex-col justify-between h-full border border-indigo-100 hover:shadow-3xl hover:scale-[1.02] transition-all duration-300 cursor-pointer">
                  <h3 class="text-xl font-extrabold mb-6 text-indigo-700 flex items-center gap-2">
                      <svg class="w-8 h-8 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.454a1 1 0 00-1.175 0l-3.38 2.454c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.049 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                      Leaderboard: Top Students by Points
                  </h3>
                  <div class="overflow-x-auto flex-1">
                      <table class="min-w-full divide-y divide-gray-200 rounded-xl overflow-hidden">
                          <thead class="bg-indigo-50">
                              <tr>
                                  <th class="px-6 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Rank</th>
                                  <th class="px-6 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Name</th>
                                  <th class="px-6 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Course & Year</th>
                                  <th class="px-6 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Points</th>
                              </tr>
                          </thead>
                          <tbody class="bg-white divide-y divide-gray-200">
                              <?php
                              $leaderboard_query = "SELECT lastname, firstname, midname, course, year, points FROM students ORDER BY points DESC, lastname ASC LIMIT 20";
                              $leaderboard_result = mysqli_query($mysql, $leaderboard_query);
                              $rank = 1;
                              if ($leaderboard_result && mysqli_num_rows($leaderboard_result) > 0) {
                                  while ($student = mysqli_fetch_assoc($leaderboard_result)) {
                                      $fullName = htmlspecialchars($student['lastname']) . ', ' . htmlspecialchars($student['firstname']) . ' ' . htmlspecialchars($student['midname']);
                                      $courseYear = htmlspecialchars($student['course']) . ' - ' . htmlspecialchars($student['year']);
                                      $badge = '';
                                      $rankDisplay = $rank;
                                      if ($rank == 1) {
                                          $badge = "<span class='inline-flex items-center px-2 py-1 ml-2 text-xs font-bold leading-none text-yellow-900 bg-yellow-300 rounded-full'><svg class='w-4 h-4 mr-1 text-yellow-500' fill='currentColor' viewBox='0 0 20 20'><circle cx='10' cy='10' r='10' fill='#FFD700'/><text x='10' y='15' text-anchor='middle' font-size='10' fill='#fff' font-family='Arial' font-weight='bold'>★</text></svg>1st</span>";
                                          $rankDisplay = "<svg class='w-6 h-6 inline' fill='none' viewBox='0 0 24 24'><circle cx='12' cy='12' r='10' fill='#FFD700'/><text x='12' y='17' text-anchor='middle' font-size='13' fill='#fff' font-family='Arial' font-weight='bold'>★</text></svg>";
                                      } elseif ($rank == 2) {
                                          $badge = "<span class='inline-flex items-center px-2 py-1 ml-2 text-xs font-bold leading-none text-gray-900 bg-gray-300 rounded-full'><svg class='w-4 h-4 mr-1 text-gray-500' fill='currentColor' viewBox='0 0 20 20'><circle cx='10' cy='10' r='10' fill='#C0C0C0'/></svg>2nd</span>";
                                          $rankDisplay = "<svg class='w-6 h-6 inline' fill='none' viewBox='0 0 24 24'><circle cx='12' cy='12' r='10' fill='#C0C0C0'/></svg>";
                                      } elseif ($rank == 3) {
                                          $badge = "<span class='inline-flex items-center px-2 py-1 ml-2 text-xs font-bold leading-none text-amber-900 bg-amber-200 rounded-full'><svg class='w-4 h-4 mr-1 text-amber-500' fill='currentColor' viewBox='0 0 20 20'><circle cx='10' cy='10' r='10' fill='#CD7F32'/></svg>3rd</span>";
                                          $rankDisplay = "<svg class='w-6 h-6 inline' fill='none' viewBox='0 0 24 24'><circle cx='12' cy='12' r='10' fill='#CD7F32'/></svg>";
                                      } else {
                                          $badge = '';
                                          $rankDisplay = $rank;
                                      }
                                      $rowBg = $rank % 2 == 0 ? 'bg-indigo-50' : '';
                                      echo "<tr class='hover:bg-indigo-100 transition $rowBg'>";
                                      echo "<td class='px-6 py-4 whitespace-nowrap text-center font-bold text-indigo-600'>" . $rankDisplay . " $badge</td>";
                                      echo "<td class='px-6 py-4 whitespace-nowrap'>" . $fullName . "</td>";
                                      echo "<td class='px-6 py-4 whitespace-nowrap'>" . $courseYear . "</td>";
                                      echo "<td class='px-6 py-4 whitespace-nowrap font-semibold text-indigo-700'>" . htmlspecialchars($student['points']) . "</td>";
                                      echo "</tr>";
                                      $rank++;
                                  }
                              } else {
                                  echo "<tr><td colspan='4' class='text-center text-gray-500 py-6'>No students found.</td></tr>";
                              }
                              ?>
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
  
          <!-- Main Content Grid -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <!-- Previous Sit-in Records -->
              <div class="bg-white p-6 rounded-lg shadow-2xl hover:shadow-3xl hover:scale-[1.02] transition-all duration-300 cursor-pointer">
                  <h3 class="text-xl font-semibold mb-4">Previous Sit-in Records</h3>
                  <div class="overflow-x-auto">
                      <div class="grid grid-cols-5 text-center border-b-2 pb-2 mb-2">
                          <p class="font-bold text-center">ID</p>
                          <p class="font-bold text-center">Full Name</p>
                          <p class="font-bold text-center">Course & Year</p>
                          <p class="font-bold text-center">Time In</p>
                          <p class="font-bold text-center">Time Out</p>
                      </div>
                      <div class="space-y-2 max-h-[400px] overflow-y-auto">
                          <?php 
                          if ($sitInRecords && mysqli_num_rows($sitInRecords) > 0) {
                              while ($row = mysqli_fetch_assoc($sitInRecords)) {
                                  $fullName = htmlspecialchars($row['lastname']) . ", " . htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['midname']);
                                  $courseYear = htmlspecialchars($row['course']) . " - " . htmlspecialchars($row['year']);
                                  $timeIn = date('M d, Y - h:i A', strtotime($row['time_in']));
                                  $timeOut = date('M d, Y - h:i A', strtotime($row['time_out']));;
                                  
                                  echo "<div class='grid grid-cols-5 text-center border-b pb-2'>";
                                  echo "<p class='text-center'>" . htmlspecialchars($row['idno']) . "</p>";
                                  echo "<p class='text-center '>" . $fullName . "</p>";
                                  echo "<p class='text-center'>" . $courseYear . "</p>";
                                  echo "<p class='text-center text-sm'>" . $timeIn . "</p>";
                                  echo "<p class='text-center text-sm'>" . $timeOut . "</p>";
                                  echo "</div>";
                              }
                          } else {
                              echo "<p class='text-gray-500 text-center'>No records found</p>";
                          }
                          ?>
                      </div>
                  </div>
              </div>
  
              <!-- Announcements Section -->
              <div class="bg-white p-6 rounded-lg shadow-2xl hover:shadow-3xl hover:scale-[1.02] transition-all duration-300 cursor-pointer">
                  <h3 class="text-xl font-semibold mb-4">Recent Announcements</h3>
                  <div class="space-y-4 max-h-[400px] overflow-y-auto">
                      <?php 
                      if ($announcements && mysqli_num_rows($announcements) > 0) {
                          while ($row = mysqli_fetch_assoc($announcements)) {
                              echo "<div class='border rounded p-4'>";
                              echo "<h4 class='font-medium'>" . htmlspecialchars($row['title']) . "</h4>";
                              echo "<p class='text-sm text-gray-600 mt-2'>" . htmlspecialchars($row['description']) . "</p>";
                              echo "<div class='mt-3 flex justify-between items-center'>";
                              echo "<span class='text-xs text-gray-500'>" . date('M d, Y', strtotime($row['created_at'])) . "</span>";
                              echo "<div class='space-x-2'>";
                              echo "</div></div></div>";
                          }
                      } else {
                          echo "<p class='text-gray-500 text-center'>No announcements found</p>";
                      }
                      ?>
                  </div>
              </div>
          </div>
      </div>
  </body>
  </html>
