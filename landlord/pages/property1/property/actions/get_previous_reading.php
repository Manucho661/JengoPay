<?php
require_once '../db/connect.php';

header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validate input
if (!isset($_GET['unit'])) {
    die(json_encode(['error' => 'Unit parameter missing', 'received' => $_GET]));
}

$unitNumber = trim($_GET['unit']);

// Log the received unit for debugging
error_log("Fetching previous reading for unit: " . $unitNumber);

try {
    // Query to get the latest reading for the SPECIFIC unit
    $stmt = $pdo->prepare("SELECT unit_number, current_reading, reading_date
                          FROM meter_readings
                          WHERE unit_number = :unit
                          ORDER BY reading_date DESC
                          LIMIT 1");
    $stmt->bindParam(':unit', $unitNumber, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Debug logging
    error_log("Query result: " . print_r($result, true));

    if ($result && isset($result['current_reading'])) {
        // Verify the unit matches what we asked for
        if ($result['unit_number'] !== $unitNumber) {
            die(json_encode([
                'error' => 'Database returned wrong unit',
                'requested' => $unitNumber,
                'received' => $result['unit_number']
            ]));
        }

        echo json_encode([
            'success' => true,
            'previous_reading' => (float)$result['current_reading'],
            'unit_number' => $result['unit_number'],
            'reading_date' => $result['reading_date']
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'previous_reading' => null,
            'unit_number' => $unitNumber
        ]);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
?>