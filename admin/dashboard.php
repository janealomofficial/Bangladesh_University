<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
include 'admin_header.php';
?>

<h2>Dashboard</h2>
<div class="row">
  <div class="col-md-4">
    <div class="card p-3 bg-primary text-white">
      <h4>Total Students</h4>
      <p><?= $DB_con->query("SELECT COUNT(*) FROM students")->fetchColumn(); ?></p>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3 bg-success text-white">
      <h4>Total Courses</h4>
      <p><?= $DB_con->query("SELECT COUNT(*) FROM courses")->fetchColumn(); ?></p>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3 bg-info text-white">
      <h4>Total Semesters</h4>
      <p><?= $DB_con->query("SELECT COUNT(*) FROM semesters")->fetchColumn(); ?></p>
    </div>
  </div>
</div>

<?php include 'admin_footer.php'; ?>
