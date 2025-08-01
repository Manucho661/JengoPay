
<? // Add at the beginning of filter.php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Add input validation
$allowed_statuses = ['draft', 'sent', 'paid', 'overdue', 'cancelled', null];
$allowed_payment_statuses = ['unpaid', 'partial', 'paid', null];

if (!in_array($status, $allowed_statuses) || !in_array($payment_status, $allowed_payment_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid filter parameters']);
    exit;
}
?>