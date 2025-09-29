<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

// Auth check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ===== Fetch Admissions Revenue =====
$admissions = [];
try {
    $admissionStmt = $DB_con->query("
        SELECT id AS admission_id, applicant_name, payment_amount, payment_status, created_at 
        FROM admissions
    ");
    $admissions = $admissionStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $admissions = [];
}

// ===== Fetch Semester Payments =====
$payments = [];
try {
    $paymentStmt = $DB_con->query("
        SELECT p.payment_id, s.full_name AS student_name, sem.name AS semester_name, 
               p.amount, p.status, p.paid_on
        FROM payments p
        JOIN students s ON p.student_id = s.student_id
        JOIN semesters sem ON p.semester_id = sem.semester_id
    ");
    $payments = $paymentStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $payments = [];
}

// ===== Calculate Totals =====
$totalAdmissions = array_sum(array_column($admissions, 'payment_amount'));
$totalSemester   = array_sum(array_column($payments, 'amount'));
$grandTotal      = $totalAdmissions + $totalSemester;

include 'admin_header.php';
?>

<div class="container-fluid p-4">
    <h2>📊 Revenue Report</h2>

    <div class="row my-3">
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Admission Revenue</h5>
                    <p class="display-6">৳ <?= number_format($totalAdmissions, 2); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Semester Fees</h5>
                    <p class="display-6">৳ <?= number_format($totalSemester, 2); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Revenue</h5>
                    <p class="display-6">৳ <?= number_format($grandTotal, 2); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Buttons -->
    <div class="mb-3">
        <!-- <a href="export_revenue_pdf.php" class="btn btn-danger">Export to PDF</a> -->
        <a href="export_revenue_excel.php" class="btn btn-success">Export to Excel</a>
    </div>

    <!-- Admissions Table -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">Admission Payments</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Applicant</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Paid On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admissions as $a): ?>
                        <tr>
                            <td><?= $a['admission_id']; ?></td>
                            <td><?= htmlspecialchars($a['applicant_name']); ?></td>
                            <td><?= number_format($a['payment_amount'], 2); ?></td>
                            <td><?= htmlspecialchars($a['payment_status']); ?></td>
                            <td><?= htmlspecialchars($a['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($admissions)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No admissions found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Semester Fee Table -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">Semester Fee Payments</div>
        <div class="card-body table-responsive">
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
                    <?php foreach ($payments as $p): ?>
                        <tr>
                            <td><?= $p['payment_id']; ?></td>
                            <td><?= htmlspecialchars($p['student_name']); ?></td>
                            <td><?= htmlspecialchars($p['semester_name']); ?></td>
                            <td><?= number_format($p['amount'], 2); ?></td>
                            <td><?= htmlspecialchars($p['status']); ?></td>
                            <td><?= htmlspecialchars($p['paid_on']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($payments)): ?>
                        <tr>
                            <td colspan="6" class="text-center">No payments found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>