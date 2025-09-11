<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle Add Course
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_course'])) {
    $course_name = $_POST['course_name'];
    $course_code = $_POST['course_code'];
    $department = $_POST['department'];

    $stmt = $DB_con->prepare("INSERT INTO courses (course_name, course_code, department) 
                              VALUES (:course_name, :course_code, :department)");
    $stmt->execute([
        ':course_name' => $course_name,
        ':course_code' => $course_code,
        ':department' => $department
    ]);
    header("Location: manage_courses.php?msg=added");
    exit;
}

// Handle Delete Course
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $DB_con->prepare("DELETE FROM courses WHERE course_id = :id");
    $stmt->execute([':id' => $id]);
    header("Location: manage_courses.php?msg=deleted");
    exit;
}

// Fetch Courses
$stmt = $DB_con->query("SELECT * FROM courses ORDER BY course_name");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'admin_header.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Courses</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">+ Add Course</button>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_GET['msg']) == 'added' ? 'Course added successfully!' : 'Course deleted successfully!'; ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Course Code</th>
                <th>Department</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?= $course['course_id']; ?></td>
                    <td><?= $course['course_name']; ?></td>
                    <td><?= $course['course_code']; ?></td>
                    <td><?= $course['department']; ?></td>
                    <td>
                        <a href="edit_course.php?id=<?= $course['course_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="manage_courses.php?delete=<?= $course['course_id']; ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Course Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addCourseModalLabel">Add New Course</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Course Name</label>
                        <input type="text" name="course_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Course Code</label>
                        <input type="text" name="course_code" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_course" class="btn btn-success">Save Course</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include 'admin_footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
