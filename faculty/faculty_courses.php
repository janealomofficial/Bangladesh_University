<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../login.php");
    exit;
}

$faculty_id = $_SESSION['faculty_id'];

$stmt = $DB_con->prepare("
    SELECT c.course_name, c.course_code, o.section, sem.name AS semester_name, o.year
    FROM course_offerings o
    JOIN courses c ON o.course_id = c.course_id
    JOIN semesters sem ON o.semester_id = sem.semester_id
    WHERE o.faculty_id = :f
    ORDER BY o.year DESC, sem.start_date DESC
");
$stmt->execute([':f' => $faculty_id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'faculty_header.php';
?>

<div class="container-fluid mt-4">
    <h2>📚 My Assigned Courses</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Course Name</th>
                <th>Code</th>
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
                        <td><?= htmlspecialchars($c['section']); ?></td>
                        <td><?= htmlspecialchars($c['semester_name']); ?></td>
                        <td><?= htmlspecialchars($c['year']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No courses assigned.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'faculty_footer.php'; ?>