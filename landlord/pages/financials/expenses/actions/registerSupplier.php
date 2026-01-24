<?php
include '../../../db/connect.php';
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user']['id'])) {
    die("Unauthorized access.");
}

// Get logged-in user ID
$userId = $_SESSION['user']['id'];

// Fetch landlord ID linked to user
$stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ?");
$stmt->execute([$userId]);
$landlord = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$landlord) {
    die("Landlord account not found.");
}

$landlord_id = $landlord['id'];

// Convert PHP errors to exceptions
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Collect incoming POST values
        $kra     = trim($_POST['kra'] ?? '');
        $name    = trim($_POST['name'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $phone   = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');

        // Basic validation
        if (!$kra || !$name || !$email || !$phone || !$address) {
            throw new Exception("All fields are required!");
        }

        // Check if supplier with same KRA already exists for this landlord
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

        // Insert new supplier (FIXED column/value mismatch)
        $stmt = $pdo->prepare("
            INSERT INTO suppliers 
                (kra_pin, landlord_id, supplier_name, email, phone, address) 
            VALUES 
                (:kra, :landlord_id, :supplier_name, :email, :phone, :address)
        ");

        $stmt->execute([
            ':kra'           => $kra,
            ':landlord_id'   => $landlord_id,
            ':supplier_name' => $name,
            ':email'         => $email,
            ':phone'         => $phone,
            ':address'       => $address,
        ]);

        echo "Supplier registered successfully!";
    } catch (Throwable $e) {
        echo "Caught: " . $e->getMessage();
    }
}
