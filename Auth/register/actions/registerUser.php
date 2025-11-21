<?php
header('Content-Type: application/json');
session_start();

// Convert PHP warnings/notices → exceptions
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

require_once '../../../db/connect.php'; // Must define $pdo (PDO instance)

try {
    // -----------------------------
    // Independent Field Validation
    // -----------------------------
    $errors = [];
    if (empty($_POST['role'])) {
        $errors[] = "role is required.";
    }
    if (empty($_POST['userName'])) {
        $errors[] = "Username is required.";
    }

    if (empty($_POST['email'])) {
        $errors[] = "Email is required.";
    }

    if (empty($_POST['password'])) {
        $errors[] = "Password is required.";
    }

    // Return validation errors
    if (!empty($errors)) {
        echo json_encode([
            "status" => "error",
            "errors" => $errors
        ]);
        exit;
    }

    // -----------------------------
    // Clean Inputs
    // -----------------------------
    $role   = trim($_POST['role']);
    $username = trim($_POST['userName']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // -----------------------------
    // Hash Password
    // -----------------------------
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // -----------------------------
    // Insert User
    // -----------------------------
    $stmt = $pdo->prepare("
        INSERT INTO users (username, email, password, role)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([$username, $email, $hashedPassword, $role]);

    // -----------------------------
    // Success Response
    // -----------------------------
    echo json_encode([
        "status"  => "success",
        "message" => "User registered successfully.",
        "user_id" => $pdo->lastInsertId()
    ]);
    exit;
} catch (Throwable $e) {
    // Log internal error
    error_log("register error: " . $e->getMessage());

    // Client-safe response
    echo json_encode([
        "status"  => "error",
        "message" => $e->getMessage()
    ]);
}
