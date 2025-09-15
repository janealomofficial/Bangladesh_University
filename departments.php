<?php
require_once __DIR__ . "/app/config/db.php";

// Fetch all departments
$stmt = $DB_con->query("SELECT * FROM departments ORDER BY created_at DESC");
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?> <!-- Your main site header -->

<div class="container py-5">
    <h2 class="text-center mb-5">Our Departments</h2>
    <div class="row">
        <?php foreach ($departments as $d): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if (!empty($d['image_path'])): ?>
                        <img src="<?= ltrim($d['image_path'], './') ?>" class="card-img-top" alt="Department Image" style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($d['department_name']) ?></h5>
                        <p><strong>Head:</strong> <?= htmlspecialchars($d['department_head']) ?></p>
                        <p><strong>Faculty:</strong> <?= htmlspecialchars($d['total_faculty']) ?></p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal<?= $d['id'] ?>">
                            Learn More
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal for Department Info -->
            <div class="modal fade" id="modal<?= $d['id'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $d['id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel<?= $d['id'] ?>"><?= htmlspecialchars($d['department_name']) ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?php if (!empty($d['image_path'])): ?>
                                <img src="<?= ltrim($d['image_path'], './') ?>" class="img-fluid mb-3" alt="Department Image">
                            <?php endif; ?>
                            <p><strong>Head:</strong> <?= htmlspecialchars($d['department_head']) ?></p>
                            <p><strong>Total Faculty:</strong> <?= htmlspecialchars($d['total_faculty']) ?></p>
                            <p><strong>Total Credits:</strong> <?= htmlspecialchars($d['total_credits']) ?></p>
                            <p><strong>Course Cost:</strong> <?= number_format($d['course_cost'], 2) ?> BDT</p>
                            <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($d['description'])) ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?> <!-- Your main site footer -->