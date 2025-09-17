<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Faculty ID is missing.");
}

$faculty_id = $_GET['id'];

// Get user_id before deleting faculty
$stmt = $DB_con->prepare("SELECT user_id FROM faculty WHERE faculty_id = :id");
$stmt->execute([':id' => $faculty_id]);
$faculty = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$faculty) {
    die("Faculty not found.");
}

try {
    $DB_con->beginTransaction();

    // Delete faculty row
    $stmt = $DB_con->prepare("DELETE FROM faculty WHERE faculty_id = :id");
    $stmt->execute([':id' => $faculty_id]);

    // Optional: Delete the linked user as well
    if (!empty($faculty['user_id'])) {
        $stmt = $DB_con->prepare("DELETE FROM users WHERE user_id = :uid");
        $stmt->execute([':uid' => $faculty['user_id']]);
    }

    $DB_con->commit();

    header("Location: manage_faculty.php?deleted=1");
    exit;
} catch (PDOException $e) {
    $DB_con->rollBack();
    die("Database error: " . $e->getMessage());
}
