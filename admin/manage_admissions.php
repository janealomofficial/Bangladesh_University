<?php 
session_start();
require_once __DIR__ . "/../app/config/db.php";

// Check admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Fetch admissions with the latest invoice info
$sql = "
    SELECT a.id, a.student_id, a.full_name, a.email, a.phone, a.department, 
           a.program, a.batch, a.payment_status, a.payment_reference,
           ai.invoice_no, ai.id AS invoice_id
    FROM admissions a
    LEFT JOIN admission_invoices ai 
        ON ai.admission_id = a.id 
       AND ai.id = (
            SELECT MAX(id) 
            FROM admission_invoices 
            WHERE admission_id = a.id
       )
    ORDER BY a.id DESC
";
$stmt = $DB_con->query($sql);
$admissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'admin_header.php';
?>

<div class="container-fluid mt-4">
    <h2>Manage Admissions</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Department</th>
                <th>Program</th>
                <th>Batch</th>
                <th>Payment</th>
                <th>Invoice</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($admissions): ?>
            <?php foreach ($admissions as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['student_id']) ?></td>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['department']) ?></td>
                    <td><?= htmlspecialchars($row['program']) ?></td>
                    <td><?= htmlspecialchars($row['batch']) ?></td>
                    <td>
                        <?php if ($row['payment_status'] === 'paid'): ?>
                            <span class="badge bg-success">Paid</span><br>
                            <small><?= htmlspecialchars($row['payment_reference']) ?></small>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Pending</span>
                        <?php endif; ?>
                    </td>
                        <td>
                    <?php if (!empty($row['invoice_no'])): ?>
                        <a href="invoice.php?invoice_id=<?= (int)$row['invoice_id'] ?>" target="_blank">
                            <?= htmlspecialchars($row['invoice_no']) ?>
                        </a>
                    <?php elseif ($row['payment_status'] === 'paid'): ?>
                        <!-- with helper/backfill, this will be rare -->
                        <span class="text-muted">Generating… refresh</span>
                    <?php else: ?>
                        <span class="text-muted">Payment Pending</span>
                    <?php endif; ?>
                    </td>

                    <td>
                        <a href="delete_admission.php?id=<?= $row['id'] ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this admission?');">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="11" class="text-center">No admissions found</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'admin_footer.php'; ?>
