<?php
header('Content-Type: application/json');

// Convert PHP warnings/notices → exceptions
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

require_once '../../db/connect.php'; // $pdo must exist

try {
    // -----------------------------
    // Validate input
    // -----------------------------
    if (empty($_POST['email']) || empty($_POST['newPassword'])) {
        echo json_encode([
            "status" => "error"
        ]);
        exit;
    }

    $email = trim($_POST['email']);
    $newPassword = $_POST['newPassword'];

    // -----------------------------
    // Check if email exists
    // -----------------------------
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode([
            "status" => "Email_does_not_exist"
        ]);
        exit;
    }

    // -----------------------------
    // Hash new password
    // -----------------------------
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // -----------------------------
    // Update password
    // -----------------------------
    $updateStmt = $pdo->prepare(
        "UPDATE users SET password = ? WHERE email = ?"
    );
    $updateStmt->execute([$hashedPassword, $email]);

    // -----------------------------
    // Success response
    // -----------------------------
    echo json_encode([
        "status" => "success"
    ]);
    exit;

} catch (Throwable $e) {
    error_log("password reset error: " . $e->getMessage());

    echo json_encode([
        "status" => "error"
    ]);
    exit;
}
