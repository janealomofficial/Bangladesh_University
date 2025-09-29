<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle new enrollment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'], $_POST['course_id'], $_POST['semester_id'], $_POST['faculty_id'])) {
    $student_id = $_POST['student_id'];
    $course_id  = $_POST['course_id'];
    $semester_id = $_POST['semester_id'];
    $faculty_id = $_POST['faculty_id'];

    try {
        $stmt = $DB_con->prepare("INSERT INTO enrollments (student_id, course_id, semester_id, faculty_id, enrolled_on) 
                                  VALUES (:s, :c, :sem, :f, NOW())");
        $stmt->execute([':s' => $student_id, ':c' => $course_id, ':sem' => $semester_id, ':f' => $faculty_id]);
        $success = "Enrollment added successfully!";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $DB_con->prepare("DELETE FROM enrollments WHERE enrollment_id=:id")->execute([':id' => $id]);
    $success = "Enrollment removed!";
}

// Fetch dropdown data
$students = $DB_con->query("SELECT student_id, full_name FROM students ORDER BY full_name")->fetchAll(PDO::FETCH_ASSOC);
$courses  = $DB_con->query("SELECT course_id, course_name, course_code FROM courses ORDER BY course_name")->fetchAll(PDO::FETCH_ASSOC);
$semesters = $DB_con->query("SELECT semester_id, name FROM semesters ORDER BY start_date DESC")->fetchAll(PDO::FETCH_ASSOC);
$faculty  = $DB_con->query("SELECT faculty_id, full_name FROM faculty ORDER BY full_name")->fetchAll(PDO::FETCH_ASSOC);

// Fetch enrollments list
$enrollments = $DB_con->query("
    SELECT e.enrollment_id, s.full_name AS student, c.course_name, c.course_code,
           sem.name AS semester_name, f.full_name AS faculty_name, e.enrolled_on
    FROM enrollments e
    JOIN students s ON e.student_id = s.student_id
    JOIN courses c ON e.course_id   = c.course_id
    JOIN semesters sem ON e.semester_id = sem.semester_id
    LEFT JOIN faculty f ON e.faculty_id = f.faculty_id
    ORDER BY s.full_name
")->fetchAll(PDO::FETCH_ASSOC);

include 'admin_header.php';
?>

<div class="container-fluid mt-4">
    <h2>👩‍🎓 Manage Enrollments</h2>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Add Enrollment Form -->
    <div class="card mb-4">
        <div class="card-header">Assign Course to Student</div>
        <div class="card-body">
            <form method="post">
                <div class="row">
                    <!-- Student -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Student</label>
                        <select name="student_id" class="form-control" required>
                            <option value="">-- Select Student --</option>
                            <?php foreach ($students as $s): ?>
                                <option value="<?= $s['student_id'] ?>"><?= $s['full_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Course -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Course</label>
                        <select name="course_id" class="form-control" required>
                            <option value="">-- Select Course --</option>
                            <?php foreach ($courses as $c): ?>
                                <option value="<?= $c['course_id'] ?>"><?= $c['course_name'] ?> (<?= $c['course_code'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Semester -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Semester</label>
                        <select name="semester_id" class="form-control" required>
                            <option value="">-- Select Semester --</option>
                            <?php foreach ($semesters as $sem): ?>
                                <option value="<?= $sem['semester_id'] ?>"><?= $sem['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Faculty -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Faculty</label>
                        <select name="faculty_id" class="form-control" required>
                            <option value="">-- Select Faculty --</option>
                            <?php foreach ($faculty as $f): ?>
                                <option value="<?= $f['faculty_id'] ?>"><?= $f['full_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Assign</button>
            </form>
        </div>
    </div>

    <!-- Enrollment List -->
    <div class="card">
        <div class="card-header">All Enrollments</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Semester</th>
                        <th>Faculty</th>
                        <th>Enrolled On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enrollments as $e): ?>
                        <tr>
                            <td><?= htmlspecialchars($e['student']); ?></td>
                            <td><?= htmlspecialchars($e['course_name']); ?> (<?= $e['course_code']; ?>)</td>
                            <td><?= htmlspecialchars($e['semester_name']); ?></td>
                            <td><?= htmlspecialchars($e['faculty_name']); ?></td>
                            <td><?= htmlspecialchars($e['enrolled_on']); ?></td>
                            <td>
                                <a href="?delete=<?= $e['enrollment_id']; ?>" onclick="return confirm('Remove this enrollment?')" class="btn btn-sm btn-danger">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>