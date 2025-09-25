<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_timeslot'])) {
    $stmt = $DB_con->prepare("INSERT INTO timeslots (day_of_week,start_time,end_time) VALUES (:d,:s,:e)");
    $stmt->execute([':d' => $_POST['day_of_week'], ':s' => $_POST['start_time'], ':e' => $_POST['end_time']]);
    $success = "Timeslot added!";
}

// Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_timeslot'])) {
    $stmt = $DB_con->prepare("UPDATE timeslots SET day_of_week=:d,start_time=:s,end_time=:e WHERE id=:id");
    $stmt->execute([':d' => $_POST['day_of_week'], ':s' => $_POST['start_time'], ':e' => $_POST['end_time'], ':id' => $_POST['id']]);
    $success = "Timeslot updated!";
}

// Delete
if (isset($_GET['delete'])) {
    $stmt = $DB_con->prepare("DELETE FROM timeslots WHERE id=:id");
    $stmt->execute([':id' => $_GET['delete']]);
    $success = "Timeslot deleted!";
}

// Fetch
$timeslots = $DB_con->query("SELECT * FROM timeslots ORDER BY FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), start_time")->fetchAll(PDO::FETCH_ASSOC);

include 'admin_header.php';
?>

<div class="container mt-4">
    <h2>Manage Timeslots</h2>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

    <!-- Add Form -->
    <form method="POST" class="row g-3 mb-4">
        <input type="hidden" name="add_timeslot" value="1">
        <div class="col-md-3">
            <select name="day_of_week" class="form-control" required>
                <option value="">Day</option>
                <option>Monday</option>
                <option>Tuesday</option>
                <option>Wednesday</option>
                <option>Thursday</option>
                <option>Friday</option>
                <option>Saturday</option>
                <option>Sunday</option>
            </select>
        </div>
        <div class="col-md-3"><input type="time" name="start_time" class="form-control" required></div>
        <div class="col-md-3"><input type="time" name="end_time" class="form-control" required></div>
        <div class="col-md-2"><button class="btn btn-primary w-100">Add</button></div>
    </form>

    <!-- Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Day</th>
                <th>Start</th>
                <th>End</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($timeslots as $t): ?>
                <tr>
                    <td><?= $t['id'] ?></td>
                    <td><?= $t['day_of_week'] ?></td>
                    <td><?= $t['start_time'] ?></td>
                    <td><?= $t['end_time'] ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editSlot<?= $t['id'] ?>">Edit</button>
                        <a href="?delete=<?= $t['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete timeslot?');">Delete</a>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editSlot<?= $t['id'] ?>">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST">
                                <div class="modal-header">
                                    <h5>Edit Timeslot</h5>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                    <input type="hidden" name="edit_timeslot" value="1">
                                    <div class="mb-2"><label>Day</label>
                                        <select name="day_of_week" class="form-control">
                                            <option <?= $t['day_of_week'] == 'Monday' ? 'selected' : '' ?>>Monday</option>
                                            <option <?= $t['day_of_week'] == 'Tuesday' ? 'selected' : '' ?>>Tuesday</option>
                                            <option <?= $t['day_of_week'] == 'Wednesday' ? 'selected' : '' ?>>Wednesday</option>
                                            <option <?= $t['day_of_week'] == 'Thursday' ? 'selected' : '' ?>>Thursday</option>
                                            <option <?= $t['day_of_week'] == 'Friday' ? 'selected' : '' ?>>Friday</option>
                                            <option <?= $t['day_of_week'] == 'Saturday' ? 'selected' : '' ?>>Saturday</option>
                                            <option <?= $t['day_of_week'] == 'Sunday' ? 'selected' : '' ?>>Sunday</option>
                                        </select>
                                    </div>
                                    <div class="mb-2"><label>Start</label><input type="time" class="form-control" name="start_time" value="<?= $t['start_time'] ?>"></div>
                                    <div class="mb-2"><label>End</label><input type="time" class="form-control" name="end_time" value="<?= $t['end_time'] ?>"></div>
                                </div>
                                <div class="modal-footer"><button class="btn btn-success">Save</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'admin_footer.php'; ?>