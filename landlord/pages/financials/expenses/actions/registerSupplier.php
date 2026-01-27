<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../../db/connect.php';

// Only run when the supplier form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['register_supplier'])) {
    return;
}

// Ensure user is logged in
if (empty($_SESSION['user']['id'])) {
    $_SESSION['error'] = "Unauthorized access.";
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

$userId = (int)$_SESSION['user']['id'];

// Fetch landlord ID linked to user
$stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
$stmt->execute([$userId]);
$landlord = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$landlord) {
    $_SESSION['error'] = "Landlord account not found.";
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

$landlord_id = (int)$landlord['id'];

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
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;

} catch (Throwable $e) {
    $_SESSION['error'] = 'Failed to register supplier: ' . $e->getMessage();
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}
