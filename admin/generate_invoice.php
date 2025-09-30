<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$admission_id = $_GET['id'] ?? 0;
if (!$admission_id) {
    die("Invalid admission ID.");
}

// Fetch admission info
$stmt = $DB_con->prepare("SELECT full_name, student_id FROM admissions WHERE id = :aid LIMIT 1");
$stmt->execute([':aid' => $admission_id]);
$admission = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admission) {
    die("Admission not found.");
}

// Generate invoice number (unique)
$invoice_no = "INV" . date("Ymd") . "-" . str_pad($admission_id, 4, "0", STR_PAD_LEFT);

// Insert invoice if not already exists
$stmt = $DB_con->prepare("
    INSERT INTO admission_invoices (admission_id, invoice_no, amount, status, issued_at)
    VALUES (:aid, :ino, 5000, 'issued', NOW())
    ON DUPLICATE KEY UPDATE invoice_no = VALUES(invoice_no)
");
$stmt->execute([
    ':aid' => $admission_id,
    ':ino' => $invoice_no
]);

// Redirect to view invoice
$invoice_id = $DB_con->lastInsertId();
header("Location: invoice.php?invoice_id=" . $invoice_id);
exit;
?>
