<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $payment_id = $_GET['id'];

    $stmt = $DB_con->prepare("
        SELECT p.payment_id, s.full_name, sem.name AS semester, p.amount, p.paid_on 
        FROM payments p
        JOIN students s ON p.student_id = s.student_id
        JOIN semesters sem ON p.semester_id = sem.semester_id
        WHERE p.payment_id = :id
    ");
    $stmt->execute([':id' => $payment_id]);
    $receipt = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Payment Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        .receipt {
            border: 1px solid #333;
            padding: 20px;
            width: 500px;
            margin: auto;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="receipt">
        <h2>🎓 University Payment Receipt</h2>
        <p><strong>Student:</strong> <?= $receipt['full_name'] ?></p>
        <p><strong>Semester:</strong> <?= $receipt['semester'] ?></p>
        <p><strong>Amount Paid:</strong> <?= number_format($receipt['amount'], 2) ?> ৳</p>
        <p><strong>Paid On:</strong> <?= $receipt['paid_on'] ?></p>
        <hr>
        <p style="text-align:center">✅ Payment Successful</p>
    </div>
    <script>
        window.print();
    </script>
</body>

</html>