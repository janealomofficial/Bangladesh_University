<?php
require_once __DIR__ . "/app/config/db.php";
require_once __DIR__ . "/includes/header.php";

// Fetch live news/events
$stmt = $DB_con->query("SELECT * FROM news_events WHERE is_live = 1 ORDER BY event_date DESC");
$news_events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html>

<head>
    <title>News and Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container py-5">
        <h1 class="mb-4">News and Events</h1>

        <div class="row">
            <?php foreach ($news_events as $ne): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php if (!empty($ne['image_path'])): ?>
                            <img src="<?= $ne['image_path']; ?>" class="card-img-top" alt="<?= htmlspecialchars($ne['title']); ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($ne['title']); ?></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($ne['description'], 0, 150)) . '...'; ?></p>
                            <a href="news_event_detail.php?id=<?= $ne['event_id']; ?>" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php require_once __DIR__ . "/includes/footer.php"; ?>
</body>

</html>