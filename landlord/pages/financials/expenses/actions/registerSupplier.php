<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');
require_once '../../../db/connect.php';

// Only run on POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
    exit;
}

// Destination page 
$redirectTo = '/jengopay/landlord/pages/financials/expenses/expenses.php'; 

// Ensure user is logged in
if (empty($_SESSION['user']['id'])) {
    $_SESSION['error'] = "Unauthorized access.";
    echo json_encode(['success' => false, 'redirect' => $redirectTo]);
    exit;
}

$userId = (int)$_SESSION['user']['id'];

// Fetch landlord ID linked to user
$stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
$stmt->execute([$userId]);
$landlord = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$landlord) {
    $_SESSION['error'] = "Landlord account not found.";
    echo json_encode(['success' => false, 'redirect' => $redirectTo]);
    exit;
}

$landlord_id = (int)$landlord['id'];

// Convert PHP errors to exceptions (optional)
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    $kra     = trim($_POST['kra'] ?? '');
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (!$kra || !$name || !$email || !$phone || !$address) {
        throw new Exception("All fields are required!");
    }

    // Check duplicate KRA for this landlord
    $stmt = $pdo->prepare("
        SELECT id
        FROM suppliers
        WHERE kra_pin = :kra AND landlord_id = :landlord_id
        LIMIT 1
    ");
    $stmt->execute([
        ':kra' => $kra,
        ':landlord_id' => $landlord_id
    ]);

    if ($stmt->fetch()) {
        throw new Exception("Supplier with this KRA already exists!");
    }

    // Insert supplier
    $stmt = $pdo->prepare("
        INSERT INTO suppliers (kra_pin, landlord_id, supplier_name, email, phone, address)
        VALUES (:kra, :landlord_id, :supplier_name, :email, :phone, :address)
    ");
    $stmt->execute([
        ':kra' => $kra,
        ':landlord_id' => $landlord_id,
        ':supplier_name' => $name,
        ':email' => $email,
        ':phone' => $phone,
        ':address' => $address,
    ]);

    $_SESSION['success'] = "Supplier registered successfully!";

    echo json_encode([
        'success' => true,
        'redirect' => $redirectTo
    ]);
    exit;

} catch (Throwable $e) {
    $_SESSION['error'] = 'Failed to register supplier: ' . $e->getMessage();

    echo json_encode([
        'success' => false,
        'redirect' => $redirectTo
    ]);
    exit;
}
