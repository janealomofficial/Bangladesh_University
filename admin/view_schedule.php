<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Fetch Rooms & Timeslots
$rooms = $DB_con->query("SELECT * FROM rooms ORDER BY room_name")->fetchAll(PDO::FETCH_ASSOC);
$timeslots = $DB_con->query("SELECT * FROM timeslots ORDER BY FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), start_time")->fetchAll(PDO::FETCH_ASSOC);

// Fetch Assigned Schedule with joins
$schedules = $DB_con->query("
    SELECT s.id, s.room_id, s.timeslot_id, 
           c.course_name, f.full_name, o.section, sem.name AS semester_name
    FROM schedule s
    JOIN course_offerings o ON s.offering_id=o.id
    JOIN courses c ON o.course_id=c.course_id
    JOIN faculty f ON o.faculty_id=f.faculty_id
    JOIN semesters sem ON o.semester_id=sem.semester_id
")->fetchAll(PDO::FETCH_ASSOC);

// Index by room+timeslot for quick lookup
$scheduleMap = [];
foreach ($schedules as $row) {
    $scheduleMap[$row['room_id']][$row['timeslot_id']] = $row;
}

include 'admin_header.php';
?>

<div class="container-fluid mt-4">
    <h2>📅 View Routine</h2>

    <table class="table table-bordered text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>Timeslot</th>
                <?php foreach ($rooms as $r): ?>
                    <th><?= $r['room_name'] ?><br><small>Cap: <?= $r['capacity'] ?></small></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($timeslots as $t): ?>
                <tr>
                    <td><strong><?= $t['day_of_week'] ?></strong><br><?= substr($t['start_time'], 0, 5) ?>-<?= substr($t['end_time'], 0, 5) ?></td>
                    <?php foreach ($rooms as $r): ?>
                        <td>
                            <?php if (isset($scheduleMap[$r['id']][$t['id']])):
                                $item = $scheduleMap[$r['id']][$t['id']];
                            ?>
                                <div class="card bg-light p-2">
                                    <strong><?= htmlspecialchars($item['course_name']) ?></strong><br>
                                    <small><?= htmlspecialchars($item['full_name']) ?></small><br>
                                    <span class="badge bg-info"><?= $item['semester_name'] ?> (<?= $item['section'] ?>)</span>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">--</span>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'admin_footer.php'; ?>