<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

// Auth check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Fetch faculty
$stmt = $DB_con->query("SELECT * FROM faculty ORDER BY hire_date DESC");
$faculty_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'admin_header.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4">Manage Faculty</h2>
    <a href="add_faculty.php" class="btn btn-primary mb-3">+ Add Faculty</a>
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Designation</th>
                <th>Department</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Hire Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($faculty_list as $faculty): ?>
                <tr>
                    <td><?= $faculty['faculty_id']; ?></td>
                    <td><?= $faculty['full_name']; ?></td>
                    <td><?= $faculty['designation']; ?></td>
                    <td><?= $faculty['department']; ?></td>
                    <td><?= $faculty['contact']; ?></td>
                    <td><?= $faculty['address']; ?></td>
                    <td><?= $faculty['hire_date']; ?></td>
                    <td>
                        <a href="edit_faculty.php?id=<?= $faculty['faculty_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete_faculty.php?id=<?= $faculty['faculty_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'admin_footer.php'; ?>