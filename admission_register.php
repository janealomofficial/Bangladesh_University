<?php
require_once __DIR__ . "/app/config/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admission.php");
    exit;
}

// Collect + simple validation
$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$department = trim($_POST['department'] ?? '');
$program = trim($_POST['program'] ?? '');
$batch = trim($_POST['batch'] ?? '');
$amount = (float)($_POST['amount'] ?? 0.0);

if (!$full_name || !$email || !$phone || !$address || !$department || !$program || !$batch) {
    die("Missing required fields. Please go back and fill all required fields.");
}

// --- Generate auto Student ID ---
$year = date('Y');
$prefix = 'BU' . $year . '-';

// find last student id for this year
$stmt = $DB_con->prepare("SELECT student_id FROM admissions WHERE student_id LIKE :like ORDER BY id DESC LIMIT 1");
$stmt->execute([':like' => $prefix . '%']);
$last = $stmt->fetchColumn();

if ($last) {
    $parts = explode('-', $last);
    $num = (int) end($parts);
    $num++;
} else {
    $num = 1;
}
$student_id = $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);

// Insert admission (payment_status = pending)
$ins = $DB_con->prepare("
    INSERT INTO admissions 
    (full_name, student_id, email, phone, address, program, batch, department, payment_status, created_at) 
    VALUES (:full_name, :student_id, :email, :phone, :address, :program, :batch, :department, 'pending', NOW())
");
$ins->execute([
    ':full_name' => $full_name,
    ':student_id' => $student_id,
    ':email' => $email,
    ':phone' => $phone,
    ':address' => $address,
    ':program' => $program,
    ':batch' => $batch,
    ':department' => $department
]);

$admission_id = $DB_con->lastInsertId();

// Redirect to payment page (we will simulate payment)
header("Location: payment.php?admission_id={$admission_id}&amount=" . urlencode(number_format($amount, 2, '.', '')));
exit;
