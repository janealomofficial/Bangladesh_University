<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../login.php");
    exit;
}

function letter_from_marks($m) {
    if ($m === '' || $m === null) return '';
    $m = floatval($m);
    if ($m >= 80) return 'A+';
    if ($m >= 75) return 'A';
    if ($m >= 70) return 'A-';
    if ($m >= 65) return 'B+';
    if ($m >= 60) return 'B';
    if ($m >= 55) return 'B-';
    if ($m >= 50) return 'C+';
    if ($m >= 45) return 'C';
    if ($m >= 40) return 'D';
    return 'F';
}

$offering_id = isset($_POST['offering_id']) ? (int)$_POST['offering_id'] : 0;
if (!$offering_id) { die("Offering required."); }

// Validate offering belongs to the logged-in faculty
$user_id = $_SESSION['user_id'];
$fid_stmt = $DB_con->prepare("SELECT faculty_id FROM faculty WHERE user_id=:u LIMIT 1");
$fid_stmt->execute([':u'=>$user_id]);
$fid = $fid_stmt->fetchColumn();
if (!$fid) { die("Faculty profile not linked."); }

$off = $DB_con->prepare("
    SELECT course_id, semester_id, faculty_id
    FROM course_offerings
    WHERE id=:oid LIMIT 1
");
$off->execute([':oid'=>$offering_id]);
$o = $off->fetch(PDO::FETCH_ASSOC);
if (!$o || (int)$o['faculty_id'] !== (int)$fid) { die("Not your offering."); }

$course_id   = (int)$o['course_id'];
$semester_id = (int)$o['semester_id'];

$marks = $_POST['marks'] ?? [];
$grades = $_POST['grade'] ?? [];

// Upsert all (skip already-published rows)
$ins = $DB_con->prepare("
  INSERT INTO results (student_id, course_id, semester_id, marks, grade, status)
  VALUES (:sid,:cid,:sem,:m,:g,'draft')
  ON DUPLICATE KEY UPDATE
    marks  = IF(status='published', marks, VALUES(marks)),
    grade  = IF(status='published', grade, VALUES(grade)),
    updated_at = NOW()
");

$affected = 0;
foreach ($marks as $sid => $m) {
    $sid = (int)$sid;
    $m   = ($m === '' ? null : $m);
    $g   = trim($grades[$sid] ?? '');

    if ($m !== null && $g === '') {
        $g = letter_from_marks($m);
    }
    // allow saving empty rows? skip if both empty
    if ($m === null && $g === '') continue;

    $ins->execute([
        ':sid' => $sid,
        ':cid' => $course_id,
        ':sem' => $semester_id,
        ':m'   => $m,
        ':g'   => $g
    ]);
    $affected += $ins->rowCount();
}

header("Location: manage_results.php?offering_id=".$offering_id."&saved=1");
exit;
