<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

// Auth check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $DB_con->prepare("DELETE FROM departments WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: manage_departments.php?deleted=1");
exit;
