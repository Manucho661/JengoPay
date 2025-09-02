<?php
include '../../../db/connect.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id      = $_POST['supplierEditId'] ?? null; // supplier id (for updates)
        $kra     = $_POST['kra'] ?? null;
        $name    = $_POST['name'] ?? null;
        $email   = $_POST['email'] ?? null;
        $phone   = $_POST['phone'] ?? null;
        $address = $_POST['address'] ?? null;

        if ($id) {
            // ✅ Update existing supplier
            $stmt = $pdo->prepare("
                UPDATE suppliers
                SET kra_pin = :kra,
                    supplier_name = :supplier_name,
                    email = :email,
                    phone = :phone,
                    address = :address,
                    time_stamp = NOW()
                WHERE id = :id
            ");
            $stmt->execute([
                ':kra'           => $kra,
                ':supplier_name' => $name,
                ':email'         => $email,
                ':phone'         => $phone,
                ':address'       => $address,
                ':id'            => $id,
            ]);

            echo "Supplier updated successfully!";
        } 
        else{
            echo "id doesn't exist";
        }
    } catch (Throwable $e) {
        echo "Caught: " . $e->getMessage();
    }
}
