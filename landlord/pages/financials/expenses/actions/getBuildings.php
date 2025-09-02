<?php
include '../../db/connect.php';

try {
    $buildings = $pdo->prepare("SELECT * FROM buildings");
    $buildings->execute();
    $buildings = $buildings->fetchAll(PDO::FETCH_ASSOC);

    if (empty($buildings)) {
        echo "<p style='color:red;'>No buildings found in database.</p>";
    }
} catch (PDOException $e) {
    $errorMessage = "❌ Failed to fetch buildings: " . $e->getMessage();
    $buildings = []; // default to empty array if error occurs
}
?>