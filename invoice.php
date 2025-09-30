<?php
require_once __DIR__ . "/app/config/db.php";

$admission_id = (int)($_GET['id'] ?? 0);
if (!$admission_id) {
    die("Admission ID required.");
}

$stmt = $DB_con->prepare("
  SELECT a.*
  FROM admissions a
  WHERE a.id = :id
  LIMIT 1
");
$stmt->execute([':id' => $admission_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) die("Invoice not found.");

// If no invoice_no exists yet, generate one
if (empty($row['invoice_no'])) {
    $invoiceNo = "INV" . date("Ymd") . "-" . str_pad($row['id'], 4, "0", STR_PAD_LEFT);
    $update = $DB_con->prepare("UPDATE admissions SET invoice_no = :inv WHERE id = :id");
    $update->execute([':inv' => $invoiceNo, ':id' => $row['id']]);
    $row['invoice_no'] = $invoiceNo;
}

require_once __DIR__ . "/includes/header.php";
?>

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
                    <h5>Applicant:</h5>
                    <p><?= htmlspecialchars($row['full_name']) ?> <br>
                        Student ID: <?= htmlspecialchars($row['student_id']) ?><br>
                        Email: <?= htmlspecialchars($row['email']) ?><br>
                        Phone: <?= htmlspecialchars($row['phone']) ?><br>
                        Address: <?= nl2br(htmlspecialchars($row['address'])) ?>
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <h5>Issued:</h5>
                    <p><?= htmlspecialchars($row['created_at']) ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($row['payment_status']) ?></p>
                </div>
            </div>

            <hr>
            <p><strong>Program:</strong> <?= htmlspecialchars($row['program']) ?> | 
               <strong>Batch:</strong> <?= htmlspecialchars($row['batch']) ?></p>

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
