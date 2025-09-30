<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); exit;
}

$semester_id = isset($_GET['semester_id']) ? (int)$_GET['semester_id'] : 0;
$course_id   = isset($_GET['course_id'])   ? (int)$_GET['course_id']   : 0;
if (!$semester_id || !$course_id) { die("Missing parameters."); }

$stmt = $DB_con->prepare("UPDATE results SET status='published', updated_at=NOW()
                          WHERE semester_id=:sem AND course_id=:cid");
$stmt->execute([':sem'=>$semester_id, ':cid'=>$course_id]);

header("Location: manage_results.php?semester_id=$semester_id&course_id=$course_id&published=1");
exit;
