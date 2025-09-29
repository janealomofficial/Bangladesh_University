<?php
session_start();
require_once __DIR__ . "/../app/config/db.php"; // adjust path if needed

// Auth check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}

/**
 * Safe count helper — returns 0 if table missing or query fails.
 */
function table_count($DB_con, $tableName)
{
  try {
    // use backticks to allow names with underscores etc.
    $sql = "SELECT COUNT(*) FROM `" . str_replace("`", "", $tableName) . "`";
    $stmt = $DB_con->query($sql);
    return (int)$stmt->fetchColumn();
  } catch (Exception $e) {
    // Log $e->getMessage() if you want — for now return 0 to avoid breaking dashboard
    return 0;
  }
}

// Fetch counts (add/remove tables as you have them)
$counts = [
  'students'      => table_count($DB_con, 'students'),
  'faculty'       => table_count($DB_con, 'faculty'),                 // <-- faculty count
  'courses'       => table_count($DB_con, 'courses'),
  'semesters'     => table_count($DB_con, 'semesters'),
  'admissions'    => table_count($DB_con, 'admissions'),
  'users'         => table_count($DB_con, 'users'),
  'alumni'        => table_count($DB_con, 'alumni'),
  'departments'   => table_count($DB_con, 'departments'),
  'convocations'  => table_count($DB_con, 'convocation_registrations'),
  'news_events'   => table_count($DB_con, 'news_events'),
];

include 'admin_header.php';
?>

<div class="container-fluid p-4">
  <h2>Dashboard</h2>

  <div class="row g-3 mt-3">
    <!-- Example card: Students -->
    <div class="col-sm-6 col-md-4 col-lg-3">
      <div class="card text-white bg-primary h-100">
        <div class="card-body">
          <h6 class="card-title">Total Students</h6>
          <p class="display-6 mb-0"><?= $counts['students']; ?></p>
        </div>
        <div class="card-footer bg-transparent border-0">
          <a href="manage_students.php" class="text-white small">Manage Students →</a>
        </div>
      </div>
    </div>

    <!-- Faculty -->
    <div class="col-sm-6 col-md-4 col-lg-3">
      <div class="card text-white" style="background:#ffb703;">
        <div class="card-body">
          <h6 class="card-title">Total Faculty</h6>
          <p class="display-6 mb-0"><?= $counts['faculty']; ?></p>
        </div>
        <div class="card-footer bg-transparent border-0">
          <a href="manage_faculty.php" class="text-dark small">Manage Faculty →</a>
        </div>
      </div>
    </div>

    <!-- Courses -->
    <div class="col-sm-6 col-md-4 col-lg-3">
      <div class="card text-white bg-success h-100">
        <div class="card-body">
          <h6 class="card-title">Total Courses</h6>
          <p class="display-6 mb-0"><?= $counts['courses']; ?></p>
        </div>
        <div class="card-footer bg-transparent border-0">
          <a href="manage_courses.php" class="text-white small">Manage Courses →</a>
        </div>
      </div>
    </div>

    <!-- Semesters -->
    <div class="col-sm-6 col-md-4 col-lg-3">
      <div class="card text-white bg-info h-100">
        <div class="card-body">
          <h6 class="card-title">Total Semesters</h6>
          <p class="display-6 mb-0"><?= $counts['semesters']; ?></p>
        </div>
        <div class="card-footer bg-transparent border-0">
          <a href="manage_semesters.php" class="text-white small">Manage Semesters →</a>
        </div>
      </div>
    </div>

    <!-- Admissions -->
    <div class="col-sm-6 col-md-4 col-lg-3">
      <div class="card text-white bg-secondary h-100">
        <div class="card-body">
          <h6 class="card-title">Total Admissions</h6>
          <p class="display-6 mb-0"><?= $counts['admissions']; ?></p>
        </div>
        <div class="card-footer bg-transparent border-0">
          <a href="manage_admissions.php" class="text-white small">Manage Admissions →</a>
        </div>
      </div>
    </div>

    <!-- Users -->
    <div class="col-sm-6 col-md-4 col-lg-3">
      <div class="card text-white bg-dark h-100">
        <div class="card-body">
          <h6 class="card-title">Total Users</h6>
          <p class="display-6 mb-0"><?= $counts['users']; ?></p>
        </div>
        <div class="card-footer bg-transparent border-0">
          <a href="manage_users.php" class="text-white small">Manage Users →</a>
        </div>
      </div>
    </div>

    <!-- Alumni -->
    <div class="col-sm-6 col-md-4 col-lg-3">
      <div class="card text-white bg-secondary h-100">
        <div class="card-body">
          <h6 class="card-title">Total Alumni</h6>
          <p class="display-6 mb-0"><?= $counts['alumni']; ?></p>
        </div>
        <div class="card-footer bg-transparent border-0">
          <a href="manage_alumni.php" class="text-white small">Manage Alumni →</a>
        </div>
      </div>
    </div>

    <!-- Departments -->
    <div class="col-sm-6 col-md-4 col-lg-3">
      <div class="card text-white bg-danger h-100">
        <div class="card-body">
          <h6 class="card-title">Departments</h6>
          <p class="display-6 mb-0"><?= $counts['departments']; ?></p>
        </div>
        <div class="card-footer bg-transparent border-0">
          <a href="manage_departments.php" class="text-white small">Manage Departments →</a>
        </div>
      </div>
    </div>

    <!-- Convocations -->
    <div class="col-sm-6 col-md-4 col-lg-3">
      <div class="card text-white bg-primary h-100">
        <div class="card-body">
          <h6 class="card-title">Convocation Registrations</h6>
          <p class="display-6 mb-0"><?= $counts['convocations']; ?></p>
        </div>
        <div class="card-footer bg-transparent border-0">
          <a href="manage_convocation.php" class="text-white small">Manage Convocation →</a>
        </div>
      </div>
    </div>

    <!-- News & Events -->
    <div class="col-sm-6 col-md-4 col-lg-3">
      <div class="card text-white bg-success h-100">
        <div class="card-body">
          <h6 class="card-title">News & Events</h6>
          <p class="display-6 mb-0"><?= $counts['news_events']; ?></p>
        </div>
        <div class="card-footer bg-transparent border-0">
          <a href="manage_news.php" class="text-white small">Manage News →</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card text-bg-info mb-3">
        <div class="card-body">
          <h5 class="card-title">Routine Management</h5>
          <a href="assign_routine.php" class="btn btn-light">Manage Routine</a>
        </div>
      </div>
    </div>

    <?php
    // ===== Revenue Queries =====
    // Admission Revenue
    $admRev = $DB_con->query("SELECT IFNULL(SUM(amount),0) FROM admission_invoices WHERE status='paid'")->fetchColumn();

    // Semester Fee Revenue
    $semRev = $DB_con->query("SELECT IFNULL(SUM(amount),0) FROM payments WHERE status='paid'")->fetchColumn();

    // Total Revenue
    $totalRev = $admRev + $semRev;
    ?>

    <!-- Revenue Summary -->
    <div class="col-sm-6 col-md-4 col-lg-4">
      <div class="card text-white bg-success h-100">
        <div class="card-body">
          <h6 class="card-title">💰 Total Revenue</h6>
          <p class="display-6 mb-0"><?= number_format($totalRev, 2); ?> ৳</p>
        </div>
        <div class="card-footer bg-transparent border-0 small">
          Admission: <?= number_format($admRev, 2); ?> ৳ <br>
          Semester Fees: <?= number_format($semRev, 2); ?> ৳
        </div>
      </div>
    </div>


  </div>
</div>

<?php include 'admin_footer.php'; ?>