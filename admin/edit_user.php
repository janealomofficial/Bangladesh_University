<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

// Only allow admin
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}

// Get user ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_users.php");
    exit;
}

$user_id = intval($_GET['id']);

// Fetch user
$stmt = $DB_con->prepare("SELECT * FROM users WHERE user_id = :id");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("❌ User not found.");
}

// Handle update form
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_user'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $status = $_POST['status'];

    $sql = "UPDATE users SET username = :username, email = :email, role = :role, status = :status";
    $params = [
        ':username' => $username,
        ':email' => $email,
        ':role' => $role,
        ':status' => $status,
        ':id' => $user_id
    ];

    // If new password entered
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql .= ", password = :password";
        $params[':password'] = $password;
    }

    $sql .= " WHERE user_id = :id";

    $stmt = $DB_con->prepare($sql);

    try {
        $stmt->execute($params);
        header("Location: manage_users.php?msg=updated");
        exit;
    } catch (PDOException $e) {
        $error = "Error updating user: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { margin: 0; }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #343a40;
            color: #fff;
            padding-top: 20px;
        }
        .sidebar a {
            display: block;
            color: #fff;
            padding: 12px 20px;
            text-decoration: none;
        }
        .sidebar a:hover { background: #495057; }
        .content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
            background: #f8f9fa;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center mb-4">Admin Panel</h4>
    <a href="dashboard.php"><i class="fa fa-home"></i> Dashboard</a>
    <a href="manage_users.php"><i class="fa fa-users"></i> Manage Users</a>
    <a href="../logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Content -->
<div class="content">
    <h2 class="mb-4">Edit User</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" class="card p-4">
        <input type="hidden" name="update_user" value="1">

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">New Password (leave blank to keep current)</label>
            <input type="password" name="password" class="form-control" placeholder="Enter new password">
        </div>

        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
                <option value="faculty" <?= $user['role'] === 'faculty' ? 'selected' : '' ?>>Faculty</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update User</button>
        <a href="manage_users.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
    </form>
</div>

</body>
</html>
