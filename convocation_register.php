<?php
require_once __DIR__ . "/app/config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name  = $_POST['full_name'];
    $student_id = $_POST['student_id'];
    $email      = $_POST['email'];
    $phone      = $_POST['phone'];
    $department = $_POST['department'];
    $session    = $_POST['session'];
    $program    = $_POST['program'];
    $batch      = $_POST['batch'];

    try {
        $stmt = $DB_con->prepare("INSERT INTO convocation_registrations 
        (full_name, student_id, email, phone, department, session, program, batch, status, created_at)
        VALUES (:full_name, :student_id, :email, :phone, :department, :session, :program, :batch, 'pending', NOW())");

        $stmt->execute([
            ':full_name'  => $full_name,
            ':student_id' => $student_id,
            ':email'      => $email,
            ':phone'      => $phone,
            ':department' => $department,
            ':session'    => $session,
            ':program'    => $program,
            ':batch'      => $batch
        ]);

        header("Location: convocation.php?success=1");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: convocation.php");
    exit;
}
