<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>
    <div class="d-flex">
        
        <!-- Sidebar -->
        <div class="bg-primary text-white p-3" style="width: 250px; min-height: 100vh;">
            <h4 class="mb-4">Student Panel</h4>
                                <!-- Profile always pinned at bottom -->
<div class="profile-section mt-auto p-3 border-top d-flex align-items-center justify-content-between">
  <div class="profile-info d-flex align-items-center">
    <!-- Dummy avatar icon -->
    <div class="rounded-circle bg-light text-dark d-flex align-items-center justify-content-center me-2"
         style="width:40px; height:40px; font-size:18px;">
      <i class="bi bi-person-fill"></i>
    </div>

    <div>
      <strong><?= $_SESSION['student_name'] ?? "Student"; ?></strong><br>
      <small style="color:#cfd8dc;">Student</small>
    </div>
  </div>

  <!-- Logout -->
  <a href="../logout.php" class="btn btn-sm btn-dark" title="Logout">
    <i class="bi bi-box-arrow-right"></i>
  </a>
</div>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="dashboard.php" class="nav-link text-white"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li class="nav-item"><a href="student_routine.php" class="nav-link text-white"><i class="bi bi-calendar3"></i> My Routine</a></li>
                <li class="nav-item"><a href="my_courses.php" class="nav-link text-white"><i class="bi bi-journal-bookmark"></i> My Courses</a></li>
                <li class="nav-item">
                <li class="nav-item">
                <a href="results.php" class="nav-link text-white">
                    <i class="bi bi-bar-chart-line"></i> My Results
                </a>
                </li>

                <li class="nav-item"><a href="../logout.php" class="nav-link text-white"><i class="bi bi-box-arrow-right"></i> Logout</a></li>

            </ul>


        </div>



        
        <!-- Main Content -->
        <div class="flex-grow-1 p-4">

        