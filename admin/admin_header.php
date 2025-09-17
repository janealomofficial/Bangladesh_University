<!-- admin_header.php -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body {
      display: flex;
      min-height: 100vh;
      margin: 0;
    }

    .sidebar {
      width: 250px;
      background-color: #1a237e;
      color: white;
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0;
      bottom: 0;
      padding: 20px 0;
    }

    .sidebar h4 {
      color: #fff;
      margin: 0 20px 20px;
    }

    /* Scrollable menu section */
    /* Scrollable menu section */
    .menu-container {
      flex-grow: 1;
      overflow-y: auto;
      padding: 0 20px;

      /* Hide scrollbar but keep scrolling functional */
      scrollbar-width: none;
      /* Firefox */
      -ms-overflow-style: none;
      /* IE 10+ */
    }

    .menu-container::-webkit-scrollbar {
      display: none;
      /* Chrome, Safari */
    }


    .sidebar a {
      color: #cfd8dc;
      display: block;
      padding: 10px 0;
      text-decoration: none;
      transition: 0.2s;
    }

    .sidebar a:hover {
      color: #fff;
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 5px;
      padding-left: 12px;
    }

    .profile-section {
      border-top: 1px solid rgba(255, 255, 255, 0.3);
      padding: 15px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .profile-info {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .profile-info img {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid white;
    }

    .content {
      margin-left: 250px;
      padding: 30px;
      flex-grow: 1;
      overflow-y: auto;
      background-color: #f0f2f5;
    }
  </style>
</head>

<body>
  <div class="sidebar">
    <h4>Admin Panel</h4>
    <div class="menu-container">
      <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
      <a href="manage_admissions.php"><i class="bi bi-box-fill"></i> Manage Admissions</a>
      <a href="manage_students.php"><i class="bi bi-people"></i> Manage Students</a>
      <a href="manage_faculty.php"><i class="bi bi-person-badge"></i> Manage Faculty</a>
      <a href="manage_users.php"><i class="bi bi-person-gear"></i> Manage Users</a>
      <a href="manage_courses.php"><i class="bi bi-book"></i> Manage Courses</a>
      <a href="manage_semesters.php"><i class="bi bi-calendar-event"></i> Manage Semesters</a>
      <a href="manage_alumni.php"><i class="bi bi-mortarboard"></i> Manage Alumni</a>
      <a href="manage_news_events.php"><i class="bi bi-newspaper"></i> Manage News & Events</a>
      <a href="manage_departments.php"><i class="bi bi-building"></i> Manage Departments</a>
      <a href="manage_convocation.php"><i class="bi bi-award"></i> Manage Convocation</a>
    </div>

    <!-- Profile always pinned at bottom -->
    <div class="profile-section">
      <div class="profile-info">
        <img src="../uploads/admin-avatar.png" alt="Admin">
        <div>
          <strong><?= $_SESSION['admin_name'] ?? "Admin"; ?></strong><br>
          <small style="color:#cfd8dc;">Administrator</small>
        </div>
      </div>
      <a href="../logout.php" class="btn btn-sm btn-dark" title="Logout">
        <i class="bi bi-box-arrow-right"></i>
      </a>
    </div>
  </div>

  <div class="content">