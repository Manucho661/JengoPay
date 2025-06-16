<?php
// get_unit_details.php

include '../db/connect.php'; // This includes the PDO connection

if (isset($_GET['unit_id'])) {
    $unit_id = intval($_GET['unit_id']);

    try {
        // Get the unit details using prepared statement
        $query = "SELECT * FROM units WHERE unit_id = :unit_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':unit_id', $unit_id, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the result
        $unit = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($unit) {
            echo json_encode(['success' => true, 'unit' => $unit]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Unit not found.']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }

    // Close the PDO connection (optional, as it will automatically close when the script ends)
    $pdo = null;
}
?>