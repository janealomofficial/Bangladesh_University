<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../login.php");
    exit;
}

$faculty_id = $_SESSION['faculty_id'];

// Fetch rooms & timeslots
$rooms = $DB_con->query("SELECT * FROM rooms ORDER BY room_name")->fetchAll(PDO::FETCH_ASSOC);
$timeslots = $DB_con->query("SELECT * FROM timeslots ORDER BY FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), start_time")->fetchAll(PDO::FETCH_ASSOC);

// Fetch schedule for this faculty
$stmt = $DB_con->prepare("
    SELECT s.room_id, s.timeslot_id, c.course_name, o.section, sem.name AS semester_name
    FROM schedule s
    JOIN course_offerings o ON s.offering_id=o.id
    JOIN courses c ON o.course_id=c.course_id
    JOIN semesters sem ON o.semester_id=sem.semester_id
    WHERE o.faculty_id=:f
");
$stmt->execute([':f' => $faculty_id]);
$schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

$scheduleMap = [];
foreach ($schedules as $row) {
    $scheduleMap[$row['room_id']][$row['timeslot_id']] = $row;
}

include 'faculty_header.php';
?>

<div class="container-fluid mt-4">
    <h2>📅 My Routine</h2>
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
                            <?php if (isset($scheduleMap[$r['id']][$t['id']])):
                                $item = $scheduleMap[$r['room_id']][$t['id']];
                            ?>
                                <strong><?= $item['course_name'] ?></strong><br>
                                <small><?= $item['semester_name'] ?> (Sec <?= $item['section'] ?>)</small>
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

<?php include 'faculty_footer.php'; ?>