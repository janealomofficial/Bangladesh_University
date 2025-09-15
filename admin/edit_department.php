<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

// Auth check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: manage_departments.php");
    exit;
}

// Fetch department
$stmt = $DB_con->prepare("SELECT * FROM departments WHERE id = ?");
$stmt->execute([$id]);
$department = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$department) {
    header("Location: manage_departments.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $department_name = $_POST['department_name'];
    $department_head = $_POST['department_head'];
    $total_faculty = $_POST['total_faculty'];

    // Handle image upload (optional)
    $image_path = $department['image_path'];
    if (!empty($_FILES['image']['name'])) {
        $image_path = "uploads/" . time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . "/../" . $image_path);
    }

    $stmt = $DB_con->prepare("UPDATE departments 
                              SET department_name = :department_name, 
                                  department_head = :department_head, 
                                  total_faculty = :total_faculty, 
                                  image_path = :image_path
                              WHERE id = :id");
    $stmt->execute([
        ':department_name' => $department_name,
        ':department_head' => $department_head,
        ':total_faculty' => $total_faculty,
        ':image_path' => $image_path,
        ':id' => $id
    ]);

    header("Location: manage_departments.php?updated=1");
    exit;
}
?>

<?php include 'admin_header.php'; ?>

<div class="container-fluid p-4">
    <h2 class="mb-4">Edit Department</h2>

    <div class="card">
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Department Name</label>
                    <input type="text" name="department_name" class="form-control" value="<?= htmlspecialchars($department['department_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Department Head</label>
                    <input type="text" name="department_head" class="form-control" value="<?= htmlspecialchars($department['department_head']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Total Faculty</label>
                    <input type="number" name="total_faculty" class="form-control" value="<?= htmlspecialchars($department['total_faculty']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Current Image</label><br>
                    <?php if ($department['image_path']): ?>
                        <img src="../<?= $department['image_path']; ?>" width="100" class="rounded mb-2">
                    <?php endif; ?>
                    <input type="file" name="image" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Update Department</button>
                <a href="manage_departments.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>