<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id     = $_POST['room_id'];
    $timeslot_id = $_POST['timeslot_id'];
    $offering_id = $_POST['offering_id'];

    try {
        // Conflict check
        $check = $DB_con->prepare("SELECT * FROM schedule 
            WHERE (room_id=:r AND timeslot_id=:t) 
               OR (offering_id=:o AND timeslot_id=:t)");
        $check->execute([':r' => $room_id, ':t' => $timeslot_id, ':o' => $offering_id]);

        if ($check->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'Conflict: Room or faculty already booked in this slot.']);
            exit;
        }

        $stmt = $DB_con->prepare("INSERT INTO schedule (offering_id, room_id, timeslot_id) 
                                  VALUES (:o,:r,:t)");
        $stmt->execute([':o' => $offering_id, ':r' => $room_id, ':t' => $timeslot_id]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
