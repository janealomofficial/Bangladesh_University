<?php
include 'admin_header.php';
require_once __DIR__ . "/../app/config/db.php";

// Fetch departments
$stmt = $DB_con->prepare("SELECT * FROM departments ORDER BY id DESC");
$stmt->execute();
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Manage Departments</h2>
        <a href="add_department.php" class="btn btn-primary">+ Add Department</a>
    </div>

    <div class="row">
        <?php foreach ($departments as $row): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <?php if (!empty($row['image_path'])): ?>
                        <img src="<?= htmlspecialchars($row['image_path']); ?>" class="card-img-top" alt="Department Image" style="height:200px; object-fit:cover;">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['department_name']); ?></h5>
                        <p><strong>Head:</strong> <?= htmlspecialchars($row['department_head']); ?></p>
                        <p><strong>Faculty:</strong> <?= htmlspecialchars($row['total_faculty']); ?></p>

                        <button
                            class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#departmentModal"
                            data-name="<?= htmlspecialchars($row['department_name']); ?>"
                            data-head="<?= htmlspecialchars($row['department_head']); ?>"
                            data-faculty="<?= htmlspecialchars($row['total_faculty']); ?>"
                            data-cost="<?= htmlspecialchars($row['course_cost']); ?>"
                            data-credits="<?= htmlspecialchars($row['total_credits']); ?>"
                            data-description="<?= htmlspecialchars($row['description']); ?>">
                            Learn More
                        </button>
                        <a href="edit_department.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_department.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="departmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="departmentModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Head:</strong> <span id="modalHead"></span></p>
                <p><strong>Faculty:</strong> <span id="modalFaculty"></span></p>
                <p><strong>Cost:</strong> $<span id="modalCost"></span></p>
                <p><strong>Total Credits:</strong> <span id="modalCredits"></span></p>
                <p><strong>Description:</strong></p>
                <div id="modalDescription" style="max-height:200px; overflow-y:auto;"></div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById('departmentModal');
        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            modal.querySelector('.modal-title').textContent = button.getAttribute('data-name');
            modal.querySelector('#modalHead').textContent = button.getAttribute('data-head');
            modal.querySelector('#modalFaculty').textContent = button.getAttribute('data-faculty');
            modal.querySelector('#modalCost').textContent = button.getAttribute('data-cost');
            modal.querySelector('#modalCredits').textContent = button.getAttribute('data-credits');
            modal.querySelector('#modalDescription').textContent = button.getAttribute('data-description');
        });
    });
</script>

<?php include 'admin_footer.php'; ?>