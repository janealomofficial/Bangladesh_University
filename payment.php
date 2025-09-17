<?php
require_once __DIR__ . "/app/config/db.php";

$admission_id = (int)($_GET['admission_id'] ?? 0);
$amount = (float)($_GET['amount'] ?? 0);

if (!$admission_id) {
    header("Location: admission.php");
    exit;
}

$stmt = $DB_con->prepare("SELECT * FROM admissions WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $admission_id]);
$ad = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$ad) {
    die("Admission not found.");
}
?>
<?php require_once __DIR__ . "/includes/header.php"; ?>
<div class="container my-5">
    <h2>Complete Payment</h2>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Applicant:</strong> <?= htmlspecialchars($ad['full_name']) ?> (<?= htmlspecialchars($ad['student_id']) ?>)</p>
            <p><strong>Program:</strong> <?= htmlspecialchars($ad['program']) ?> | <strong>Batch:</strong> <?= htmlspecialchars($ad['batch']) ?></p>
            <p><strong>Amount to pay:</strong> BDT <?= number_format($amount, 2) ?></p>

            <form method="post" action="payment_callback.php">
                <input type="hidden" name="admission_id" value="<?= $admission_id ?>">
                <input type="hidden" name="amount" value="<?= number_format($amount, 2, '.', '') ?>">
                <!-- In production you’d redirect to a payment gateway and pass a return URL -->
                <button type="submit" class="btn btn-success">Pay Now (Simulated)</button>
                <a href="admission.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?php require_once __DIR__ . "/includes/footer.php"; ?>