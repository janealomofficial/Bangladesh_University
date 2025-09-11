<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

// Auth check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle new news/event submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $is_live = isset($_POST['is_live']) ? 1 : 0;

    // Handle image upload
    $image_path = null;
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . "/../uploads/news_events/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // create folder if it doesn’t exist
        }
        $image_path = "uploads/news_events/" . time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . "/../" . $image_path);
    }

    $stmt = $DB_con->prepare("INSERT INTO news_events (title, description, image_path, is_live) 
                              VALUES (:title, :description, :image_path, :is_live)");
    $stmt->execute([
        ':title' => $title,
        ':description' => $description,
        ':image_path' => $image_path,
        ':is_live' => $is_live
    ]);

    $success = "News/Event added successfully!";
}

// Fetch all news/events
$news_events_stmt = $DB_con->query("SELECT * FROM news_events ORDER BY event_date DESC");
$news_events = $news_events_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'admin_header.php'; ?>

<div class="container-fluid p-4">
    <h2 class="mb-4">Manage News and Events</h2>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success; ?></div>
    <?php endif; ?>

    <!-- Add News/Event Form -->
    <div class="card mb-4">
        <div class="card-header">Add News/Event</div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Event Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Is Live</label>
                        <input type="checkbox" name="is_live" class="form-check-input">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Add News/Event</button>
            </form>
        </div>
    </div>

    <!-- News/Event List -->
    <div class="card">
        <div class="card-header">News/Event List</div>
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Is Live</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($news_events as $ne): ?>
                        <tr>
                            <td>
                                <?php if (!empty($ne['image_path'])): ?>
                                    <img src="../<?= htmlspecialchars($ne['image_path']); ?>" width="60" class="rounded">
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($ne['title']); ?></td>
                            <td><?= htmlspecialchars(substr($ne['description'], 0, 100)) . '...'; ?></td>
                            <td>
                                <?= $ne['is_live'] ? 'Yes' : 'No'; ?>
                            </td>
                            <td>
                                <a href="edit_news_event.php?id=<?= $ne['event_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="delete_news_event.php?id=<?= $ne['event_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>
