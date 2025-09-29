<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'], $_POST['faculty_id'], $_POST['semester_id'], $_POST['section'], $_POST['year'])) {
    $course_id   = $_POST['course_id'];
    $faculty_id  = $_POST['faculty_id'];
    $semester_id = $_POST['semester_id'];
    $section     = $_POST['section'];
    $year        = $_POST['year'];

    try {
        $stmt = $DB_con->prepare("
            INSERT INTO course_offerings (course_id, faculty_id, semester_id, section, year) 
            VALUES (:course_id, :faculty_id, :semester_id, :section, :year)
        ");
        $stmt->execute([
            ':course_id'   => $course_id,
            ':faculty_id'  => $faculty_id,
            ':semester_id' => $semester_id,
            ':section'     => $section,
            ':year'        => $year
        ]);
        $success = "Course offering created successfully!";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch dropdown data
$courses = $DB_con->query("SELECT course_id, course_name, course_code FROM courses ORDER BY course_name")->fetchAll(PDO::FETCH_ASSOC);
$faculty = $DB_con->query("SELECT faculty_id, full_name FROM faculty ORDER BY full_name")->fetchAll(PDO::FETCH_ASSOC);
$semesters = $DB_con->query("SELECT semester_id, name FROM semesters ORDER BY start_date DESC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all offerings
$offerings = $DB_con->query("
    SELECT o.id, c.course_name, c.course_code, f.full_name AS faculty_name, s.name AS semester_name, o.section, o.year
    FROM course_offerings o
    JOIN courses c ON o.course_id = c.course_id
    JOIN faculty f ON o.faculty_id = f.faculty_id
    JOIN semesters s ON o.semester_id = s.semester_id
    ORDER BY o.year DESC, s.start_date DESC
")->fetchAll(PDO::FETCH_ASSOC);

include 'admin_header.php';
?>

<div class="container mt-4">
    <h2>📚 Manage Course Offerings</h2>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Add Offering Form -->
    <div class="card mb-4">
        <div class="card-header">Add New Offering</div>
        <div class="card-body">
            <form method="post">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Course</label>
                        <select name="course_id" class="form-select" required>
                            <option value="">-- Select Course --</option>
                            <?php foreach ($courses as $c): ?>
                                <option value="<?= $c['course_id'] ?>"><?= $c['course_name'] ?> (<?= $c['course_code'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Faculty</label>
                        <select name="faculty_id" class="form-select" required>
                            <option value="">-- Select Faculty --</option>
                            <?php foreach ($faculty as $f): ?>
                                <option value="<?= $f['faculty_id'] ?>"><?= $f['full_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Semester</label>
                        <select name="semester_id" class="form-select" required>
                            <option value="">-- Select Semester --</option>
                            <?php foreach ($semesters as $s): ?>
                                <option value="<?= $s['semester_id'] ?>"><?= $s['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Section</label>
                        <input type="text" name="section" class="form-control" placeholder="A" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Year</label>
                        <input type="number" name="year" class="form-control" value="<?= date('Y') ?>" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Add Offering</button>
            </form>
        </div>
    </div>

    <!-- Offerings Table -->
    <div class="card">
        <div class="card-header">All Offerings</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Faculty</th>
                        <th>Semester</th>
                        <th>Section</th>
                        <th>Year</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($offerings as $o): ?>
                        <tr>
                            <td><?= htmlspecialchars($o['course_name']) ?> (<?= $o['course_code'] ?>)</td>
                            <td><?= htmlspecialchars($o['faculty_name']) ?></td>
                            <td><?= htmlspecialchars($o['semester_name']) ?></td>
                            <td><?= htmlspecialchars($o['section']) ?></td>
                            <td><?= htmlspecialchars($o['year']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>