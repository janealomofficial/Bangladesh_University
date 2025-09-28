<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

// Auth check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Fetch course offerings
$offerings = $DB_con->query("
    SELECT co.id AS offering_id, c.course_name, c.course_code, f.full_name AS faculty, s.name AS semester
    FROM course_offerings co
    JOIN courses c ON co.course_id = c.course_id
    JOIN faculty f ON co.faculty_id = f.faculty_id
    JOIN semesters s ON co.semester_id = s.semester_id
    ORDER BY c.course_name
")->fetchAll(PDO::FETCH_ASSOC);

// Fetch rooms
$rooms = $DB_con->query("SELECT id, room_name, capacity FROM rooms ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);

// Fetch timeslots
$timeslots = $DB_con->query("SELECT id, day_of_week, start_time, end_time FROM timeslots ORDER BY FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), start_time")->fetchAll(PDO::FETCH_ASSOC);

// Fetch existing schedule
$schedules = $DB_con->query("
    SELECT sc.id, sc.offering_id, sc.room_id, sc.timeslot_id,
           c.course_name, c.course_code, f.full_name AS faculty, s.name AS semester
    FROM schedule sc
    JOIN course_offerings co ON sc.offering_id = co.id
    JOIN courses c ON co.course_id = c.course_id
    JOIN faculty f ON co.faculty_id = f.faculty_id
    JOIN semesters s ON co.semester_id = s.semester_id
")->fetchAll(PDO::FETCH_ASSOC);

// Index schedule by [timeslot][room]
$scheduleMap = [];
foreach ($schedules as $sch) {
    $scheduleMap[$sch['timeslot_id']][$sch['room_id']] = $sch;
}

include 'admin_header.php';
?>

<div class="container-fluid mt-4">
    <h2><i class="bi bi-calendar3"></i> Assign Routine</h2>

    <div class="row">
        <!-- Left: Offerings -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Course Offerings</div>
                <div class="list-group list-group-flush">
                    <?php if (empty($offerings)): ?>
                        <div class="alert alert-warning m-2">No course offerings available. Please create offerings first.</div>
                    <?php else: ?>
                        <?php foreach ($offerings as $o): ?>
                            <div class="list-group-item" data-offering="<?= $o['offering_id'] ?>">
                                <strong><?= htmlspecialchars($o['course_name']) ?> (<?= $o['course_code'] ?>)</strong><br>
                                <?= htmlspecialchars($o['faculty']) ?><br>
                                <small><?= htmlspecialchars($o['semester']) ?></small>
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
                    <table class="table table-bordered text-center">
                        <thead>
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
                                    <td><?= $t['day_of_week'] ?> <?= date("H:i", strtotime($t['start_time'])) ?>-<?= date("H:i", strtotime($t['end_time'])) ?></td>
                                    <?php foreach ($rooms as $r): ?>
                                        <td>
                                            <?php if (isset($scheduleMap[$t['id']][$r['id']])):
                                                $s = $scheduleMap[$t['id']][$r['id']]; ?>
                                                <div class="p-2 bg-light border rounded">
                                                    <strong><?= htmlspecialchars($s['course_code']) ?></strong><br>
                                                    <?= htmlspecialchars($s['faculty']) ?><br>
                                                    <small><?= htmlspecialchars($s['semester']) ?></small>
                                                </div>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-outline-primary assign-btn"
                                                    data-room="<?= $r['id'] ?>"
                                                    data-timeslot="<?= $t['id'] ?>">+</button>
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

<!-- Modal -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="assignForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Course to Timeslot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="room_id" id="room_id">
                <input type="hidden" name="timeslot_id" id="timeslot_id">
                <div class="mb-3">
                    <label class="form-label">Course Offering</label>
                    <select name="offering_id" class="form-control" required>
                        <option value="">-- Select Offering --</option>
                        <?php foreach ($offerings as $o): ?>
                            <option value="<?= $o['offering_id'] ?>">
                                <?= $o['course_name'] ?> (<?= $o['course_code'] ?>) - <?= $o['faculty'] ?> - <?= $o['semester'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('.assign-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('room_id').value = this.dataset.room;
            document.getElementById('timeslot_id').value = this.dataset.timeslot;
            new bootstrap.Modal(document.getElementById('assignModal')).show();
        });
    });

    document.getElementById('assignForm').addEventListener('submit', function(e) {
        e.preventDefault();
        fetch('save_schedule.php', {
                method: 'POST',
                body: new FormData(this)
            }).then(res => res.json())
            .then(data => {
                if (data.success) location.reload();
                else alert(data.message);
            });
    });
</script>

<?php include 'admin_footer.php'; ?>