<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Fetch student details to edit
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];
    $stmt = $DB_con->prepare("SELECT * FROM students WHERE student_id = :id");
    $stmt->execute([':id' => $student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$student) {
        die("Student not found.");
    }
}

// Fetch semesters for dropdown
$sem_stmt = $DB_con->query("SELECT semester_id, semester_name FROM semesters");
$semesters = $sem_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch courses for dropdown (with existing assignments)
$course_stmt = $DB_con->query("SELECT course_id, course_name FROM courses");
$courses = $course_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch assigned courses to the student
$assigned_courses_stmt = $DB_con->prepare("SELECT course_id FROM student_courses WHERE student_id = :id");
$assigned_courses_stmt->execute([':id' => $student_id]);
$assigned_courses = array_column($assigned_courses_stmt->fetchAll(PDO::FETCH_ASSOC), 'course_id');

// Handle the form submission to update student details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_student'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $semester_id = $_POST['semester_id'];
    $assigned_courses = isset($_POST['courses']) ? $_POST['courses'] : [];

    // Update student details
    $update_stmt = $DB_con->prepare("UPDATE students SET full_name = :full_name, email = :email, contact = :contact, semester_id = :semester_id WHERE student_id = :id");
    $update_stmt->execute([
        ':full_name' => $full_name,
        ':email' => $email,
        ':contact' => $contact,
        ':semester_id' => $semester_id,
        ':id' => $student_id
    ]);

    // Remove existing course assignments and reassign the selected courses
    $DB_con->prepare("DELETE FROM student_courses WHERE student_id = :id")->execute([':id' => $student_id]);

    if (!empty($assigned_courses)) {
        $insert_stmt = $DB_con->prepare("INSERT INTO student_courses (student_id, course_id) VALUES (:student_id, :course_id)");
        foreach ($assigned_courses as $course_id) {
            $insert_stmt->execute([':student_id' => $student_id, ':course_id' => $course_id]);
        }
    }

    header("Location: manage_students.php?msg=updated");
    exit;
}
?>

<?php include 'admin_header.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Student Details</h2>
        <a href="manage_students.php" class="btn btn-secondary">Back to Student List</a>
    </div>

    <form method="POST" action="edit_student.php?id=<?= $student['student_id'] ?>">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Student Information</div>
            <div class="card-body">

                <!-- Full Name -->
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($student['full_name']) ?>" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($student['email'] ?? '') ?>" required>
                </div>

                <!-- Contact -->
                <div class="mb-3">
                    <label class="form-label">Contact</label>
                    <input type="text" name="contact" class="form-control" value="<?= htmlspecialchars($student['contact']) ?>">
                </div>

                <!-- Semester Dropdown -->
                <div class="mb-3">
                    <label class="form-label">Semester</label>
                    <select name="semester_id" class="form-select" required>
                        <option value="">-- Select Semester --</option>
                        <?php foreach ($semesters as $semester): ?>
                            <option value="<?= $semester['semester_id']; ?>"
                                <?= $semester['semester_id'] == $student['semester_id'] ? 'selected' : ''; ?>>
                                <?= $semester['semester_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Courses Checkbox -->
                <div class="mb-3">
                    <label class="form-label">Assign Courses</label>
                    <select name="courses[]" class="form-select" multiple>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['course_id']; ?>"
                                <?= in_array($course['course_id'], $assigned_courses) ? 'selected' : ''; ?>>
                                <?= $course['course_name']; ?> (<?= $course['course_id']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" name="update_student" class="btn btn-success">Save Changes</button>
            <a href="manage_students.php" class="btn btn-danger">Cancel</a>
        </div>
    </form>
</div>

<?php include 'admin_footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
