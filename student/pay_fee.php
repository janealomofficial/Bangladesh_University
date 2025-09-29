<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $payment_id = $_GET['id'];

    $stmt = $DB_con->prepare("UPDATE payments SET status='paid', paid_on=NOW() WHERE payment_id=:id");
    $stmt->execute([':id' => $payment_id]);

    header("Location: dashboard.php?msg=paid");
    exit;
}
