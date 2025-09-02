<?php
include '../../../db/connect.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Collect incoming POST values
        $kra     = $_POST['kra'] ?? null;
        $name    = $_POST['name'] ?? null;
        $email   = $_POST['email'] ?? null;
        $phone   = $_POST['phone'] ?? null;
        $address = $_POST['address'] ?? null;

        if (!$kra || !$name || !$email || !$phone || !$address) {
            throw new Exception("All fields are required!");
        }

        // 1. Check if KRA already exists
        $stmt = $pdo->prepare("SELECT id FROM suppliers WHERE kra_pin = :kra LIMIT 1");
        $stmt->execute([':kra' => $kra]);

        if ($stmt->fetch()) {
            throw new Exception("Supplier with this KRA already exists!");
        }

        // 2. Insert new supplier
        $stmt = $pdo->prepare("
            INSERT INTO suppliers (kra_pin, supplier_name, email, phone, address, time_stamp) 
            VALUES (:kra, :supplier_name, :email, :phone, :address, NOW())
        ");

        $stmt->execute([
            ':kra'           => $kra,
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
