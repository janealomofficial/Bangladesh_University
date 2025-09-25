<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle new enrollment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'], $_POST['course_id'], $_POST['semester_id'])) {
    $student_id  = $_POST['student_id'];
    $course_id   = $_POST['course_id'];
    $semester_id = $_POST['semester_id'];

    try {
        $stmt = $DB_con->prepare("INSERT INTO enrollments (student_id, course_id, semester, enrolled_on) 
                                  VALUES (:s, :c, :sem, NOW())");
        $stmt->execute([
            ':s'   => $student_id,
            ':c'   => $course_id,
            ':sem' => $semester_id
        ]);
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
$students  = $DB_con->query("SELECT student_id, full_name FROM students ORDER BY full_name")->fetchAll(PDO::FETCH_ASSOC);
$courses   = $DB_con->query("SELECT course_id, course_name, course_code FROM courses ORDER BY course_name")->fetchAll(PDO::FETCH_ASSOC);
$semesters = $DB_con->query("SELECT semester_id, name FROM semesters ORDER BY start_date DESC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch enrollments list
$enrollments = $DB_con->query("
    SELECT e.enrollment_id, s.full_name AS student, c.course_name, c.course_code, se.name AS semester, e.enrolled_on
    FROM enrollments e
    JOIN students s ON e.student_id = s.student_id
    JOIN courses c  ON e.course_id   = c.course_id
    JOIN semesters se ON e.semester = se.semester_id
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
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Student</label>
                        <select name="student_id" class="form-control" required>
                            <option value="">-- Select Student --</option>
                            <?php foreach ($students as $s): ?>
                                <option value="<?= $s['student_id'] ?>"><?= htmlspecialchars($s['full_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Course</label>
                        <select name="course_id" class="form-control" required>
                            <option value="">-- Select Course --</option>
                            <?php foreach ($courses as $c): ?>
                                <option value="<?= $c['course_id'] ?>"><?= htmlspecialchars($c['course_name']) ?> (<?= $c['course_code'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Semester</label>
                        <select name="semester_id" class="form-control" required>
                            <option value="">-- Select Semester --</option>
                            <?php foreach ($semesters as $se): ?>
                                <option value="<?= $se['semester_id'] ?>"><?= htmlspecialchars($se['name']) ?></option>
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
                        <th>Enrolled On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enrollments as $e): ?>
                        <tr>
                            <td><?= htmlspecialchars($e['student']); ?></td>
                            <td><?= htmlspecialchars($e['course_name']); ?> (<?= $e['course_code']; ?>)</td>
                            <td><?= htmlspecialchars($e['semester']); ?></td>
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