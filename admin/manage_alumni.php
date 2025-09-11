<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

// Auth check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle new alumni submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['full_name'];
    $batch = $_POST['batch'];
    $position = $_POST['current_position'];
    $achievements = $_POST['achievements'];

    // Handle photo upload
    $image_path = null;
    if (!empty($_FILES['photo']['name'])) {
        $uploadDir = __DIR__ . "/../uploads/alumni/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // create folder if it doesn’t exist
        }
        $image_path = "uploads/alumni/" . time() . "_" . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . "/../" . $image_path);
    }

    $stmt = $DB_con->prepare("INSERT INTO alumni (full_name, batch, current_position, achievements, image_path) 
                              VALUES (:name, :batch, :position, :achievements, :image_path)");
    $stmt->execute([
        ':name' => $name,
        ':batch' => $batch,
        ':position' => $position,
        ':achievements' => $achievements,
        ':image_path' => $image_path
    ]);

    // Redirect to refresh the page and show the updated list
    header("Location: manage_alumni.php");
    exit;
}

// Fetch all alumni
$alumni_stmt = $DB_con->query("SELECT * FROM alumni ORDER BY created_at DESC");
$alumni = $alumni_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'admin_header.php'; ?> 

<div class="container-fluid p-4">
    <h2 class="mb-4">Manage Alumni</h2>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success; ?></div>
    <?php endif; ?>

    <!-- Add Alumni Form -->
    <div class="card mb-4">
        <div class="card-header">Add Alumni</div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Batch</label>
                        <input type="text" name="batch" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Current Position</label>
                        <input type="text" name="current_position" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Photo</label>
                        <input type="file" name="photo" class="form-control">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Achievements</label>
                        <textarea name="achievements" class="form-control"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Add Alumni</button>
            </form>
        </div>
    </div>

    <!-- Alumni List -->
    <div class="card">
        <div class="card-header">Alumni List</div>
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Batch</th>
                        <th>Position</th>
                        <th>Achievements</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alumni as $a): ?>
                        <tr>
                            <td>
                                <?php if (!empty($a['image_path'])): ?>
                                    <img src="../<?= htmlspecialchars($a['image_path']); ?>" width="60" class="rounded">
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($a['full_name']); ?></td>
                            <td><?= htmlspecialchars($a['batch']); ?></td>
                            <td><?= htmlspecialchars($a['current_position']); ?></td>
                            <td><?= htmlspecialchars($a['achievements']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>
