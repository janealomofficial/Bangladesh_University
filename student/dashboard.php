<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";
include 'student_header.php';

// Fetch student id from linked user
$stmt = $DB_con->prepare("SELECT student_id, full_name FROM students WHERE user_id = :uid");
$stmt->execute([':uid' => $_SESSION['user_id']]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

$fees = [];
$total_due = 0;

if ($student) {
    $student_id = $student['student_id'];

    // Fetch payment info
    $pay_stmt = $DB_con->prepare("
        SELECT p.payment_id, sem.name AS semester, p.amount, p.status, p.paid_on
        FROM payments p
        JOIN semesters sem ON p.semester_id = sem.semester_id
        WHERE p.student_id = :sid
        ORDER BY sem.start_date ASC
    ");
    $pay_stmt->execute([':sid' => $student_id]);
    $fees = $pay_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total due
    foreach ($fees as $f) {
        if ($f['status'] === 'pending') {
            $total_due += $f['amount'];
        }
    }
}
?>

<h2>🎓 Student Dashboard</h2>

<div class="row">
    <!-- Routine Card -->
    <div class="col-md-4">
        <div class="card text-bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">My Routine</h5>
                <a href="student_routine.php" class="btn btn-light">View Routine</a>
            </div>
        </div>
    </div>

    <!-- Fee Summary -->
    <div class="col-md-4">
        <div class="card text-bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">💰 Total Due</h5>
                <p class="fs-4"><?= number_format($total_due, 2) ?> ৳</p>
            </div>
        </div>
    </div>
</div>

<!-- Semester Fee Table -->
<div class="card mt-4">
    <div class="card-header bg-primary text-white">💳 Semester Fees</div>
    <div class="card-body">
        <?php if (!empty($fees)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Semester</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Paid On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fees as $f): ?>
                        <tr>
                            <td><?= htmlspecialchars($f['semester']); ?></td>
                            <td><?= number_format($f['amount'], 2); ?> ৳</td>
                            <td>
                                <?php if ($f['status'] === 'paid'): ?>
                                    <span class="badge bg-success">Paid</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $f['paid_on'] ?? '-'; ?></td>
                            <td>
                                <?php if ($f['status'] === 'pending'): ?>
                                    <a href="pay_fee.php?id=<?= $f['payment_id']; ?>" class="btn btn-sm btn-primary">Pay Now</a>
                                <?php else: ?>
                                    <a href="receipt.php?id=<?= $f['payment_id']; ?>" class="btn btn-sm btn-outline-success">Download Receipt</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No semester fee assigned yet.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'student_footer.php'; ?>