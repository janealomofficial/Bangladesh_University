<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// dropdowns
$semesters = $DB_con->query("SELECT semester_id, name FROM semesters ORDER BY start_date DESC")->fetchAll(PDO::FETCH_ASSOC);
$courses   = $DB_con->query("SELECT course_id, course_code, course_name FROM courses ORDER BY course_name")->fetchAll(PDO::FETCH_ASSOC);

$semester_id = isset($_GET['semester_id']) ? (int)$_GET['semester_id'] : 0;
$course_id   = isset($_GET['course_id'])   ? (int)$_GET['course_id']   : 0;

$results = [];
if ($semester_id && $course_id) {
    $stmt = $DB_con->prepare("
        SELECT r.id, r.student_id, s.full_name, r.marks, r.grade, r.status,
               c.course_code, c.course_name, sem.name as semester_name
        FROM results r
        JOIN students s ON s.student_id = r.student_id
        JOIN courses  c ON c.course_id = r.course_id
        JOIN semesters sem ON sem.semester_id = r.semester_id
        WHERE r.semester_id=:sem AND r.course_id=:cid
        ORDER BY s.full_name
    ");
    $stmt->execute([':sem'=>$semester_id, ':cid'=>$course_id]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

include 'admin_header.php';
?>
<div class="container-fluid mt-4">
    <h2>Manage Results</h2>

    <form class="row g-2 mb-4" method="get">
        <div class="col-md-4">
            <label class="form-label">Semester</label>
            <select name="semester_id" class="form-select" required>
                <option value="">-- Select --</option>
                <?php foreach ($semesters as $s): ?>
                    <option value="<?= (int)$s['semester_id'] ?>" <?= $semester_id==$s['semester_id']?'selected':'' ?>>
                        <?= htmlspecialchars($s['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Course</label>
            <select name="course_id" class="form-select" required>
                <option value="">-- Select --</option>
                <?php foreach ($courses as $c): ?>
                    <option value="<?= (int)$c['course_id'] ?>" <?= $course_id==$c['course_id']?'selected':'' ?>>
                        <?= htmlspecialchars($c['course_name']) ?> (<?= htmlspecialchars($c['course_code']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 align-self-end">
            <button class="btn btn-primary w-100">Load</button>
        </div>
        <?php if ($semester_id && $course_id): ?>
        <div class="col-md-2 align-self-end text-end">
            <a class="btn btn-success"
               href="publish_results.php?semester_id=<?= (int)$semester_id ?>&course_id=<?= (int)$course_id ?>"
               onclick="return confirm('Publish all results for this course & semester? Faculty will no longer be able to edit.');">
               Publish All
            </a>
        </div>
        <?php endif; ?>
    </form>

    <?php if ($semester_id && $course_id): ?>
        <div class="card">
            <div class="card-header">Results</div>
            <div class="card-body">
                <?php if (empty($results)): ?>
                    <div class="alert alert-warning">No results yet.</div>
                <?php else: ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Marks</th>
                                <th>Grade</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars($r['full_name']) ?> <span class="text-muted small">#<?= (int)$r['student_id'] ?></span></td>
                                <td><?= htmlspecialchars($r['marks']) ?></td>
                                <td><?= htmlspecialchars($r['grade']) ?></td>
                                <td>
                                    <?php if ($r['status'] === 'published'): ?>
                                        <span class="badge bg-success">Published</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Draft</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php include 'admin_footer.php'; ?>
