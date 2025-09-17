<?php
require_once __DIR__ . "/app/config/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admission.php");
    exit;
}

$admission_id = (int)($_POST['admission_id'] ?? 0);
$amount = (float)($_POST['amount'] ?? 0);

if (!$admission_id) die("Invalid request.");

// Mark as paid and set payment reference
$payment_reference = 'PAY' . strtoupper(bin2hex(random_bytes(6)));

$upd = $DB_con->prepare("UPDATE admissions SET payment_status = 'paid', payment_reference = :pref WHERE id = :id");
$upd->execute([':pref' => $payment_reference, ':id' => $admission_id]);

// Create invoice number (simple format: INVYYYYMMDD-XXXX)
$datepart = date('Ymd');
$seq_stmt = $DB_con->prepare("SELECT COUNT(*) FROM admission_invoices WHERE DATE(issued_at) = CURDATE()");
$seq_stmt->execute();
$seq = (int)$seq_stmt->fetchColumn() + 1;
$invoice_no = 'INV' . $datepart . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);

// Insert invoice record
$ins = $DB_con->prepare("INSERT INTO admission_invoices (admission_id, invoice_no, amount, status) VALUES (:admission_id, :invoice_no, :amount, 'paid')");
$ins->execute([':admission_id' => $admission_id, ':invoice_no' => $invoice_no, ':amount' => $amount]);

$invoice_id = $DB_con->lastInsertId();

// Redirect to invoice view
header("Location: invoice.php?invoice_id=" . $invoice_id);
exit;
