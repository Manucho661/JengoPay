<?php
session_start();

// Convert PHP errors → exceptions
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

require_once '../../db/connect.php'; // $pdo must exist

try {
    // -----------------------------
    // Validate input
    // -----------------------------
    if (empty($_POST['email']) || empty($_POST['password'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid credentials"
        ]);
        exit;
    }

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // -----------------------------
    // Fetch user by email
    // -----------------------------
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        // Always return generic error
        echo json_encode([
            "status" => "error",
            "message" => "Invalid credentials"
        ]);
        exit;
    }

    // -----------------------------
    // Login successful
    // -----------------------------
    $_SESSION['user'] = [
        "id" => $user['id'],
        "name" => $user['name'],
        "role" => $user['role'] // server-side only
    ];

    // -----------------------------
    // Redirect user based on role
    // -----------------------------
    if ($user['role'] === 'landlord') {
        $redirectUrl = '/jengopay/landlord/pages/dashboard/index2.php';
    } elseif ($user['role'] === 'provider') {
        $redirectUrl = '/jengopay/service/requestOrders.php';
    } else {
        $redirectUrl = '/Jengopay/auth/login.php'; // fallback
    }

    // -----------------------------
    // Respond with minimal info
    // -----------------------------
    echo json_encode([
        "status" => "success",
        "redirect" => $redirectUrl
    ]);

} catch (Throwable $e) {
    error_log("Login error: " . $e->getMessage());
    echo json_encode([
        "status" => "error",
        "message" => "An error occurred. Please try again."
    ]);
}
