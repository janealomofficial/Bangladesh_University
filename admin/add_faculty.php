<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $designation = $_POST['designation'];
    $email = $_POST['email'] ?: null;
    $department = $_POST['department'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $hire_date = $_POST['hire_date'];

    try {
        $DB_con->beginTransaction();

        // 1. Create a user first (if your users table has columns username, password, role)
        $username = strtolower(str_replace(' ', '', $full_name));
        $password = password_hash("default123", PASSWORD_DEFAULT); // temporary password
        $role = 'faculty';

        $stmt = $DB_con->prepare("
            INSERT INTO users (username, password, role, email)
            VALUES (:username, :password, :role, :email)
        ");
        $stmt->execute([
            ':username' => $username,
            ':password' => $password,
            ':role' => $role,
            ':email' => $email
        ]);

        $new_user_id = $DB_con->lastInsertId();

        // 2. Insert into faculty table with the new user_id
        $stmt = $DB_con->prepare("
            INSERT INTO faculty (user_id, full_name, designation, email, department, contact, address, hire_date)
            VALUES (:user_id, :full_name, :designation, :email, :department, :contact, :address, :hire_date)
        ");
        $stmt->execute([
            ':user_id' => $new_user_id,
            ':full_name' => $full_name,
            ':designation' => $designation,
            ':email' => $email,
            ':department' => $department,
            ':contact' => $contact,
            ':address' => $address,
            ':hire_date' => $hire_date
        ]);

        $DB_con->commit();

        header("Location: manage_faculty.php?success=1");
        exit;
    } catch (PDOException $e) {
        $DB_con->rollBack();
        die("Database error: " . $e->getMessage());
    }
}
?>

<?php include 'admin_header.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4">Add Faculty</h2>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Designation</label>
            <input type="text" name="designation" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Department</label>
            <input type="text" name="department" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contact</label>
            <input type="text" name="contact" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="2"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Hire Date</label>
            <input type="date" name="hire_date" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Faculty</button>
    </form>
</div>

<?php include 'admin_footer.php'; ?>