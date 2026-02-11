<?php

declare(strict_types=1);

require_once '../../db/connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$buildings = [];

try {
    /* -------------------------------------------------
       1. Ensure user is logged in
    ------------------------------------------------- */
    if (!isset($_SESSION['user']['id'])) {
        throw new Exception('User not authenticated.');
    }

    $userId = (int) $_SESSION['user']['id'];

    /* -------------------------------------------------
       2. Get landlord ID from session user
    ------------------------------------------------- */
    $stmt = $pdo->prepare(
        "SELECT id FROM landlords WHERE user_id = ? LIMIT 1"
    );
    $stmt->execute([$userId]);

    $landlord = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$landlord) {
        throw new Exception('Landlord record not found.');
    }

    $landlordId = (int) $landlord['id'];

    /* -------------------------------------------------
       3. Fetch buildings for this landlord
    ------------------------------------------------- */
    $stmt = $pdo->prepare(
        "SELECT id, building_name
     FROM buildings
     WHERE landlord_id = ?
     ORDER BY building_name ASC"
    );
    $stmt->execute([$landlordId]);

    $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    // You can log this instead of echoing in production
    // echo "<p style='color:red;'>❌ {$e->getMessage()}</p>";
}

/* -------------------------------------------------
   4. Optional: Empty state handling
------------------------------------------------- */
if (empty($buildings)) {
    // echo "<p style='color:red;'>No buildings found for this landlord.</p>";
}
