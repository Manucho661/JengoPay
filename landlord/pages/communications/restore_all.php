<?php
header('Content-Type: application/json');
require '../db/connect.php';

// Optional: authentication/authorization check here (e.g., admin role)

// Define response structure
$response = [
    'success' => false,
    'message' => '',
    'restored_count' => 0
];

try {
    // Restore all announcements with 'Archived' status to 'Sent'
    $sql = "UPDATE announcements SET status = 'Sent' WHERE status = 'Archived'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $count = $stmt->rowCount();

    if ($count > 0) {
        $response['success'] = true;
        $response['message'] = "$count announcement(s) restored successfully.";
        $response['restored_count'] = $count;
    } else {
        $response['message'] = "No archived announcements found to restore.";
    }

    echo json_encode($response);
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    $response['message'] = "Database error occurred.";
    $response['error'] = $e->getMessage(); // Optionally remove in production
    echo json_encode($response);
}
