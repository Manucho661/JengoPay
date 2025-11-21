<?php
header('Content-Type: application/json');
session_start();

// Convert PHP errors → exceptions
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

require_once '../../db/connect.php'; // $pdo must exist

try {
    // Validate input
    if (empty($_POST['email']) || empty($_POST['password'])) {
        throw new Exception("Email or password missing");
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user by email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["status" => "user_not_found"]);
        exit;
    }

    // Verify hashed password
    if (!password_verify($password, $user['password'])) {
        echo json_encode([
            "status" => "incorrect_password"
        ]);
        exit;
    }

    // At this point: login successful
    $_SESSION['user'] = [
        "id" => $user['id'],
        "name" => $user['first_name'],
        "role" => $user['role']
    ];

    echo json_encode([
        "status" => "Logged in",
        "userName" => $user['first_name'],
        "userRole" => $user['role']
    ]);

} catch (Throwable $e) {
    error_log("Login error: " . $e->getMessage());
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
