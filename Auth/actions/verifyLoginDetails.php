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
    // Fetch name based on role
    // -----------------------------
    $name = '';
    if ($user['role'] === 'landlord') {
        // Fetch name from landlords table
        $stmt = $pdo->prepare("SELECT first_name, second_name FROM landlords WHERE user_id = ?");
        $stmt->execute([$user['id']]);
        $landlord = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($landlord) {
            $name = trim($landlord['first_name'] . ' ' . $landlord['second_name']);
        }
    } elseif ($user['role'] === 'provider') {
        // Fetch name from service_providers table
        $stmt = $pdo->prepare("SELECT name FROM service_providers WHERE user_id = ?");
        $stmt->execute([$user['id']]);
        $provider = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($provider) {
            $name = $provider['name'];
        }
    } elseif ($user['role'] === 'tenant') {
        // Fetch name tenants table
        $stmt = $pdo->prepare("SELECT first_name FROM tenants WHERE user_id = ?");
        $stmt->execute([$user['id']]);
        $tenant = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($tenant) {
            $name = $tenant['first_name'];
        }
    }

    // -----------------------------
    // Login successful
    // -----------------------------
    $_SESSION['user'] = [
        "id" => $user['id'],
        "name" => $name, // Set the name based on the role
        "role" => $user['role'] // server-side only
    ];

    // -----------------------------
    // Redirect user based on role
    // -----------------------------
    if ($user['role'] === 'landlord') {
        $redirectUrl = '/jengopay/landlord/pages/dashboard/dashboard.php';
    } elseif ($user['role'] === 'provider') {
        $redirectUrl = '/jengopay/service/requestOrders.php';
    } elseif ($user['role'] === 'tenant') {
        $redirectUrl = '/jengopay/tenant/tenant-portal.php';
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
