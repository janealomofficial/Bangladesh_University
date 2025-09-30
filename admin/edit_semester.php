<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Get semester ID
$id = $_GET['id'] ?? 0;
if (!$id) {
    die("Invalid Semester ID");
}

// Fetch semester details
$stmt = $DB_con->prepare("SELECT * FROM semesters WHERE semester_id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$semester = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$semester) {
    die("Semester not found.");
}

// Handle form update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = trim($_POST['name']);
    $start_date = $_POST['start_date'];
    $end_date   = $_POST['end_date'];
    $fee        = (float)$_POST['fee'];

    try {
        $update = $DB_con->prepare("
            UPDATE semesters
            SET name = :name,
                start_date = :start_date,
                end_date = :end_date,
                fee = :fee
            WHERE semester_id = :id
        ");
        $update->execute([
            ':name'       => $name,
            ':start_date' => $start_date,
            ':end_date'   => $end_date,
            ':fee'        => $fee,
            ':id'         => $id
        ]);

        header("Location: manage_semesters.php?updated=1");
        exit;
    } catch (PDOException $e) {
        $error = "Update failed: " . $e->getMessage();
    }
}

include 'admin_header.php';
?>

<div class="container mt-4">
    <h2>Edit Semester</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Semester Name</label>
            <input type="text" name="name" class="form-control" 
                   value="<?= htmlspecialchars($semester['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" 
                   value="<?= htmlspecialchars($semester['start_date']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control" 
                   value="<?= htmlspecialchars($semester['end_date']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Fee (BDT)</label>
            <input type="number" step="0.01" name="fee" class="form-control" 
                   value="<?= htmlspecialchars($semester['fee']) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Semester</button>
        <a href="manage_semesters.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include 'admin_footer.php'; ?>
