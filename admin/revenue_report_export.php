<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit("Unauthorized");
}

$type = $_GET['type'] ?? 'excel';
$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';

$whereAdmission = "WHERE status='paid'";
$whereSemester  = "WHERE status='paid'";
$params = [];

if ($from && $to) {
    $whereAdmission .= " AND paid_on BETWEEN :from AND :to";
    $whereSemester  .= " AND paid_on BETWEEN :from AND :to";
    $params = [':from' => $from, ':to' => $to];
}

// Fetch admission payments
$sqlAdm = "SELECT student_id, amount, status, paid_on FROM admission_invoices $whereAdmission";
$stmtAdm = $DB_con->prepare($sqlAdm);
$stmtAdm->execute($params);
$admissions = $stmtAdm->fetchAll(PDO::FETCH_ASSOC);

// Fetch semester payments
$sqlSem = "SELECT student_id, semester_id, amount, status, paid_on FROM payments $whereSemester";
$stmtSem = $DB_con->prepare($sqlSem);
$stmtSem->execute($params);
$semesters = $stmtSem->fetchAll(PDO::FETCH_ASSOC);

// === Export to Excel (CSV) ===
if ($type === 'excel') {
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=revenue_report.csv");
    $out = fopen("php://output", "w");

    fputcsv($out, ["Admission Payments"]);
    fputcsv($out, ["Student ID", "Amount", "Status", "Paid On"]);
    foreach ($admissions as $row) {
        fputcsv($out, $row);
    }

    fputcsv($out, []); // blank line
    fputcsv($out, ["Semester Payments"]);
    fputcsv($out, ["Student ID", "Semester ID", "Amount", "Status", "Paid On"]);
    foreach ($semesters as $row) {
        fputcsv($out, $row);
    }

    fclose($out);
    exit;
}

// === Export to PDF ===
require_once __DIR__ . "/../vendor/fpdf/fpdf.php"; // make sure you have FPDF installed

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont("Arial", "B", 16);
$pdf->Cell(0, 10, "Revenue Report", 0, 1, "C");

$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(0, 10, "Admission Payments", 0, 1);
$pdf->SetFont("Arial", "", 10);
foreach ($admissions as $row) {
    $pdf->Cell(50, 8, "Student: " . $row['student_id'], 0, 0);
    $pdf->Cell(40, 8, "Amount: " . $row['amount'], 0, 0);
    $pdf->Cell(30, 8, "Status: " . $row['status'], 0, 0);
    $pdf->Cell(50, 8, "Paid On: " . $row['paid_on'], 0, 1);
}

$pdf->Ln(5);
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(0, 10, "Semester Payments", 0, 1);
$pdf->SetFont("Arial", "", 10);
foreach ($semesters as $row) {
    $pdf->Cell(40, 8, "Student: " . $row['student_id'], 0, 0);
    $pdf->Cell(30, 8, "Semester: " . $row['semester_id'], 0, 0);
    $pdf->Cell(40, 8, "Amount: " . $row['amount'], 0, 0);
    $pdf->Cell(30, 8, "Status: " . $row['status'], 0, 0);
    $pdf->Cell(40, 8, "Paid On: " . $row['paid_on'], 0, 1);
}

$pdf->Output("D", "revenue_report.pdf");
exit;
