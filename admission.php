<?php
session_start();
require_once __DIR__ . "/app/config/db.php";

// Check if user is logged in
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'student') {
    // See if this student already has an admission record
    $check = $DB_con->prepare("SELECT id FROM admissions WHERE student_id = :sid LIMIT 1");
    $check->execute([':sid' => $_SESSION['user_id']]);
    if ($check->fetch()) {
        // Admission already completed → go to dashboard
        header("Location: student/dashboard.php");
        exit;
    }
    // else → show admission form below
}

// Only include header after redirects are handled
require_once __DIR__ . "/includes/header.php";
?>

<div class="container my-5">
    <h1 class="mb-4">🎓 University Admission</h1>

    <!-- Some information about the university -->
    <div class="card mb-4">
        <div class="card-body">
            <h4>Why Choose Our University?</h4>
            <ul>
                <li>Top-ranked faculty and research facilities.</li>
                <li>Wide range of programs across science, engineering, business, and arts.</li>
                <li>Scholarship opportunities for outstanding students.</li>
                <li>Vibrant campus life with clubs, events, and innovation hubs.</li>
            </ul>
        </div>
    </div>

    <?php if (!isset($_SESSION['user_id'])): ?>
        <!-- Not logged in: show Apply Now button -->
        <div class="text-center">
            <a href="register.php" class="btn btn-primary btn-lg">Apply Now</a>
        </div>

    <?php else: ?>
        <!-- Logged in but not admitted yet: show admission form -->
        <div class="card mb-4">
            <div class="card-body">
                <h4>Admission Application Form</h4>
                <form action="admission_register.php" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <?php
                            $dept_stmt = $DB_con->query("SELECT id, department_name FROM departments LIMIT 100");
                            $depts = $dept_stmt->fetchAll(PDO::FETCH_ASSOC);
                            if ($depts):
                            ?>
                                <select name="department" class="form-control" required>
                                    <option value="">-- Select Department --</option>
                                    <?php foreach ($depts as $d): ?>
                                        <option value="<?= htmlspecialchars($d['department_name']) ?>">
                                            <?= htmlspecialchars($d['department_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <input type="text" name="department" class="form-control" required>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Program (e.g. B.Sc. CSE)</label>
                            <input type="text" name="program" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Batch (e.g. Spring 2025)</label>
                            <input type="text" name="batch" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Application Fee (BDT)</label>
                            <input type="number" name="application_fee" class="form-control" value="5000" required>
                            <small class="text-muted">Default fee is 5000 — change if needed.</small>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-success">Submit & Proceed</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . "/includes/footer.php"; ?>

