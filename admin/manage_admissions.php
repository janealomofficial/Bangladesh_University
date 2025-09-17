<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle actions: mark paid, delete
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = (int)$_GET['id'];

    if ($action === 'mark_paid') {
        // create payment reference and optionally create invoice
        $payment_reference = 'ADMINPAY' . strtoupper(bin2hex(random_bytes(4)));
        $DB_con->prepare("UPDATE admissions SET payment_status='paid', payment_reference=:pref WHERE id=:id")
            ->execute([':pref' => $payment_reference, ':id' => $id]);

        // create invoice if not exists
        $chk = $DB_con->prepare("SELECT id FROM admission_invoices WHERE admission_id=:aid LIMIT 1");
        $chk->execute([':aid' => $id]);
        if (!$chk->fetch()) {
            // create invoice (amount default 5000)
            $amount = 5000.00;
            $datepart = date('Ymd');
            $seq_stmt = $DB_con->prepare("SELECT COUNT(*) FROM admission_invoices WHERE DATE(issued_at)=CURDATE()");
            $seq_stmt->execute();
            $seq = (int)$seq_stmt->fetchColumn() + 1;
            $invoice_no = 'INV' . $datepart . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
            $ins = $DB_con->prepare("INSERT INTO admission_invoices (admission_id, invoice_no, amount, status) VALUES (:aid, :inv, :amt, 'paid')");
            $ins->execute([':aid' => $id, ':inv' => $invoice_no, ':amt' => $amount]);
        }
    }

    if ($action === 'delete') {
        $DB_con->prepare("DELETE FROM admissions WHERE id=:id")->execute([':id' => $id]);
    }

    header("Location: manage_admissions.php");
    exit;
}

// fetch admissions with invoice if any
$rows = $DB_con->query("
    SELECT a.*, ai.invoice_no, ai.id AS invoice_id
    FROM admissions a
    LEFT JOIN admission_invoices ai ON ai.admission_id = a.id
    ORDER BY a.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

?>
<?php include 'admin_header.php'; ?>

<div class="container-fluid p-4">
    <h2>Manage Admissions</h2>

    <table class="table table-striped table-bordered mt-3">
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
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= htmlspecialchars($r['student_id']) ?></td>
                    <td><?= htmlspecialchars($r['full_name']) ?></td>
                    <td><?= htmlspecialchars($r['email']) ?></td>
                    <td><?= htmlspecialchars($r['phone']) ?></td>
                    <td><?= htmlspecialchars($r['department']) ?></td>
                    <td><?= htmlspecialchars($r['program']) ?></td>
                    <td><?= htmlspecialchars($r['batch']) ?></td>
                    <td>
                        <?php if ($r['payment_status'] === 'paid'): ?>
                            <span class="badge bg-success">Paid</span><br>
                            <small><?= htmlspecialchars($r['payment_reference']) ?></small>
                        <?php else: ?>
                            <span class="badge bg-warning">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($r['invoice_id'])): ?>
                            <a href="../invoice.php?invoice_id=<?= $r['invoice_id'] ?>" target="_blank"><?= htmlspecialchars($r['invoice_no']) ?></a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($r['payment_status'] !== 'paid'): ?>
                            <a href="manage_admissions.php?action=mark_paid&id=<?= $r['id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Mark as paid?')">Mark paid</a>
                        <?php endif; ?>
                        <a href="manage_admissions.php?action=delete&id=<?= $r['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this applicant?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'admin_footer.php'; ?>