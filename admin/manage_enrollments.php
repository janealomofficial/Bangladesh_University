<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle new enrollment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'], $_POST['offering_id'])) {
    $student_id = (int)$_POST['student_id'];
    $offering_id = (int)$_POST['offering_id'];

    try {
        // Fetch offering details
        $stmt = $DB_con->prepare("
            SELECT co.id, co.course_id, co.faculty_id, co.semester_id, co.section, co.year,
                   c.course_name, c.course_code, s.name AS semester_name
            FROM course_offerings co
            JOIN courses c   ON co.course_id = c.course_id
            JOIN semesters s ON co.semester_id = s.semester_id
            WHERE co.id = :oid
            LIMIT 1
        ");
        $stmt->execute([':oid' => $offering_id]);
        $off = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($off) {
            $stmt = $DB_con->prepare("
                INSERT INTO enrollments (student_id, course_id, offering_id, faculty_id, semester, enrolled_on)
                VALUES (:sid, :cid, :oid, :fid, :sem, NOW())
            ");
            $stmt->execute([
                ':sid' => $student_id,
                ':cid' => $off['course_id'],
                ':oid' => $offering_id,
                ':fid' => $off['faculty_id'],
                ':sem' => $off['semester_name']
            ]);
            $success = "Enrollment added successfully!";
        } else {
            $error = "Invalid course offering selected.";
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $DB_con->prepare("DELETE FROM enrollments WHERE enrollment_id=:id")->execute([':id' => $id]);
    $success = "Enrollment removed!";
}

// Fetch dropdown data
$students = $DB_con->query("SELECT student_id, full_name FROM students ORDER BY full_name")->fetchAll(PDO::FETCH_ASSOC);
$offerings = $DB_con->query("
    SELECT co.id, c.course_name, c.course_code, s.name AS semester_name, co.section, co.year, f.full_name AS faculty_name
    FROM course_offerings co
    JOIN courses c   ON co.course_id = c.course_id
    JOIN semesters s ON co.semester_id = s.semester_id
    LEFT JOIN faculty f ON co.faculty_id = f.faculty_id
    ORDER BY s.start_date DESC, c.course_name
")->fetchAll(PDO::FETCH_ASSOC);

// Fetch enrollments list
$enrollments = $DB_con->query("
    SELECT e.enrollment_id, s.full_name AS student, c.course_name, c.course_code,
           e.semester, f.full_name AS faculty_name, co.section, co.year, e.enrolled_on
    FROM enrollments e
    JOIN students s ON e.student_id = s.student_id
    JOIN courses c  ON e.course_id  = c.course_id
    LEFT JOIN course_offerings co ON e.offering_id = co.id
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
        <div class="card-header">Assign Course Offering to Student</div>
        <div class="card-body">
            <form method="post">
                <div class="row">
                    <!-- Student -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Student</label>
                        <select name="student_id" class="form-control" required>
                            <option value="">-- Select Student --</option>
                            <?php foreach ($students as $s): ?>
                                <option value="<?= $s['student_id'] ?>"><?= $s['full_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Offering -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Course Offering</label>
                        <select name="offering_id" class="form-control" required>
                            <option value="">-- Select Offering --</option>
                            <?php foreach ($offerings as $o): ?>
                                <option value="<?= $o['id'] ?>">
                                    <?= $o['course_name'] ?> (<?= $o['course_code'] ?>) —
                                    <?= $o['semester_name'] ?> —
                                    <?= $o['section'] ? "Sec: " . $o['section'] : "" ?> —
                                    <?= $o['year'] ?> —
                                    Faculty: <?= $o['faculty_name'] ?>
                                </option>
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
                        <th>Section</th>
                        <th>Year</th>
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
                            <td><?= htmlspecialchars($e['faculty_name']); ?></td>
                            <td><?= htmlspecialchars($e['section']); ?></td>
                            <td><?= htmlspecialchars($e['year']); ?></td>
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