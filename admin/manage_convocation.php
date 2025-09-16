<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

// Admin Auth Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle Approve / Reject Actions
if (isset($_GET['action'], $_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'approve') {
        $stmt = $DB_con->prepare("UPDATE convocation_registrations SET status = 'approved' WHERE id = :id");
        $stmt->execute([':id' => $id]);
    } elseif ($action === 'reject') {
        $stmt = $DB_con->prepare("UPDATE convocation_registrations SET status = 'rejected' WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    header("Location: manage_convocation.php?msg=updated");
    exit;
}

// Fetch Registrations
$stmt = $DB_con->query("SELECT * FROM convocation_registrations ORDER BY created_at DESC");
$registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'admin_header.php';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div class="container-fluid p-4">
    <h2 class="mb-4">Manage Convocation Registrations</h2>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
        <div class="alert alert-success">Registration status updated successfully!</div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Department</th>
                            <th>Session</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($registrations)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">No registrations yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($registrations as $index => $r): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($r['full_name']); ?></td>
                                    <td><?= htmlspecialchars($r['email']); ?></td>
                                    <td><?= htmlspecialchars($r['phone']); ?></td>
                                    <td><?= htmlspecialchars($r['department']); ?></td>
                                    <td><?= htmlspecialchars($r['session']); ?></td>
                                    <td>
                                        <?php if ($r['status'] === 'approved'): ?>
                                            <span class="badge bg-success">Approved</span>
                                        <?php elseif ($r['status'] === 'rejected'): ?>
                                            <span class="badge bg-danger">Rejected</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <!-- View Details -->
                                            <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#viewModal<?= $r['id']; ?>">
                                                <i class="bi bi-eye"></i> View
                                            </button>

                                            <?php if ($r['status'] === 'pending'): ?>
                                                <a href="?action=approve&id=<?= $r['id']; ?>"
                                                    class="btn btn-success btn-sm"
                                                    onclick="return confirm('Approve this registration?');">
                                                    <i class="bi bi-check2-circle"></i> Approve
                                                </a>
                                                <a href="?action=reject&id=<?= $r['id']; ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Reject this registration?');">
                                                    <i class="bi bi-x-circle"></i> Reject
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-secondary btn-sm" disabled>✔ Reviewed</button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal for Viewing Details -->
                                <div class="modal fade" id="viewModal<?= $r['id']; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Registration Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><strong>Name:</strong> <?= htmlspecialchars($r['name']); ?></li>
                                                    <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($r['email']); ?></li>
                                                    <li class="list-group-item"><strong>Phone:</strong> <?= htmlspecialchars($r['phone']); ?></li>
                                                    <li class="list-group-item"><strong>Department:</strong> <?= htmlspecialchars($r['department']); ?></li>
                                                    <li class="list-group-item"><strong>Session:</strong> <?= htmlspecialchars($r['session']); ?></li>
                                                    <li class="list-group-item"><strong>Status:</strong> <?= htmlspecialchars($r['status']); ?></li>
                                                    <li class="list-group-item"><strong>Submitted At:</strong> <?= htmlspecialchars($r['created_at']); ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>