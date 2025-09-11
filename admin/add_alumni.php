<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";
require_once __DIR__ . "/../app/includes/image_helpers.php"; // if using thumbnails

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); exit;
}

$uploadDir = __DIR__ . "/../uploads/alumni/"; // physical path
$webUploadDir = "uploads/alumni/";             // web path stored in DB

$maxFileSize = 2 * 1024 * 1024; // 2MB
$allowedTypes = ['image/jpeg','image/png','image/gif'];

$editing = false;
$alumni = null;
if (isset($_GET['id'])) {
    $editing = true;
    $stmt = $DB_con->prepare("SELECT * FROM alumni WHERE alumni_id = :id LIMIT 1");
    $stmt->execute([':id' => intval($_GET['id'])]);
    $alumni = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$alumni) { die("Alumni not found."); }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect fields
    $full_name = trim($_POST['full_name'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $grad_year = !empty($_POST['grad_year']) ? intval($_POST['grad_year']) : null;
    $bio = trim($_POST['bio'] ?? '');

    // Basic validation
    $errors = [];
    if ($full_name === '') $errors[] = "Full name is required.";

    // Handle image upload (optional)
    $imagePath = $alumni['image_path'] ?? null;
    if (!empty($_FILES['image']['name'])) {
        $f = $_FILES['image'];
        if ($f['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Image upload error.";
        } elseif ($f['size'] > $maxFileSize) {
            $errors[] = "Image is too large (max 2MB).";
        } else {
            $info = getimagesize($f['tmp_name']);
            if (!$info || !in_array($info['mime'], $allowedTypes)) {
                $errors[] = "Invalid image type. Allowed: JPG, PNG, GIF.";
            } else {
                // create unique filename
                $ext = image_type_to_extension($info[2], false); // jpg, png, gif
                $filename = uniqid('al_') . '.' . $ext;
                $destination = $uploadDir . $filename;
                if (!move_uploaded_file($f['tmp_name'], $destination)) {
                    $errors[] = "Failed to move uploaded file.";
                } else {
                    // optional: generate thumbnail (overwrite original or create thumb)
                    // create_thumbnail($destination, $destination, 800, 600);
                    $imagePath = $webUploadDir . $filename;
                }
            }
        }
    }

    if (empty($errors)) {
        if ($editing) {
            $stmt = $DB_con->prepare("UPDATE alumni SET full_name=:fn, department=:dept, grad_year=:gy, bio=:bio, image_path=:img WHERE alumni_id=:id");
            $stmt->execute([
                ':fn' => $full_name, ':dept' => $department, ':gy' => $grad_year, ':bio' => $bio, ':img' => $imagePath, ':id' => $alumni['alumni_id']
            ]);
            header("Location: manage_alumni.php?msg=updated"); exit;
        } else {
            $stmt = $DB_con->prepare("INSERT INTO alumni (full_name, department, grad_year, bio, image_path) VALUES (:fn, :dept, :gy, :bio, :img)");
            $stmt->execute([
                ':fn' => $full_name, ':dept' => $department, ':gy' => $grad_year, ':bio' => $bio, ':img' => $imagePath
            ]);
            header("Location: manage_alumni.php?msg=added"); exit;
        }
    }
}
?>
<!-- HTML form (bootstrap) -->
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?php echo $editing ? 'Edit' : 'Add'; ?> Alumni</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h2><?php echo $editing ? 'Edit Alumni' : 'Add New Alumni'; ?></h2>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <?php foreach($errors as $e) echo "<div>".htmlspecialchars($e)."</div>"; ?>
    </div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Full Name</label>
      <input name="full_name" class="form-control" value="<?php echo htmlspecialchars($alumni['full_name'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Department</label>
      <input name="department" class="form-control" value="<?php echo htmlspecialchars($alumni['department'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Graduation Year</label>
      <input name="grad_year" type="number" min="1900" max="<?php echo date('Y'); ?>" class="form-control" value="<?php echo htmlspecialchars($alumni['grad_year'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Short Bio</label>
      <textarea name="bio" class="form-control" rows="4"><?php echo htmlspecialchars($alumni['bio'] ?? '') ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Photo (JPG/PNG/GIF, max 2MB)</label>
      <input type="file" name="image" accept="image/*" class="form-control">
      <?php if (!empty($alumni['image_path'])): ?>
        <div class="mt-2">
          <img src="<?php echo htmlspecialchars('../'.$alumni['image_path']); ?>" style="max-width:150px" alt="">
        </div>
      <?php endif; ?>
    </div>

    <button class="btn btn-primary"><?php echo $editing ? 'Update' : 'Add'; ?></button>
    <a href="manage_alumni.php" class="btn btn-secondary">Back</a>
  </form>
</div>
</body>
</html>
