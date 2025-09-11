<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

// Auth check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Check if an event ID is provided
if (isset($_GET['id'])) {
    $event_id = intval($_GET['id']);

    // Fetch the event to get its image path before deletion
    $stmt = $DB_con->prepare("SELECT image_path FROM news_events WHERE event_id = :id LIMIT 1");
    $stmt->execute([':id' => $event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($event) {
        // Delete the news/event from the database
        $stmt = $DB_con->prepare("DELETE FROM news_events WHERE event_id = :id");
        $stmt->execute([':id' => $event_id]);

        // If an image exists, delete it from the filesystem
        if (!empty($event['image_path']) && file_exists(__DIR__ . "/../" . $event['image_path'])) {
            unlink(__DIR__ . "/../" . $event['image_path']);
        }

        // Redirect back to the manage page with a success message
        header("Location: manage_news_events.php?msg=deleted");
        exit;
    } else {
        // Event not found, redirect with error
        header("Location: manage_news_events.php?msg=error");
        exit;
    }
} else {
    // No ID provided, redirect to the manage page
    header("Location: manage_news_events.php?msg=error");
    exit;
}
