<?php
require_once __DIR__ . "/app/config/db.php";

// Check if an event ID is provided
if (isset($_GET['id'])) {
    $event_id = intval($_GET['id']);

    // Fetch the event details from the database
    $stmt = $DB_con->prepare("SELECT * FROM news_events WHERE event_id = :id LIMIT 1");
    $stmt->execute([':id' => $event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        header("Location: index.php?msg=event-not-found");
        exit;
    }
} else {
    header("Location: index.php?msg=event-not-found");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($event['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php include __DIR__ . "/app/includes/header.php"; ?>

    <div class="container py-5">
        <h1 class="mb-4"><?= htmlspecialchars($event['title']); ?></h1>

        <?php if (!empty($event['image_path'])): ?>
            <img src="<?= $event['image_path']; ?>" class="img-fluid mb-4" alt="<?= htmlspecialchars($event['title']); ?>">
        <?php endif; ?>

        <div class="mb-4">
            <h5>Event Description:</h5>
            <p><?= nl2br(htmlspecialchars($event['description'])); ?></p>
        </div>

        <div class="mb-4">
            <p><strong>Batch:</strong> <?= htmlspecialchars($event['batch']); ?></p>
            <p><strong>Current Position:</strong> <?= htmlspecialchars($event['current_position']); ?></p>
            <p><strong>Created On:</strong> <?= date('F j, Y', strtotime($event['created_at'])); ?></p>
        </div>

        <a href="index.php" class="btn btn-secondary">Back to News/Events</a>
    </div>

    <?php include __DIR__ . "/app/includes/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>