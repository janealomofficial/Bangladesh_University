<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$student_id = $DB_con->query("SELECT student_id FROM students WHERE user_id=$user_id")->fetchColumn();

$stmt = $DB_con->prepare("
    SELECT c.course_name, c.course_code, f.full_name AS faculty_name, sem.name AS semester_name, o.section, o.year
    FROM enrollments e
    JOIN courses c ON e.course_id = c.course_id
    JOIN course_offerings o ON o.course_id = c.course_id
    JOIN semesters sem ON o.semester_id = sem.semester_id
    JOIN faculty f ON o.faculty_id = f.faculty_id
    WHERE e.student_id = :sid
    ORDER BY o.year DESC, sem.start_date DESC
");
$stmt->execute([':sid' => $student_id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'student_header.php';
?>

<div class="container-fluid mt-4">
    <h2>📘 My Enrolled Courses</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Course Name</th>
                <th>Code</th>
                <th>Faculty</th>
                <th>Section</th>
                <th>Semester</th>
                <th>Year</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($courses) > 0): ?>
                <?php foreach ($courses as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['course_name']); ?></td>
                        <td><?= htmlspecialchars($c['course_code']); ?></td>
                        <td><?= htmlspecialchars($c['faculty_name']); ?></td>
                        <td><?= htmlspecialchars($c['section']); ?></td>
                        <td><?= htmlspecialchars($c['semester_name']); ?></td>
                        <td><?= htmlspecialchars($c['year']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">You are not enrolled in any courses yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'student_footer.php'; ?>