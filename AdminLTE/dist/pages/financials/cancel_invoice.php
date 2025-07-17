<?php
require_once '../db/connect.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $invoiceId = intval($_GET['id']);

    // Update invoice status to cancelled
    $stmt = $pdo->prepare("UPDATE invoice SET status = 'cancelled' WHERE id = ?");
    if ($stmt->execute([$invoiceId])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'DB update failed']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid ID']);
}
