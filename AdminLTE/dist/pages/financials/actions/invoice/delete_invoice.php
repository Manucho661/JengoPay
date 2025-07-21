<?php
require_once '../../db/connect.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $invoiceId = intval($_GET['id']);

    $stmt = $pdo->prepare("DELETE FROM invoice WHERE id = ?");
    if ($stmt->execute([$invoiceId])) {
        // Redirect to inv1.php on success
        header("Location: inv1.php?deleted=1");
        exit;
    } else {
        // Redirect with error
        header("Location: inv1.php?deleted=0&error=Delete+failed");
        exit;
    }
} else {
    // Redirect with error
    header("Location: inv1.php?deleted=0&error=Invalid+ID");
    exit;
}
