<?php
require_once __DIR__ . "/../app/config/db.php";
require_once __DIR__ . "/../fpdf/fpdf.php";

// Create PDF instance
$pdf = new FPDF();
$pdf->AddPage();

// Title
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'University Revenue Report', 0, 1, 'C');
$pdf->Ln(5);

// Date
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Generated on: " . date("Y-m-d H:i:s"), 0, 1);
$pdf->Ln(10);

// Table Header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, "Source", 1, 0, 'C');
$pdf->Cell(40, 10, "Amount", 1, 0, 'C');
$pdf->Cell(50, 10, "Status", 1, 0, 'C');
$pdf->Cell(50, 10, "Date", 1, 1, 'C');

// Use Arial for rows
$pdf->SetFont('Arial', '', 11);

// ---------------- Admissions ----------------
$admissions = $DB_con->query("
    SELECT id, full_name, payment_status, created_at 
    FROM admissions
")->fetchAll(PDO::FETCH_ASSOC);

foreach ($admissions as $row) {
    $pdf->Cell(50, 10, "Admission - " . $row['full_name'], 1);
    $pdf->Cell(40, 10, "N/A", 1, 0, 'C'); // No amount column in admissions table
    $pdf->Cell(50, 10, ucfirst($row['payment_status']), 1, 0, 'C');
    $pdf->Cell(50, 10, $row['created_at'], 1, 1, 'C');
}

// ---------------- Semester Payments ----------------
$payments = $DB_con->query("
    SELECT p.payment_id, s.full_name, p.amount, p.status, p.paid_on 
    FROM payments p
    JOIN students s ON p.student_id = s.student_id
")->fetchAll(PDO::FETCH_ASSOC);

foreach ($payments as $row) {
    $pdf->Cell(50, 10, "Semester - " . $row['full_name'], 1);
    $pdf->Cell(40, 10, number_format($row['amount'], 2), 1, 0, 'C');
    $pdf->Cell(50, 10, ucfirst($row['status']), 1, 0, 'C');
    $pdf->Cell(50, 10, $row['paid_on'] ?? "-", 1, 1, 'C');
}

// Output PDF
$pdf->Output("D", "Revenue_Report.pdf");
exit;
