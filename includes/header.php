<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dashboard_link = "";
if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'admin':   $dashboard_link = "/university_ms/admin/dashboard.php"; break;
        case 'faculty': $dashboard_link = "/university_ms/faculty/dashboard.php"; break;
        case 'student': $dashboard_link = "/university_ms/student/dashboard.php"; break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>University Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { margin: 0; font-family: Arial, sans-serif; }
        .top-bar {
            background: #0d2240;
            color: #ffc107;
            padding: 5px 20px;
            font-size: 14px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: space-between;
            
        }

        .top-bar-nav{
            color: #fff;
            padding: 10px 20px;
            
        }
        .top-bar-nav a{
            color: #fff;
            font-size: medium;
            padding: 10px;
            
        }
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            padding: 10px 20px;
            border-bottom: 1px solid #242121ff;
        }
        .navbar .logo { display: flex; align-items: center; }
        .navbar .logo img { height: 50px; margin-right: 10px; }
        .navbar .menu { display: flex; gap: 20px; }
        .navbar .menu a {
            text-decoration: none;
            color: #0d2240;
            font-weight: bold;
            font-size: 15px;
        }
        .navbar .menu a:hover { color: #da1010ff; }
        .navbar .menu .highlight {
            background: #0d2240;
            color: #fff;
            padding: 6px 12px;
            border-radius: 4px;
        }
        .navbar .menu .highlight:hover { background: #0c0c0cff; }
    </style>
</head>
<body>

<div class="top-bar">
    <div>
        <marquee class="marquee" behavior="scroll" direction="left" scrollamount="6">
    🎓 Welcome to Bangladesh University – A Center Of Excellence For Higher Education
        </marquee>
    </div>

    <div class="top-bar-nav">
        <nav>
        <a href="alumni.php">Allumni</a>
        <a href="#">3rd Convocation</a>
        <a href="#">Result</a>
    </nav>
    </div>
</div>

<div class="navbar">
    <div class="logo">
        <img src="assets/images/BU.jpg" alt="Logo">
        <span><strong>Bangladesh University</strong></span>
    </div>
    <div class="menu">
        <a href="/university_ms/index.php">Home</a>
        <a href="about.php">About</a>
        <a href="#">Admissions</a>
        <a href="#">Departments</a>
        <a href="#">News & Events</a>
        <a href="#">Library</a>
        <a href="contact-us.php">Contact</a>
        <?php if($dashboard_link): ?>
            <a href="<?php echo $dashboard_link; ?>">Dashboard</a>
            <a href="/university_ms/logout.php">Logout</a>
        <?php else: ?>
            <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
            <a href="/university_ms/register.php" class="highlight">Register</a>
        <?php endif; ?>
    </div>
</div>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="/university_ms/process_login.php">
        <div class="modal-header">
          <h5 class="modal-title" id="loginModalLabel">Login</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control" required>
              <option value="admin">Admin</option>
              <option value="faculty">Faculty</option>
              <option value="student">Student</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Login</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

