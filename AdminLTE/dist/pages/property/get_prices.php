<?php
// get_prices.php
header('Content-Type: application/json');

// Start session (if prices are stored in $_SESSION)
session_start();

// Connect to database (if prices are stored in DB)
require_once '../db/connect.php'; // Include your DB connection file

try {
    // Fetch prices from database (example query)
    $stmt = $pdo->query("SELECT water_price, electricity_price FROM buildings LIMIT 1");
    $prices = $stmt->fetch(PDO::FETCH_ASSOC);

    // Alternatively, use $_SESSION if prices are set there
    // $prices = [
    //     'water_price' => $_SESSION['water_price'] ?? 0,
    //     'electricity_price' => $_SESSION['electricity_price'] ?? 0
    // ];

    echo json_encode([
        'success' => true,
        'water_price' => $prices['water_price'] ?? 0,
        'electricity_price' => $prices['electricity_price'] ?? 0 // Fix typo if needed
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch prices: ' . $e->getMessage()
    ]);
}
?>