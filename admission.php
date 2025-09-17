<?php
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/app/config/db.php";
?>
<div class="container my-5">
    <h1 class="mb-4">Admission Application</h1>

    <?php if (!empty($_GET['success'])): ?>
        <div class="alert alert-success">Application submitted. Proceed to payment.</div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-body">
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
                        <!-- if you have a departments table, you can offer a dropdown; fallback to free text -->
                        <?php
                        $dept_stmt = $DB_con->query("SELECT id, department_name FROM departments LIMIT 100");
                        $depts = $dept_stmt->fetchAll(PDO::FETCH_ASSOC);
                        if ($depts):
                        ?>
                            <select name="department" class="form-control" required>
                                <option value="">-- Select Department --</option>
                                <?php foreach ($depts as $d): ?>
                                    <option value="<?= htmlspecialchars($d['department_name']) ?>"><?= htmlspecialchars($d['department_name']) ?></option>
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
                        <label class="form-label">Batch (e.g. Spring 2022)</label>
                        <input type="text" name="batch" class="form-control" required>
                    </div>

                    <!-- optional: let system choose fee amount per program/department or fixed -->
                    <div class="col-md-6">
                        <label class="form-label">Application Fee (BDT)</label>
                        <input type="number" name="amount" class="form-control" value="5000" required>
                        <small class="text-muted">Default amount is 5000 — change if needed.</small>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">Submit & Pay</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/includes/footer.php"; ?>