<?php
include '../../db/connect.php';

try {
    // Assuming $pdo is your PDO connection object
    $sql = "SELECT id, name, amount, entry_date, description, created_at FROM owners_equity ORDER BY entry_date ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $owners_equities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT SUM(amount) AS total_equity FROM owners_equity");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalEquity = $row['total_equity'] ?? 0;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
