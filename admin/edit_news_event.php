<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

// Auth check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Fetch the news/event if an ID is provided
if (isset($_GET['id'])) {
    $event_id = intval($_GET['id']);
    $stmt = $DB_con->prepare("SELECT * FROM news_events WHERE event_id = :id");
    $stmt->execute([':id' => $event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        header("Location: manage_news_events.php?msg=error");
        exit;
    }
} else {
    header("Location: manage_news_events.php?msg=error");
    exit;
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $is_live = isset($_POST['is_live']) ? 1 : 0;
    $image_path = $event['image_path']; // Retain existing image

    // Handle image upload (optional)
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . "/../uploads/news_events/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $image_path = "uploads/news_events/" . time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . "/../" . $image_path);
    }

    // Update news/event record
    $stmt = $DB_con->prepare("UPDATE news_events SET title = :title, description = :description, image_path = :image_path, is_live = :is_live WHERE event_id = :event_id");
    $stmt->execute([
        ':title' => $title,
        ':description' => $description,
        ':image_path' => $image_path,
        ':is_live' => $is_live,
        ':event_id' => $event_id
    ]);

    // Redirect after successful update
    header("Location: manage_news_events.php?msg=updated");
    exit;
}
?>

<?php include 'admin_header.php'; ?>

<div class="container-fluid p-4">
    <h2 class="mb-4">Edit News/Event</h2>

    <!-- Edit News/Event Form -->
    <div class="card mb-4">
        <div class="card-header">Edit News/Event</div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($event['title']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" required><?= htmlspecialchars($event['description']); ?></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Event Image</label>
                        <input type="file" name="image" class="form-control">
                        <?php if (!empty($event['image_path'])): ?>
                            <img src="../<?= $event['image_path']; ?>" width="100" class="mt-2">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Is Live</label>
                        <input type="checkbox" name="is_live" class="form-check-input" <?= $event['is_live'] ? 'checked' : ''; ?>>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Update News/Event</button>
            </form>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>
