<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

// Ensure student_id exists in session
if (!isset($_SESSION['student_id'])) {
    $stmt = $DB_con->prepare("SELECT student_id FROM students WHERE user_id = :uid LIMIT 1");
    $stmt->execute([':uid' => $_SESSION['user_id']]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        $_SESSION['student_id'] = $student['student_id'];
    } else {
        die("⚠ Student profile not linked. Please contact admin.");
    }
}

$student_id = $_SESSION['student_id'];

// Fetch the student's enrolled courses
$stmt = $DB_con->prepare("
    SELECT DISTINCT c.course_name, c.course_code,
           t.day_of_week, t.start_time, t.end_time,
           r.room_name, r.capacity,
           se.name AS semester_name,
           f.full_name AS faculty_name
    FROM enrollments e
    JOIN courses c ON e.course_id = c.course_id
    JOIN course_offerings co ON co.course_id = c.course_id
    JOIN schedule sc ON sc.offering_id = co.id
    JOIN semesters se ON co.semester_id = se.semester_id
    JOIN timeslots t ON sc.timeslot_id = t.id
    JOIN rooms r ON sc.room_id = r.id
    JOIN faculty f ON co.faculty_id = f.faculty_id
    WHERE e.student_id = :sid
    ORDER BY t.day_of_week, t.start_time
");
$stmt->execute([':sid' => $student_id]);
$schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'student_header.php';
?>

<div class="container mt-4">
    <h2>📅 My Class Routine</h2>

    <?php if (empty($schedules)): ?>
        <div class="alert alert-info">You are not enrolled in any scheduled classes yet.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Course</th>
                    <th>Faculty</th>
                    <th>Room</th>
                    <th>Semester</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedules as $s): ?>
                    <tr>
                        <td><?= htmlspecialchars($s['day_of_week']); ?></td>
                        <td><?= htmlspecialchars($s['start_time'] . " - " . $s['end_time']); ?></td>
                        <td><?= htmlspecialchars($s['course_name']); ?> (<?= $s['course_code']; ?>)</td>
                        <td><?= htmlspecialchars($s['faculty_name']); ?></td>
                        <td><?= htmlspecialchars($s['room_name']); ?> (Cap: <?= $s['capacity']; ?>)</td>
                        <td><?= htmlspecialchars($s['semester_name']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include 'student_footer.php'; ?>