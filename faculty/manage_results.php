<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../login.php");
    exit;
}

// 1) Resolve faculty_id from logged in user
$user_id = $_SESSION['user_id'];
$stmt = $DB_con->prepare("SELECT faculty_id FROM faculty WHERE user_id = :uid LIMIT 1");
$stmt->execute([':uid' => $user_id]);
$fid = $stmt->fetchColumn();
if (!$fid) {
    die("Faculty profile not linked. Contact admin.");
}

// 2) Offerings taught by this faculty
$offerings = $DB_con->prepare("
    SELECT co.id AS offering_id, c.course_id, c.course_code, c.course_name,
           s.semester_id, s.name AS semester_name, co.section, co.year
    FROM course_offerings co
    JOIN courses   c ON co.course_id   = c.course_id
    JOIN semesters s ON co.semester_id = s.semester_id
    WHERE co.faculty_id = :fid
    ORDER BY s.start_date DESC, c.course_name ASC
");
$offerings->execute([':fid' => $fid]);
$offering_rows = $offerings->fetchAll(PDO::FETCH_ASSOC);

// 3) Selected offering?
$sel_offering_id = isset($_GET['offering_id']) ? (int)$_GET['offering_id'] : 0;
$students  = [];
$existing  = [];

if ($sel_offering_id) {
    // Confirm this offering belongs to the logged-in faculty
    $check = $DB_con->prepare("
        SELECT co.course_id, co.semester_id
        FROM course_offerings co
        WHERE co.id = :oid AND co.faculty_id = :fid
        LIMIT 1
    ");
    $check->execute([':oid' => $sel_offering_id, ':fid' => $fid]);
    $row = $check->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        die("Invalid offering or permission denied.");
    }

    // Enrolled students in this offering
    $st = $DB_con->prepare("
        SELECT e.student_id, s.full_name
        FROM enrollments e
        JOIN students s ON s.student_id = e.student_id
        WHERE e.offering_id = :oid
        ORDER BY s.full_name
    ");
    $st->execute([':oid' => $sel_offering_id]);
    $students = $st->fetchAll(PDO::FETCH_ASSOC);

    // Existing results to prefill
    $rs = $DB_con->prepare("
        SELECT student_id, marks, grade, status
        FROM results
        WHERE offering_id = :oid
    ");
    $rs->execute([':oid' => $sel_offering_id]);
    $existing = [];
    foreach ($rs as $r) {
        $existing[(int)$r['student_id']] = $r;
    }
}

include 'faculty_header.php';
?>
<div class="container-fluid mt-4">
    <div class="d-flex align-items-center mb-3">
        <h2 class="me-3">Enter / Update Results</h2>
    </div>

    <!-- Offering chooser -->
    <form class="row g-2 mb-4" method="get" action="manage_results.php">
        <div class="col-md-6">
            <label class="form-label">Select your course offering</label>
            <select name="offering_id" class="form-select" required>
                <option value="">-- Choose --</option>
                <?php foreach ($offering_rows as $o): ?>
                    <option value="<?= (int)$o['offering_id'] ?>"
                        <?= $sel_offering_id == (int)$o['offering_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($o['course_name']) ?> (<?= htmlspecialchars($o['course_code']) ?>)
                        — <?= htmlspecialchars($o['semester_name']) ?>
                        <?= $o['section'] ? ' — Sec: ' . htmlspecialchars($o['section']) : '' ?>
                        <?= $o['year'] ? ' — ' . $o['year'] : '' ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 align-self-end">
            <button class="btn btn-primary w-100">Load</button>
        </div>
    </form>

    <?php if ($sel_offering_id): ?>
        <div class="card">
            <div class="card-header">Students & Marks</div>
            <div class="card-body">
                <?php if (empty($students)): ?>
                    <div class="alert alert-warning">No students enrolled in this course offering.</div>
                <?php else: ?>
                    <form method="post" action="save_results.php">
                        <input type="hidden" name="offering_id" value="<?= (int)$sel_offering_id ?>">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th style="width: 140px">Marks</th>
                                    <th style="width: 120px">Grade</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $s):
                                    $sid = (int)$s['student_id'];
                                    $pref = $existing[$sid] ?? null;
                                    $published = ($pref && $pref['status'] === 'published');
                                ?>
                                    <tr>
                                        <td>
                                            <?= htmlspecialchars($s['full_name']) ?>
                                            <div class="text-muted small">ID: <?= $sid ?></div>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" max="100"
                                                class="form-control"
                                                name="marks[<?= $sid ?>]"
                                                value="<?= $pref ? htmlspecialchars($pref['marks']) : '' ?>"
                                                <?= $published ? 'disabled' : '' ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control"
                                                name="grade[<?= $sid ?>]"
                                                placeholder="A+, A, A-, ..."
                                                value="<?= $pref ? htmlspecialchars($pref['grade']) : '' ?>"
                                                <?= $published ? 'disabled' : '' ?>>
                                        </td>
                                        <td>
                                            <?php if ($published): ?>
                                                <span class="badge bg-success">Published</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Save Draft</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php include 'faculty_footer.php'; ?>