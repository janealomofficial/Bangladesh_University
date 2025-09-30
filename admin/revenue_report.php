<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ---------- CONFIG ----------
$admission_fee = 5000; // fixed fee per admission

// ---------- Admission Revenue ----------
$stmt = $DB_con->prepare("SELECT COUNT(*) FROM admissions WHERE payment_status = 'paid'");
$stmt->execute();
$paid_admissions = (int)$stmt->fetchColumn();

$admission_revenue = $paid_admissions * $admission_fee;

// ---------- Semester Fee Revenue ----------
$stmt = $DB_con->prepare("SELECT COALESCE(SUM(amount),0) FROM payments WHERE status = 'paid'");
$stmt->execute();
$semester_revenue = (float)$stmt->fetchColumn();

$total_revenue = $admission_revenue + $semester_revenue;

// ---------- Admission Payments List ----------
$stmt = $DB_con->query("SELECT id, full_name, program, batch, payment_status, created_at 
                        FROM admissions WHERE payment_status = 'paid'");
$admissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ---------- Semester Payments List ----------
$stmt = $DB_con->query("SELECT p.payment_id, s.full_name, sem.name AS semester_name, 
                               p.amount, p.status, p.paid_on
                        FROM payments p
                        JOIN students s ON p.student_id = s.student_id
                        JOIN semesters sem ON p.semester_id = sem.semester_id
                        WHERE p.status = 'paid'");
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'admin_header.php';
?>

<div class="container-fluid mt-4">
    <h2>📊 Revenue Report</h2>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Admission Revenue</h5>
                    <p class="display-6">৳ <?= number_format($admission_revenue, 2) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Semester Fees</h5>
                    <p class="display-6">৳ <?= number_format($semester_revenue, 2) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <p class="display-6">৳ <?= number_format($total_revenue, 2) ?></p>
                </div>
            </div>
        </div>
    </div>

    <a href="export_revenue_excel.php" class="btn btn-success mb-3">Export to Excel</a>

    <!-- Admission Payments -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">Admission Payments</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Applicant</th>
                        <th>Program</th>
                        <th>Batch</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Paid On</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($admissions) > 0): ?>
                    <?php foreach ($admissions as $a): ?>
                        <tr>
                            <td><?= $a['id'] ?></td>
                            <td><?= htmlspecialchars($a['full_name']) ?></td>
                            <td><?= htmlspecialchars($a['program']) ?></td>
                            <td><?= htmlspecialchars($a['batch']) ?></td>
                            <td><?= number_format($admission_fee, 2) ?></td>
                            <td><span class="badge bg-success">Paid</span></td>
                            <td><?= $a['created_at'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">No admissions found</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Semester Fee Payments -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">Semester Fee Payments</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Student</th>
                        <th>Semester</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Paid On</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($payments) > 0): ?>
                    <?php foreach ($payments as $p): ?>
                        <tr>
                            <td><?= $p['payment_id'] ?></td>
                            <td><?= htmlspecialchars($p['full_name']) ?></td>
                            <td><?= htmlspecialchars($p['semester_name']) ?></td>
                            <td><?= number_format($p['amount'], 2) ?></td>
                            <td><span class="badge bg-success">Paid</span></td>
                            <td><?= $p['paid_on'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">No semester payments found</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>
