<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ✅ Fetch rooms
$rooms = $DB_con->query("
    SELECT id AS room_id, room_name, capacity 
    FROM rooms 
    ORDER BY room_name
")->fetchAll(PDO::FETCH_ASSOC);

// ✅ Fetch timeslots
$timeslots = $DB_con->query("
    SELECT id AS timeslot_id, day_of_week, start_time, end_time 
    FROM timeslots 
    ORDER BY FIELD(day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'),
             start_time
")->fetchAll(PDO::FETCH_ASSOC);

// ✅ Fetch course offerings (linked to faculty + semester)
$offerings = $DB_con->query("
    SELECT o.id AS offering_id, c.course_name, c.course_code, f.full_name AS faculty_name, s.name AS semester_name
    FROM course_offerings o
    JOIN courses c ON o.course_id = c.course_id
    JOIN faculty f ON o.faculty_id = f.faculty_id
    JOIN semesters s ON o.semester_id = s.semester_id
    ORDER BY c.course_name
")->fetchAll(PDO::FETCH_ASSOC);

// ✅ Fetch existing schedule assignments
$schedule = $DB_con->query("
    SELECT sc.id, sc.room_id, sc.timeslot_id, 
           c.course_code, f.full_name AS faculty_name, s.name AS semester_name
    FROM schedule sc
    JOIN course_offerings o ON sc.offering_id = o.id
    JOIN courses c ON o.course_id = c.course_id
    JOIN faculty f ON o.faculty_id = f.faculty_id
    JOIN semesters s ON o.semester_id = s.semester_id
")->fetchAll(PDO::FETCH_ASSOC);

$schedule_map = [];
foreach ($schedule as $sch) {
    $schedule_map[$sch['timeslot_id']][$sch['room_id']] = $sch;
}

include 'admin_header.php';
?>

<div class="container-fluid mt-4">
    <h2>📅 Assign Routine</h2>

    <div class="row">
        <!-- Left: Course Offerings -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Course Offerings</div>
                <div class="card-body">
                    <?php if (count($offerings) === 0): ?>
                        <div class="alert alert-warning">
                            ⚠️ No course offerings available. Please create some first (Courses → Faculty → Semester).
                        </div>
                    <?php else: ?>
                        <?php foreach ($offerings as $o): ?>
                            <div class="border p-2 mb-2">
                                <strong><?= htmlspecialchars($o['course_name']); ?> (<?= $o['course_code']; ?>)</strong><br>
                                <?= htmlspecialchars($o['faculty_name']); ?> - <?= htmlspecialchars($o['semester_name']); ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right: Timetable -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Timetable</div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Timeslot</th>
                                <?php foreach ($rooms as $r): ?>
                                    <th><?= htmlspecialchars($r['room_name']); ?><br><small>Cap: <?= $r['capacity']; ?></small></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($timeslots as $t): ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars($t['day_of_week']); ?>
                                        <?= substr($t['start_time'], 0, 5); ?>-<?= substr($t['end_time'], 0, 5); ?>
                                    </td>
                                    <?php foreach ($rooms as $r): ?>
                                        <td>
                                            <?php if (isset($schedule_map[$t['timeslot_id']][$r['room_id']])):
                                                $sc = $schedule_map[$t['timeslot_id']][$r['room_id']]; ?>
                                                <div class="p-2 bg-light border">
                                                    <strong><?= htmlspecialchars($sc['course_code']); ?></strong><br>
                                                    <?= htmlspecialchars($sc['faculty_name']); ?><br>
                                                    <small><?= htmlspecialchars($sc['semester_name']); ?></small>
                                                </div>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-outline-primary assign-btn"
                                                    data-timeslot="<?= $t['timeslot_id']; ?>"
                                                    data-room="<?= $r['room_id']; ?>">+</button>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Assigning Offering -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="save_schedule.php" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Course Offering</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="timeslot_id" id="modal_timeslot">
                <input type="hidden" name="room_id" id="modal_room">
                <div class="mb-3">
                    <label class="form-label">Select Offering</label>
                    <select name="offering_id" class="form-select" required>
                        <option value="">-- Choose Offering --</option>
                        <?php foreach ($offerings as $o): ?>
                            <option value="<?= $o['offering_id']; ?>">
                                <?= $o['course_name']; ?> (<?= $o['course_code']; ?>) - <?= $o['faculty_name']; ?> [<?= $o['semester_name']; ?>]
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Save</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('.assign-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('modal_timeslot').value = this.dataset.timeslot;
            document.getElementById('modal_room').value = this.dataset.room;
            var myModal = new bootstrap.Modal(document.getElementById('assignModal'));
            myModal.show();
        });
    });
</script>

<?php include 'admin_footer.php'; ?>