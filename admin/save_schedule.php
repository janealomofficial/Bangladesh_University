<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id     = $_POST['room_id'];
    $timeslot_id = $_POST['timeslot_id'];
    $offering_id = $_POST['offering_id'];

    try {
        // Conflict check: room already booked?
        $stmt = $DB_con->prepare("SELECT id FROM schedule WHERE room_id=:r AND timeslot_id=:t");
        $stmt->execute([':r' => $room_id, ':t' => $timeslot_id]);
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => '⚠ Room already assigned at this time.']);
            exit;
        }

        // Conflict check: faculty double-booked?
        $stmt = $DB_con->prepare("
            SELECT sc.id FROM schedule sc
            JOIN course_offerings o ON sc.offering_id = o.id
            WHERE o.faculty_id=(SELECT faculty_id FROM course_offerings WHERE id=:oid)
            AND sc.timeslot_id=:t
        ");
        $stmt->execute([':oid' => $offering_id, ':t' => $timeslot_id]);
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => '⚠ Faculty already has a class this timeslot.']);
            exit;
        }

        // Insert
        $stmt = $DB_con->prepare("INSERT INTO schedule (offering_id, room_id, timeslot_id, created_at) VALUES (:o,:r,:t,NOW())");
        $stmt->execute([':o' => $offering_id, ':r' => $room_id, ':t' => $timeslot_id]);

        echo json_encode(['success' => true, 'message' => '✅ Schedule saved!']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'DB Error: ' . $e->getMessage()]);
    }
}
