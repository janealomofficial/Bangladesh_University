<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $offering_id = $_POST['offering_id'];
    $room_id = $_POST['room_id'];
    $timeslot_id = $_POST['timeslot_id'];

    try {
        // Conflict: room + timeslot already used
        $stmt = $DB_con->prepare("SELECT COUNT(*) FROM schedule WHERE room_id=? AND timeslot_id=?");
        $stmt->execute([$room_id, $timeslot_id]);
        if ($stmt->fetchColumn() > 0) {
            echo json_encode(['success' => false, 'message' => 'This room is already booked for that timeslot.']);
            exit;
        }

        // Conflict: same offering in same timeslot
        $stmt = $DB_con->prepare("SELECT COUNT(*) FROM schedule WHERE offering_id=? AND timeslot_id=?");
        $stmt->execute([$offering_id, $timeslot_id]);
        if ($stmt->fetchColumn() > 0) {
            echo json_encode(['success' => false, 'message' => 'This course is already scheduled in this timeslot.']);
            exit;
        }

        // Insert new schedule
        $stmt = $DB_con->prepare("INSERT INTO schedule (offering_id, room_id, timeslot_id, created_at) VALUES (?,?,?,NOW())");
        $stmt->execute([$offering_id, $room_id, $timeslot_id]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
