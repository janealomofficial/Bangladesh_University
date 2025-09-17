<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// --- FETCH FACULTY DATA ---
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Faculty ID is missing.");
}

$faculty_id = $_GET['id'];

$stmt = $DB_con->prepare("SELECT * FROM faculty WHERE faculty_id = :id");
$stmt->execute([':id' => $faculty_id]);
$faculty = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$faculty) {
    die("Faculty not found.");
}

// --- UPDATE FACULTY ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name  = $_POST['full_name'];
    $designation = $_POST['designation'];
    $email = $_POST['email'] ?: null;
    $department = $_POST['department'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $hire_date = $_POST['hire_date'];

    try {
        $stmt = $DB_con->prepare("
            UPDATE faculty
            SET full_name = :full_name,
                designation = :designation,
                email = :email,
                department = :department,
                contact = :contact,
                address = :address,
                hire_date = :hire_date
            WHERE faculty_id = :id
        ");
        $stmt->execute([
            ':full_name' => $full_name,
            ':designation' => $designation,
            ':email' => $email,
            ':department' => $department,
            ':contact' => $contact,
            ':address' => $address,
            ':hire_date' => $hire_date,
            ':id' => $faculty_id
        ]);

        header("Location: manage_faculty.php?updated=1");
        exit;
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>

<?php include 'admin_header.php'; ?>
<div class="container mt-4">
    <h2 class="mb-4">Edit Faculty</h2>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($faculty['full_name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Designation</label>
            <input type="text" name="designation" class="form-control" value="<?= htmlspecialchars($faculty['designation']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($faculty['email']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Department</label>
            <input type="text" name="department" class="form-control" value="<?= htmlspecialchars($faculty['department']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contact</label>
            <input type="text" name="contact" class="form-control" value="<?= htmlspecialchars($faculty['contact']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($faculty['address']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Hire Date</label>
            <input type="date" name="hire_date" class="form-control" value="<?= htmlspecialchars($faculty['hire_date']) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Faculty</button>
        <a href="manage_faculty.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php include 'admin_footer.php'; ?>