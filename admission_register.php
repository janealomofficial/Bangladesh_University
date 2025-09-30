<?php
require_once __DIR__ . "/app/config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Collect inputs
        $full_name  = trim($_POST['full_name']);
        $email      = trim($_POST['email']);
        $phone      = trim($_POST['phone']);
        $address    = trim($_POST['address']);
        $department = trim($_POST['department']);
        $program    = trim($_POST['program']);
        $batch      = trim($_POST['batch']);
        $amount     = (float)($_POST['amount'] ?? 5000);

        // STEP 1: Insert into admissions (id will AUTO_INCREMENT correctly)
        $stmt = $DB_con->prepare("
            INSERT INTO admissions 
                (full_name, email, phone, address, department, program, batch, payment_status, amount, created_at)
            VALUES 
                (:full_name, :email, :phone, :address, :department, :program, :batch, 'paid', :amount, NOW())
        ");
        $stmt->execute([
            ':full_name'  => $full_name,
            ':email'      => $email,
            ':phone'      => $phone,
            ':address'    => $address,
            ':department' => $department,
            ':program'    => $program,
            ':batch'      => $batch,
            ':amount'     => $amount
        ]);

        // STEP 2: Get the inserted admission ID
        $admission_id = $DB_con->lastInsertId();

        // STEP 3: Generate Student ID like BU2025-001
        $year = date("Y");
        $student_id = "BU{$year}-" . str_pad($admission_id, 3, "0", STR_PAD_LEFT);

        $DB_con->prepare("UPDATE admissions SET student_id = :sid WHERE id = :id")
               ->execute([':sid' => $student_id, ':id' => $admission_id]);

        // STEP 4: Generate unique Invoice No
        $invoice_no = "INV" . date("Ymd") . "-" . str_pad($admission_id, 4, "0", STR_PAD_LEFT);

        $stmt = $DB_con->prepare("
            INSERT INTO admission_invoices (admission_id, invoice_no, amount, status, issued_at)
            VALUES (:admission_id, :invoice_no, :amount, 'paid', NOW())
        ");
        $stmt->execute([
            ':admission_id' => $admission_id,
            ':invoice_no'   => $invoice_no,
            ':amount'       => $amount
        ]);

        $invoice_id = $DB_con->lastInsertId();

        // STEP 5: Redirect user to the generated invoice
        header("Location: admin/invoice.php?invoice_id=" . $invoice_id);
        exit;

    } catch (PDOException $e) {
        echo "<p style='color:red;'>❌ DB Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>
