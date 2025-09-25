<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Fetch rooms
$rooms = $DB_con->query("SELECT id, room_name, capacity FROM rooms ORDER BY room_name")->fetchAll(PDO::FETCH_ASSOC);

// Fetch timeslots
$timeslots = $DB_con->query("
    SELECT id, day_of_week, start_time, end_time 
    FROM timeslots 
    ORDER BY FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday'), start_time
")->fetchAll(PDO::FETCH_ASSOC);

// Fetch offerings for dropdown
$offerings = $DB_con->query("
    SELECT co.id, c.course_name, c.course_code, f.full_name, se.name AS semester, se.start_date, se.end_date
    FROM course_offerings co
    JOIN courses c ON co.course_id = c.course_id
    JOIN faculty f ON co.faculty_id = f.faculty_id
    JOIN semesters se ON co.semester_id = se.semester_id
")->fetchAll(PDO::FETCH_ASSOC);

// Fetch existing schedule entries
$schedules = $DB_con->query("
    SELECT sc.id, sc.room_id, sc.timeslot_id,
           c.course_name, c.course_code,
           f.full_name AS faculty_name,
           se.name AS semester
    FROM schedule sc
    JOIN course_offerings co ON sc.offering_id = co.id
    JOIN courses c ON co.course_id = c.course_id
    JOIN faculty f ON co.faculty_id = f.faculty_id
    JOIN semesters se ON co.semester_id = se.semester_id
")->fetchAll(PDO::FETCH_ASSOC);

// Map schedule to [room][timeslot]
$scheduleMap = [];
foreach ($schedules as $s) {
    $scheduleMap[$s['room_id']][$s['timeslot_id']] =
        $s['course_name'] . " (" . $s['course_code'] . ")<br><small>" . $s['faculty_name'] . " - " . $s['semester'] . "</small>";
}

include 'admin_header.php';
?>

<div class="container-fluid mt-4">
    <h2>📅 Assign Routine</h2>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th>Timeslot</th>
                        <?php foreach ($rooms as $room): ?>
                            <th><?= htmlspecialchars($room['room_name']); ?><br><small>Cap: <?= $room['capacity']; ?></small></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($timeslots as $ts): ?>
                        <tr>
                            <td><?= $ts['day_of_week'] . " " . $ts['start_time'] . "-" . $ts['end_time']; ?></td>
                            <?php foreach ($rooms as $room): ?>
                                <td>
                                    <?php if (isset($scheduleMap[$room['id']][$ts['id']])): ?>
                                        <div class="p-1 bg-light rounded">
                                            <?= $scheduleMap[$room['id']][$ts['id']]; ?>
                                        </div>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-sm btn-light assign-btn"
                                            data-room="<?= $room['id'] ?>"
                                            data-timeslot="<?= $ts['id'] ?>">+</button>
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

<!-- Assign Routine Modal -->
<div class="modal fade" id="assignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Course to Timeslot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="assignForm">
                    <input type="hidden" name="room_id" id="room_id">
                    <input type="hidden" name="timeslot_id" id="timeslot_id">

                    <div class="mb-3">
                        <label class="form-label">Select Offering</label>
                        <select name="offering_id" id="offering_id" class="form-control" required>
                            <option value="">-- Choose --</option>
                            <?php foreach ($offerings as $o): ?>
                                <option value="<?= $o['id'] ?>">
                                    <?= $o['course_name'] ?> (<?= $o['course_code'] ?>) - <?= $o['full_name'] ?> [<?= $o['semester'] ?> <?= $o['start_date'] ?> - <?= $o['end_date'] ?>]
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll(".assign-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            let roomId = this.dataset.room;
            let timeslotId = this.dataset.timeslot;
            document.getElementById("room_id").value = roomId;
            document.getElementById("timeslot_id").value = timeslotId;

            var assignModal = new bootstrap.Modal(document.getElementById("assignModal"));
            assignModal.show();
        });
    });

    document.getElementById("assignForm").addEventListener("submit", function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch("save_schedule.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("Schedule saved successfully!");
                    location.reload();
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(err => alert("Request failed"));
    });
</script>

<?php include 'admin_footer.php'; ?>