<?php
  session_start();
  include "connector.php";
  include "admin_nav.php";
  include "admin_auth.php";
  
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
  <body class="bg-gray-50">
      <div class="max-w-7xl mx-auto p-4">
          <!-- Stats and Chart Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
              <!-- Total Users Card -->
              <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-lg shadow-2xl">
                  <div class="flex items-center justify-between">
                      <div>
                          <h3 class="text-white text-sm font-medium">Total Users</h3>
                          <p class="text-4xl font-bold text-white mt-2"><?php echo $totalUsers; ?></p>
                      </div>
                      <div class="bg-white/20 p-3 rounded-full">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                          </svg>
                      </div>
                  </div>
                  <div class="mt-4 flex items-center text-white/80 text-sm">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                      </svg>
                      <span>Total registered students</span>
                  </div>
              </div>

              <!-- Pie Chart -->
              <div class="bg-white p-6 rounded-lg shadow-2xl">
                  <h3 class="text-lg font-semibold mb-4">User Status Overview</h3>
                  <div class="h-[200px]">
                      <canvas id="userStatusChart"></canvas>
                  </div>
              </div>
          </div>
  
          <!-- Main Content Grid -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <!-- Previous Sit-in Records -->
              <div class="bg-white p-6 rounded-lg shadow-2xl">
                  <h3 class="text-lg font-semibold mb-4">Previous Sit-in Records</h3>
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
                                  $timeIn = date('M d, Y h:i A', strtotime($row['time_in']));
                                  $timeOut = date('M d, Y h:i A', strtotime($row['time_out']));;
                                  
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
              <div class="bg-white p-6 rounded-lg shadow-2xl">
                  <h3 class="text-lg font-semibold mb-4">Recent Announcements</h3>
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
  
      <script>
          document.addEventListener('DOMContentLoaded', function() {
              const ctx = document.getElementById('userStatusChart');
              new Chart(ctx, {
                  type: 'pie',
                  data: {
                      labels: ['Total Users'],
                      datasets: [{
                          data: [<?php echo $totalUsers; ?>],
                          backgroundColor: ['#3B82F6']
                      }]
                  },
                  options: {
                      responsive: true,
                      maintainAspectRatio: false,
                      plugins: {
                          legend: {
                              position: 'bottom',
                              labels: {
                                  padding: 20
                              }
                          }
                      }
                  }
              });
          });
      </script>
  </body>
  </html>
