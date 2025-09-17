<?php
require_once __DIR__ . "/app/config/db.php";
$invoice_id = (int)($_GET['invoice_id'] ?? 0);
if (!$invoice_id) {
    die("Invoice ID required.");
}

$stmt = $DB_con->prepare("
  SELECT ai.*, a.full_name, a.student_id, a.email, a.phone, a.address, a.program, a.batch, a.department
  FROM admission_invoices ai
  JOIN admissions a ON ai.admission_id = a.id
  WHERE ai.id = :id
  LIMIT 1
");
$stmt->execute([':id' => $invoice_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) die("Invoice not found.");

?>
<?php require_once __DIR__ . "/includes/header.php"; ?>
<div class="container my-5">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Invoice: <?= htmlspecialchars($row['invoice_no']) ?></h3>
                <div>
                    <button class="btn btn-outline-primary" onclick="window.print()">Print / Save PDF</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h5>Student:</h5>
                    <p><?= htmlspecialchars($row['full_name']) ?> <br>
                        Student ID: <?= htmlspecialchars($row['student_id']) ?><br>
                        Email: <?= htmlspecialchars($row['email']) ?><br>
                        Phone: <?= htmlspecialchars($row['phone']) ?><br>
                        Address: <?= nl2br(htmlspecialchars($row['address'])) ?>
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <h5>Issued:</h5>
                    <p><?= htmlspecialchars($row['issued_at']) ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($row['status']) ?></p>
                </div>
            </div>

            <hr>
            <p><strong>Program:</strong> <?= htmlspecialchars($row['program']) ?> | <strong>Batch:</strong> <?= htmlspecialchars($row['batch']) ?></p>

            <table class="table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-end">Amount (BDT)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Admission/Application Fee</td>
                        <td class="text-end"><?= number_format($row['amount'], 2) ?></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-end">Total</th>
                        <th class="text-end"><?= number_format($row['amount'], 2) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . "/includes/footer.php"; ?>