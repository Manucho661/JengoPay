<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

include '../../../db/connect.php'; // ✅ Ensure this path is correct

// You can use session OR fallback to POST
session_start();

$user_id = $_SESSION['user_id'] ?? $_POST['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$id_no = $_POST['id_no'] ?? '';

if (empty($email) || empty($phone) || empty($id_no)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

try {
    // Update email in users table
    $stmtUser = $pdo->prepare("UPDATE users SET email = :email WHERE id = :user_id");
    $stmtUser->execute([
        ':email' => $email,
        ':user_id' => $user_id,
    ]);

    // Update phone and ID in tenants table
    $stmtTenant = $pdo->prepare("UPDATE tenants SET phone_number = :phone, id_no = :id_no WHERE user_id = :user_id");
    $stmtTenant->execute([
        ':phone' => $phone,
        ':id_no' => $id_no,
        ':user_id' => $user_id,
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
