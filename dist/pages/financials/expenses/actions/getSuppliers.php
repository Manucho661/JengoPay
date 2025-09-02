<?php
include '../../db/connect.php';

try {
    $stm = $pdo->prepare("SELECT * FROM suppliers");
    $stm->execute();
    $suppliers = $stm->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $suppliers = []; // fallback if error
    $supplierError = $e->getMessage(); // pass error back
}
?>
