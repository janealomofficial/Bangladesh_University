<?php
// app/helpers/invoicing.php

/**
 * Make sure there is an invoice for a paid admission.
 * Returns the invoice id (existing or newly created) or null if not paid.
 */
function ensure_admission_invoice(PDO $DB_con, int $admissionId, float $fee = 5000.00)
{
    // 1) Read admission row
    $stmt = $DB_con->prepare("
        SELECT id, payment_status
        FROM admissions
        WHERE id = :id
        LIMIT 1
    ");
    $stmt->execute([':id' => $admissionId]);
    $adm = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$adm) return null;

    // Only generate for 'paid'
    if ($adm['payment_status'] !== 'paid') {
        return null;
    }

    // 2) If invoice already exists, return it
    $chk = $DB_con->prepare("
        SELECT id FROM admission_invoices
        WHERE admission_id = :id
        ORDER BY id DESC
        LIMIT 1
    ");
    $chk->execute([':id' => $admissionId]);
    $invoiceId = $chk->fetchColumn();
    if ($invoiceId) return (int)$invoiceId;

    // 3) Generate unique invoice_no (date + padded admission id)
    $invoiceNo = 'INV' . date('Ymd') . '-' . str_pad($admissionId, 4, '0', STR_PAD_LEFT);

    // 4) Insert the invoice
    $ins = $DB_con->prepare("
        INSERT INTO admission_invoices (admission_id, invoice_no, amount, issued_at, status)
        VALUES (:adid, :invno, :amt, NOW(), 'unpaid')
    ");
    $ins->execute([
        ':adid' => $admissionId,
        ':invno' => $invoiceNo,
        ':amt'   => $fee, // fallback fee; replace with real fee if you store it elsewhere
    ]);

    return (int)$DB_con->lastInsertId();
}
