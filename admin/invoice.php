<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_GET['invoice_id']) || !is_numeric($_GET['invoice_id'])) {
    die("Invalid invoice ID.");
}

$invoice_id = (int) $_GET['invoice_id'];

// Fetch invoice + admission details
$stmt = $DB_con->prepare("
    SELECT ai.invoice_no, ai.amount, ai.status, ai.issued_at,
           a.full_name, a.email, a.phone, a.student_id, a.department, a.program, a.batch
    FROM admission_invoices ai
    JOIN admissions a ON ai.admission_id = a.id
    WHERE ai.id = :iid
");
$stmt->execute([':iid' => $invoice_id]);
$invoice = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$invoice) {
    die("Invoice not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice <?= htmlspecialchars($invoice['invoice_no']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container border p-4">
    <h2 class="mb-4">🎓 University Invoice</h2>
    <p><strong>Invoice No:</strong> <?= htmlspecialchars($invoice['invoice_no']) ?></p>
    <p><strong>Issued At:</strong> <?= htmlspecialchars($invoice['issued_at']) ?></p>
    <hr>
    <h4>Student Info</h4>
    <p><strong>Name:</strong> <?= htmlspecialchars($invoice['full_name']) ?><br>
       <strong>Student ID:</strong> <?= htmlspecialchars($invoice['student_id']) ?><br>
       <strong>Email:</strong> <?= htmlspecialchars($invoice['email']) ?><br>
       <strong>Phone:</strong> <?= htmlspecialchars($invoice['phone']) ?><br>
       <strong>Program:</strong> <?= htmlspecialchars($invoice['program']) ?><br>
       <strong>Batch:</strong> <?= htmlspecialchars($invoice['batch']) ?><br>
       <strong>Department:</strong> <?= htmlspecialchars($invoice['department']) ?></p>
    <hr>
    <h4>Payment Info</h4>
    <p><strong>Amount:</strong> <?= number_format($invoice['amount'],2) ?> ৳<br>
       <strong>Status:</strong> <span class="badge bg-<?= $invoice['status']=='paid'?'success':'warning' ?>">
            <?= htmlspecialchars($invoice['status']) ?>
       </span></p>
    <hr>
    <a href="javascript:window.print()" class="btn btn-primary">🖨 Print / Save PDF</a>
  </div>
</body>
</html>
