<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../login.php");
    exit;
}

// Ensure faculty_id exists in session
if (!isset($_SESSION['faculty_id'])) {
    // Auto-link: find faculty_id from users
    $stmt = $DB_con->prepare("SELECT faculty_id FROM faculty WHERE user_id = :uid LIMIT 1");
    $stmt->execute([':uid' => $_SESSION['user_id']]);
    $faculty = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($faculty) {
        $_SESSION['faculty_id'] = $faculty['faculty_id'];
    } else {
        die("Faculty profile not linked. Please contact admin.");
    }
}

$faculty_id = $_SESSION['faculty_id'];

// Fetch schedule for this faculty
$stmt = $DB_con->prepare("
    SELECT c.course_name, c.course_code,
           t.day_of_week, t.start_time, t.end_time,
           r.room_name, r.capacity,
           se.name AS semester_name
    FROM schedule sc
    JOIN course_offerings co ON sc.offering_id = co.id
    JOIN courses c ON co.course_id = c.course_id
    JOIN semesters se ON co.semester_id = se.semester_id
    JOIN timeslots t ON sc.timeslot_id = t.id
    JOIN rooms r ON sc.room_id = r.id
    WHERE co.faculty_id = :fid
    ORDER BY t.day_of_week, t.start_time
");
$stmt->execute([':fid' => $faculty_id]);
$schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'faculty_header.php';
?>

<div class="container mt-4">
    <h2>📅 My Class Routine</h2>

    <?php if (empty($schedules)): ?>
        <div class="alert alert-info">No classes assigned yet.</div>
    <?php else: ?>
        <table class="table table-bordered">
            <thead class="table-primary">
                <tr>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Course</th>
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
                        <td><?= htmlspecialchars($s['room_name']); ?> (Cap: <?= $s['capacity']; ?>)</td>
                        <td><?= htmlspecialchars($s['semester_name']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include 'faculty_footer.php'; ?>