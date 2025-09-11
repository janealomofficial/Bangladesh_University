<?php
session_start();
require_once __DIR__ . "/app/config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']); // admin, faculty, student

    // Fetch user from DB
    $stmt = $DB_con->prepare("SELECT * FROM users WHERE email = :email AND role = :role AND status = 'active' LIMIT 1");
    $stmt->execute([
        ':email' => $email,
        ':role'  => $role
    ]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Store session data
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];

        // Redirect to role dashboard
        if ($user['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } elseif ($user['role'] === 'faculty') {
            header("Location: faculty/dashboard.php");
        } else {
            header("Location: student/dashboard.php");
        }
        exit;
    } else {
        echo "<p style='color:red;'>Invalid email, password, or role.</p>";
    }
}
?>
