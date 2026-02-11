<?php
header('Content-Type: application/json');
session_start();

// Convert PHP warnings/notices → exceptions
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

require_once '../../db/connect.php'; // Must define $pdo (PDO instance)

try {
    // -----------------------------
    // Independent Field Validation
    // -----------------------------
    $errors = [];
    if (empty($_POST['role'])) {
        $errors[] = "role is required.";
    }
    if (empty($_POST['first_name'])) {
        $errors[] = "first_name is required.";
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
    $role       = trim($_POST['role']);
    $first_name = trim($_POST['first_name']);
    $second_name = !empty($_POST['second_name']) ? trim($_POST['second_name']) : null;
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];


    // -----------------------------
    // Check if email already exists
    // -----------------------------
    $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->execute([$email]);
    if ($checkStmt->rowCount() > 0) {

        // Email exists
        echo json_encode([
            "status"  => "Email_exists"
        ]);
        exit;
    }

    // -----------------------------
    // Hash Password
    // -----------------------------
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // -----------------------------
    // Insert User
    // -----------------------------
    $stmt = $pdo->prepare("
        INSERT INTO users (email, password, role)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$email, $hashedPassword, $role]);

    $user_id = $pdo->lastInsertId();

    // -----------------------------
    // Insert into related table based on role
    // -----------------------------
    if ($role === "landlord") {
        $landlordStmt = $pdo->prepare("
            INSERT INTO landlords (user_id, first_name, second_name) VALUES (?, ?, ?)
        ");
        $landlordStmt->execute([$user_id, $first_name, $second_name]);
    } elseif ($role === "provider") {
        $providerStmt = $pdo->prepare("
            INSERT INTO service_providers (user_id, name) VALUES (?, ?)
        ");
        $providerStmt->execute([$user_id, $first_name]);
    }

    // -----------------------------
    // Success Response
    // -----------------------------
    echo json_encode([
        "status"  => "success",
        // "message" => "User registered successfully.",
        // "user_id" => $user_id
    ]);
    exit;
} catch (Throwable $e) {
    // Log internal error
    error_log("register error: " . $e->getMessage());

    // Client-safe response incase an error occurs (uncomment the error message);
    echo json_encode([
        // "status"  => "error",
        "errorMessage" => $e->getMessage()
    ]);
}
