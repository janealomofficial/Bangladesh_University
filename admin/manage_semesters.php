<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle Add Semester
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_semester'])) {
    $name = $_POST['name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $fee = $_POST['fee'];

    $stmt = $DB_con->prepare("INSERT INTO semesters (name, start_date, end_date, fee) 
                              VALUES (:name, :start_date, :end_date, :fee)");
    $stmt->execute([
        ':name' => $name,
        ':start_date' => $start_date,
        ':end_date' => $end_date,
        ':fee' => $fee
    ]);
    header("Location: manage_semesters.php?msg=added");
    exit;
}

// Handle Delete Semester
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $DB_con->prepare("DELETE FROM semesters WHERE semester_id = :id");
    $stmt->execute([':id' => $id]);
    header("Location: manage_semesters.php?msg=deleted");
    exit;
}

// Fetch Semesters
$stmt = $DB_con->query("SELECT * FROM semesters ORDER BY created_at DESC");
$semesters = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'admin_header.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Semesters</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSemesterModal">+ Add Semester</button>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_GET['msg']) == 'added' ? 'Semester added successfully!' : 'Semester deleted successfully!'; ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Semester ID</th>
                <th>Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Fee</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($semesters as $semester): ?>
                <tr>
                    <td><?= $semester['semester_id']; ?></td>
                    <td><?= $semester['name']; ?></td>
                    <td><?= $semester['start_date']; ?></td>
                    <td><?= $semester['end_date']; ?></td>
                    <td><?= $semester['fee']; ?></td>
                    <td>
                        <a href="edit_semester.php?id=<?= $semester['semester_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="manage_semesters.php?delete=<?= $semester['semester_id']; ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('Are you sure you want to delete this semester?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Semester Modal -->
<div class="modal fade" id="addSemesterModal" tabindex="-1" aria-labelledby="addSemesterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addSemesterModalLabel">Add New Semester</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Semester Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fee</label>
                        <input type="number" name="fee" class="form-control" step="0.01" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_semester" class="btn btn-success">Save Semester</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include 'admin_footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
