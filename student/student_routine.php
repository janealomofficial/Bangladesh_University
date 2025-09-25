<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$student_id = $DB_con->query("SELECT student_id FROM students WHERE user_id=$user_id")->fetchColumn();

$rooms = $DB_con->query("SELECT * FROM rooms ORDER BY room_name")->fetchAll(PDO::FETCH_ASSOC);
$timeslots = $DB_con->query("SELECT * FROM timeslots ORDER BY FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), start_time")->fetchAll(PDO::FETCH_ASSOC);

$stmt = $DB_con->prepare("
    SELECT s.room_id, s.timeslot_id, c.course_name, f.full_name, o.section, sem.name AS semester_name
    FROM schedule s
    JOIN course_offerings o ON s.offering_id=o.id
    JOIN courses c ON o.course_id=c.course_id
    JOIN faculty f ON o.faculty_id=f.faculty_id
    JOIN semesters sem ON o.semester_id=sem.semester_id
    JOIN enrollments e ON e.course_id=c.course_id
    WHERE e.student_id=:sid
");
$stmt->execute([':sid' => $student_id]);
$schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

$scheduleMap = [];
foreach ($schedules as $row) {
    $scheduleMap[$row['room_id']][$row['timeslot_id']] = $row;
}

include 'student_header.php';
?>

<div class="container-fluid mt-4">
    <h2>📅 My Class Routine</h2>
    <table class="table table-bordered text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>Timeslot</th>
                <?php foreach ($rooms as $r): ?>
                    <th><?= $r['room_name'] ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($timeslots as $t): ?>
                <tr>
                    <td><?= $t['day_of_week'] ?><br><?= substr($t['start_time'], 0, 5) ?>-<?= substr($t['end_time'], 0, 5) ?></td>
                    <?php foreach ($rooms as $r): ?>
                        <td>
                            <?php if (isset($scheduleMap[$r['room_id']][$t['id']])):
                                $item = $scheduleMap[$r['room_id']][$t['id']];
                            ?>
                                <strong><?= $item['course_name'] ?></strong><br>
                                <small><?= $item['full_name'] ?> | <?= $item['semester_name'] ?> (Sec <?= $item['section'] ?>)</small>
                            <?php else: ?>
                                --
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'student_footer.php'; ?>