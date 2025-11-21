<?php
header('Content-Type: application/json');
session_start();

// Convert PHP errors → exceptions
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

require_once '../../db/connect.php'; // must create PDO instance named $pdo

try {
    // Validate input
    if (empty($_POST['email']) || empty($_POST['password'])) {
        throw new Exception("Email or password not provided");
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare query using PDO
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");

    // Execute with array (this replaces bind_param)
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["status" => "user not found"]);
        exit;
    }

    if (!$password === $user['password']) {
        
        echo json_encode([
            "password" => $password,
            "userpassword" => $user['password'],
            "status" => "incorrect password"]);
        exit;
    }

    // $_SESSION['user'] = $user['username'];

    echo json_encode([
        
        "status" => "Logged in",
        "userName" => $user['first_name'],
        "userRole" => $user ['role']
    ]);
    
} catch (Throwable $e) {

    error_log("Login error: " . $e->getMessage());

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
