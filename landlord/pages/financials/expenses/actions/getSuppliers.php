<?php
include '../../db/connect.php';

try {
    // ✅ Get logged-in user's landlord_id
    $userId = $_SESSION['user']['id'] ?? null;

    if (!$userId) {
        throw new Exception("User not logged in.");
    }

    $stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
    $stmt->execute([$userId]);
    $landlord = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$landlord) {
        throw new Exception("Landlord record not found for this user.");
    }

    $landlord_id = $landlord['id'];

    // ✅ Fetch only suppliers belonging to this landlord
    $stm = $pdo->prepare("SELECT * FROM suppliers WHERE landlord_id = ? ORDER BY supplier_name ASC");
    $stm->execute([$landlord_id]);
    $suppliers = $stm->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $suppliers = [];              // fallback if error
    $supplierError = $e->getMessage(); // pass error back if needed
}
?>
