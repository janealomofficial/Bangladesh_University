<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$student_id = $DB_con->prepare("SELECT student_id FROM students WHERE user_id=:u LIMIT 1");
$student_id->execute([':u' => $user_id]);
$sid = $student_id->fetchColumn();
if (!$sid) { die("Student profile not linked."); }

$stmt = $DB_con->prepare("
    SELECT r.marks, r.grade, r.status, c.course_name, c.course_code,
           sem.name AS semester_name
    FROM results r
    JOIN courses c   ON c.course_id = r.course_id
    JOIN semesters sem ON sem.semester_id = r.semester_id
    WHERE r.student_id = :sid
    ORDER BY sem.start_date DESC, c.course_name
");
$stmt->execute([':sid' => $sid]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'student_header.php';
?>
<div class="container-fluid mt-4">
    <h2>My Results</h2>
    <?php if (empty($rows)): ?>
        <div class="alert alert-info">No results available yet.</div>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Semester</th>
                    <th>Course</th>
                    <th>Marks</th>
                    <th>Grade</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['semester_name']) ?></td>
                    <td><?= htmlspecialchars($r['course_name']) ?> (<?= htmlspecialchars($r['course_code']) ?>)</td>
                    <td><?= htmlspecialchars($r['marks']) ?></td>
                    <td><?= htmlspecialchars($r['grade']) ?></td>
                    <td>
                        <?php if ($r['status']==='published'): ?>
                            <span class="badge bg-success">Published</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Draft</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php include 'student_footer.php'; ?>
