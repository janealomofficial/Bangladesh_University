<?php
require_once __DIR__ . "/app/config/db.php";

// fetch alumni
$alumni = $DB_con->query("SELECT alumni_id, full_name, department, grad_year, bio, image_path FROM alumni ORDER BY grad_year DESC, full_name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Alumni</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .alumni-card img { width:100%; height:220px; object-fit:cover; border-radius:6px; }
  </style>
</head>
<body>
<?php require_once __DIR__ . "/includes/header.php"; ?>  <!-- optional navbar include -->

<div class="container py-5">
  <h1 class="mb-4">Our Alumni</h1>

  <div class="row g-4">
    <?php foreach($alumni as $a): ?>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card alumni-card">
          <?php if ($a['image_path']): ?>
            <img src="<?php echo htmlspecialchars($a['image_path']); ?>" alt="<?= htmlspecialchars($a['full_name']) ?>">
          <?php else: ?>
            <img src="https://via.placeholder.com/400x220?text=No+Image" alt="No image">
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($a['full_name']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($a['department'] ?? '') ?></p>
            <p class="card-text"><small class="text-muted"><?= htmlspecialchars($a['grad_year'] ?? '') ?></small></p>
            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#alumniModal<?= $a['alumni_id'] ?>">View</button>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="alumniModal<?= $a['alumni_id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><?= htmlspecialchars($a['full_name']) ?></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-4">
                  <?php if ($a['image_path']): ?>
                    <img src="<?= htmlspecialchars($a['image_path']) ?>" class="img-fluid" alt="">
                  <?php else: ?>
                    <img src="https://via.placeholder.com/400x400?text=No+Image" class="img-fluid" alt="">
                  <?php endif; ?>
                </div>
                <div class="col-md-8">
                  <p><strong>Department:</strong> <?= htmlspecialchars($a['department'] ?? '—') ?></p>
                  <p><strong>Graduation Year:</strong> <?= htmlspecialchars($a['grad_year'] ?? '—') ?></p>
                  <p><?= nl2br(htmlspecialchars($a['bio'] ?? '')) ?></p>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

    <?php endforeach; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
