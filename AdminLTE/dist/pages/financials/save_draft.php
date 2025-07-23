<?php
require_once '../db/connect.php';
header('Content-Type: application/json');

function generateDraftNumber($pdo) {
    $prefix = 'DFT';
    $stmt = $pdo->prepare("SELECT invoice_number FROM invoice WHERE invoice_number LIKE ? ORDER BY id DESC LIMIT 1");
    $stmt->execute(["$prefix%"]);
    $lastInvoice = $stmt->fetch(PDO::FETCH_ASSOC);

    $lastNumber = ($lastInvoice && preg_match('/' . $prefix . '(\d+)/', $lastInvoice['invoice_number'], $matches))
                    ? (int) $matches[1]
                    : 0;

    return $prefix . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
}

try {
    $invoiceNumber = generateDraftNumber($pdo);
    $buildingId    = $_POST['building_id'] ?? null;
    $tenantId      = $_POST['tenant'] ?? null;
    $invoiceDate   = $_POST['invoice_date'] ?? null;
    $dueDate       = $_POST['due_date'] ?? null;

    $sql = "INSERT INTO invoice (invoice_number, building_id, tenant, invoice_date, due_date, status, created_at)
            VALUES (?, ?, ?, ?, ?, 'draft', NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$invoiceNumber, $buildingId, $tenantId, $invoiceDate, $dueDate]);

    // Return success with landing page URL
    echo json_encode([
        'success' => true,
        'invoice_number' => $invoiceNumber,
        'redirect_url' => 'invoice.php?draft_saved=1&invoice_number=' . urlencode($invoiceNumber),
        'message' => "Draft saved successfully"
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
