<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_offering'])) {
    $stmt = $DB_con->prepare("INSERT INTO course_offerings (course_id,faculty_id,semester_id,section,year) VALUES (:c,:f,:s,:sec,:y)");
    $stmt->execute([':c' => $_POST['course_id'], ':f' => $_POST['faculty_id'], ':s' => $_POST['semester_id'], ':sec' => $_POST['section'], ':y' => $_POST['year']]);
    $success = "Offering added!";
}

// Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_offering'])) {
    $stmt = $DB_con->prepare("UPDATE course_offerings SET course_id=:c,faculty_id=:f,semester_id=:s,section=:sec,year=:y WHERE id=:id");
    $stmt->execute([':c' => $_POST['course_id'], ':f' => $_POST['faculty_id'], ':s' => $_POST['semester_id'], ':sec' => $_POST['section'], ':y' => $_POST['year'], ':id' => $_POST['id']]);
    $success = "Offering updated!";
}

// Delete
if (isset($_GET['delete'])) {
    $stmt = $DB_con->prepare("DELETE FROM course_offerings WHERE id=:id");
    $stmt->execute([':id' => $_GET['delete']]);
    $success = "Offering deleted!";
}

// Dropdown data
$courses = $DB_con->query("SELECT course_id,course_name FROM courses")->fetchAll(PDO::FETCH_ASSOC);
$faculty = $DB_con->query("SELECT faculty_id,full_name FROM faculty")->fetchAll(PDO::FETCH_ASSOC);
$semesters = $DB_con->query("SELECT semester_id,name FROM semesters")->fetchAll(PDO::FETCH_ASSOC);

// Fetch offerings
$offerings = $DB_con->query("SELECT o.*,c.course_name,f.full_name,s.name AS semester_name
                           FROM course_offerings o
                           JOIN courses c ON o.course_id=c.course_id
                           JOIN faculty f ON o.faculty_id=f.faculty_id
                           JOIN semesters s ON o.semester_id=s.semester_id
                           ORDER BY o.year DESC")->fetchAll(PDO::FETCH_ASSOC);

include 'admin_header.php';
?>

<div class="container mt-4">
    <h2>Manage Course Offerings</h2>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

    <!-- Add Form -->
    <form method="POST" class="row g-3 mb-4">
        <input type="hidden" name="add_offering" value="1">
        <div class="col-md-3">
            <select name="course_id" class="form-control" required>
                <option value="">Course</option>
                <?php foreach ($courses as $c): ?><option value="<?= $c['course_id'] ?>"><?= $c['course_name'] ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="faculty_id" class="form-control" required>
                <option value="">Faculty</option>
                <?php foreach ($faculty as $f): ?><option value="<?= $f['faculty_id'] ?>"><?= $f['full_name'] ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="semester_id" class="form-control" required>
                <option value="">Semester</option>
                <?php foreach ($semesters as $s): ?><option value="<?= $s['semester_id'] ?>"><?= $s['name'] ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-1"><input type="text" name="section" placeholder="Sec" class="form-control" required></div>
        <div class="col-md-2"><input type="number" name="year" value="<?= date('Y') ?>" class="form-control" required></div>
        <div class="col-md-1"><button class="btn btn-primary w-100">Add</button></div>
    </form>

    <!-- Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Course</th>
                <th>Faculty</th>
                <th>Semester</th>
                <th>Section</th>
                <th>Year</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($offerings as $o): ?>
                <tr>
                    <td><?= $o['id'] ?></td>
                    <td><?= $o['course_name'] ?></td>
                    <td><?= $o['full_name'] ?></td>
                    <td><?= $o['semester_name'] ?></td>
                    <td><?= $o['section'] ?></td>
                    <td><?= $o['year'] ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editOffer<?= $o['id'] ?>">Edit</button>
                        <a href="?delete=<?= $o['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete offering?');">Delete</a>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editOffer<?= $o['id'] ?>">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST">
                                <div class="modal-header">
                                    <h5>Edit Offering</h5>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="<?= $o['id'] ?>">
                                    <input type="hidden" name="edit_offering" value="1">

                                    <div class="mb-2"><label>Course</label>
                                        <select name="course_id" class="form-control"><?php foreach ($courses as $c): ?>
                                                <option value="<?= $c['course_id'] ?>" <?= $o['course_id'] == $c['course_id'] ? 'selected' : '' ?>><?= $c['course_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-2"><label>Faculty</label>
                                        <select name="faculty_id" class="form-control"><?php foreach ($faculty as $f): ?>
                                                <option value="<?= $f['faculty_id'] ?>" <?= $o['faculty_id'] == $f['faculty_id'] ? 'selected' : '' ?>><?= $f['full_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-2"><label>Semester</label>
                                        <select name="semester_id" class="form-control"><?php foreach ($semesters as $s): ?>
                                                <option value="<?= $s['semester_id'] ?>" <?= $o['semester_id'] == $s['semester_id'] ? 'selected' : '' ?>><?= $s['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-2"><label>Section</label><input class="form-control" name="section" value="<?= $o['section'] ?>"></div>
                                    <div class="mb-2"><label>Year</label><input class="form-control" type="number" name="year" value="<?= $o['year'] ?>"></div>
                                </div>
                                <div class="modal-footer"><button class="btn btn-success">Save</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </tbody>
        Here’s the **final complete page** for `manage_offerings.php` with full **Add, Edit, Delete** features using Bootstrap modals, matching the other two pages.

        ---

        # 📌 `manage_offerings.php`

        ```php
        <?php
        session_start();
        require_once __DIR__ . "/../app/config/db.php";
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: ../login.php");
            exit;
        }

        // ✅ Add Offering
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_offering'])) {
            $stmt = $DB_con->prepare("INSERT INTO course_offerings (course_id,faculty_id,semester_id,section,year) VALUES (:c,:f,:s,:sec,:y)");
            $stmt->execute([
                ':c' => $_POST['course_id'],
                ':f' => $_POST['faculty_id'],
                ':s' => $_POST['semester_id'],
                ':sec' => $_POST['section'],
                ':y' => $_POST['year']
            ]);
            $success = "Offering added!";
        }

        // ✅ Edit Offering
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_offering'])) {
            $stmt = $DB_con->prepare("UPDATE course_offerings 
                            SET course_id=:c, faculty_id=:f, semester_id=:s, section=:sec, year=:y 
                            WHERE id=:id");
            $stmt->execute([
                ':c' => $_POST['course_id'],
                ':f' => $_POST['faculty_id'],
                ':s' => $_POST['semester_id'],
                ':sec' => $_POST['section'],
                ':y' => $_POST['year'],
                ':id' => $_POST['id']
            ]);
            $success = "Offering updated!";
        }

        // ✅ Delete Offering
        if (isset($_GET['delete'])) {
            $stmt = $DB_con->prepare("DELETE FROM course_offerings WHERE id=:id");
            $stmt->execute([':id' => $_GET['delete']]);
            $success = "Offering deleted!";
        }

        // Dropdown Data
        $courses = $DB_con->query("SELECT course_id,course_name FROM courses")->fetchAll(PDO::FETCH_ASSOC);
        $faculty = $DB_con->query("SELECT faculty_id,full_name FROM faculty")->fetchAll(PDO::FETCH_ASSOC);
        $semesters = $DB_con->query("SELECT semester_id,name FROM semesters")->fetchAll(PDO::FETCH_ASSOC);

        // Fetch Offerings
        $offerings = $DB_con->query("SELECT o.*,c.course_name,f.full_name,s.name AS semester_name
                           FROM course_offerings o
                           JOIN courses c ON o.course_id=c.course_id
                           JOIN faculty f ON o.faculty_id=f.faculty_id
                           JOIN semesters s ON o.semester_id=s.semester_id
                           ORDER BY o.year DESC")->fetchAll(PDO::FETCH_ASSOC);

        include 'admin_header.php';
        ?>

        <div class="container mt-4">
            <h2>Manage Course Offerings</h2>
            <?php if (!empty($success)): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

            <!-- Add Form -->
            <form method="POST" class="row g-3 mb-4">
                <input type="hidden" name="add_offering" value="1">
                <div class="col-md-3">
                    <select name="course_id" class="form-control" required>
                        <option value="">Course</option>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?= $c['course_id'] ?>"><?= $c['course_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="faculty_id" class="form-control" required>
                        <option value="">Faculty</option>
                        <?php foreach ($faculty as $f): ?>
                            <option value="<?= $f['faculty_id'] ?>"><?= $f['full_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="semester_id" class="form-control" required>
                        <option value="">Semester</option>
                        <?php foreach ($semesters as $s): ?>
                            <option value="<?= $s['semester_id'] ?>"><?= $s['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-1"><input type="text" name="section" placeholder="Sec" class="form-control" required></div>
                <div class="col-md-2"><input type="number" name="year" value="<?= date('Y') ?>" class="form-control" required></div>
                <div class="col-md-1"><button class="btn btn-primary w-100">Add</button></div>
            </form>

            <!-- Offerings Table -->
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Course</th>
                        <th>Faculty</th>
                        <th>Semester</th>
                        <th>Section</th>
                        <th>Year</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($offerings as $o): ?>
                        <tr>
                            <td><?= $o['id'] ?></td>
                            <td><?= $o['course_name'] ?></td>
                            <td><?= $o['full_name'] ?></td>
                            <td><?= $o['semester_name'] ?></td>
                            <td><?= $o['section'] ?></td>
                            <td><?= $o['year'] ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editOffer<?= $o['id'] ?>">Edit</button>
                                <a href="?delete=<?= $o['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete offering?');">Delete</a>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editOffer<?= $o['id'] ?>">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-header">
                                            <h5>Edit Offering</h5>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?= $o['id'] ?>">
                                            <input type="hidden" name="edit_offering" value="1">

                                            <div class="mb-2"><label>Course</label>
                                                <select name="course_id" class="form-control">
                                                    <?php foreach ($courses as $c): ?>
                                                        <option value="<?= $c['course_id'] ?>" <?= $o['course_id'] == $c['course_id'] ? 'selected' : '' ?>><?= $c['course_name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="mb-2"><label>Faculty</label>
                                                <select name="faculty_id" class="form-control">
                                                    <?php foreach ($faculty as $f): ?>
                                                        <option value="<?= $f['faculty_id'] ?>" <?= $o['faculty_id'] == $f['faculty_id'] ? 'selected' : '' ?>><?= $f['full_name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="mb-2"><label>Semester</label>
                                                <select name="semester_id" class="form-control">
                                                    <?php foreach ($semesters as $s): ?>
                                                        <option value="<?= $s['semester_id'] ?>" <?= $o['semester_id'] == $s['semester_id'] ? 'selected' : '' ?>><?= $s['name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="mb-2"><label>Section</label><input class="form-control" name="section" value="<?= $o['section'] ?>"></div>
                                            <div class="mb-2"><label>Year</label><input class="form-control" type="number" name="year" value="<?= $o['year'] ?>"></div>
                                        </div>
                                        <div class="modal-footer"><button class="btn btn-success">Save</button></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php include 'admin_footer.php'; ?>