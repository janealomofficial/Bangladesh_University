<?php
require_once __DIR__ . "/../app/config/db.php";

// Set headers for Excel file download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Revenue_Report.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Open output stream
$output = fopen("php://output", "w");

// Table headers
fputcsv($output, ["Source", "Amount", "Status", "Date"], "\t");

// ---------------- Admissions ----------------
$admissions = $DB_con->query("
    SELECT id, full_name, payment_status, created_at 
    FROM admissions
")->fetchAll(PDO::FETCH_ASSOC);

foreach ($admissions as $row) {
    fputcsv($output, [
        "Admission - " . $row['full_name'],
        "N/A",  // Admissions have no fee amount in your DB
        ucfirst($row['payment_status']),
        $row['created_at']
    ], "\t");
}

// ---------------- Semester Payments ----------------
$payments = $DB_con->query("
    SELECT p.payment_id, s.full_name, p.amount, p.status, p.paid_on 
    FROM payments p
    JOIN students s ON p.student_id = s.student_id
")->fetchAll(PDO::FETCH_ASSOC);

foreach ($payments as $row) {
    fputcsv($output, [
        "Semester - " . $row['full_name'],
        number_format($row['amount'], 2),
        ucfirst($row['status']),
        $row['paid_on'] ?? "-"
    ], "\t");
}

// Close output
fclose($output);
exit;
