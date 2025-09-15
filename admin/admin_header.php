<!-- admin_header.php -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      display: flex;
      min-height: 100vh;
      overflow: hidden;
    }

    .sidebar {
      width: 250px;
      background-color: #1a237e;
      color: white;
      flex-shrink: 0;
      padding: 20px;
      height: 100vh;
      position: fixed;
    }

    .sidebar h4 {
      color: #fff;
      margin-bottom: 30px;
    }

    .sidebar a {
      color: #cfd8dc;
      display: block;
      padding: 10px 0;
      text-decoration: none;
    }

    .sidebar a:hover {
      color: white;
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
    <a href="dashboard.php">Dashboard</a>
    <a href="manage_students.php">Manage Students</a>
    <a href="manage_courses.php">Manage Courses</a>
    <a href="manage_semesters.php">Manage Semesters</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_alumni.php">Manage Alumni</a>
    <a href="manage_news_events.php">Manage News Events</a>
    <a href="manage_departments.php">Manage Departments</a>
    <a href="../logout.php">Logout</a>
  </div>
  <div class="content">