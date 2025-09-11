<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}

// Fetch Students
$stmt = $DB_con->query("
    SELECT s.student_id, s.full_name, s.contact, u.username, u.email, sem.semester_name
    FROM students s
    JOIN users u ON s.user_id = u.user_id
    LEFT JOIN semesters sem ON s.semester_id = sem.semester_id
    ORDER BY s.student_id DESC
");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Users for Dropdown (To add student)
$user_stmt = $DB_con->query("SELECT user_id, username FROM users WHERE role = 'student'");
$users = $user_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Semesters for Dropdown
$sem_stmt = $DB_con->query("SELECT semester_id, semester_name FROM semesters");
$semesters = $sem_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'admin_header.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Students</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">+ Add Student</button>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_GET['msg']) == 'added' ? 'Student added successfully!' : 'Student deleted successfully!'; ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Student ID</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Semester</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= $student['student_id']; ?></td>
                    <td><?= $student['full_name']; ?></td>
                    <td><?= $student['username']; ?></td>
                    <td><?= $student['email']; ?></td>
                    <td><?= $student['contact']; ?></td>
                    <td><?= $student['semester_name'] ?? 'N/A'; ?></td>
                    <td>
                        <a href="edit_student.php?id=<?= $student['student_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="manage_students.php?delete=<?= $student['student_id']; ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="manage_students.php">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addStudentModalLabel">Add New Student</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">User (Registered)</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Select User --</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['user_id']; ?>"><?= $user['username']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contact</label>
                        <input type="text" name="contact" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <select name="semester_id" class="form-select">
                            <option value="">-- Select Semester --</option>
                            <?php foreach ($semesters as $semester): ?>
                                <option value="<?= $semester['semester_id']; ?>"><?= $semester['semester_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_student" class="btn btn-success">Save Student</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include 'admin_footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
