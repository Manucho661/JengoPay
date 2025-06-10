<?php
// update_property.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the required fields
    if (isset($_POST['building_id'], $_POST['county'], $_POST['ownership_info'], $_POST['units_number'])) {
        // Sanitize and assign POST data
        $building_id = intval($_POST['building_id']);
        $location = htmlspecialchars($_POST['county']);
        $ownership = htmlspecialchars($_POST['ownership_info']);
        $units = intval($_POST['units_number']);

        // Database connection using PDO
        include '../db/connect.php'; // This file must set up a $pdo object

        try {
            // Prepare the update query
            $query = "UPDATE buildings SET county = :county, ownership_info = :ownership, units_number = :units WHERE building_id = :building_id";
            $stmt = $pdo->prepare($query);

            // Bind parameters
            $stmt->bindParam(':county', $location, PDO::PARAM_STR);
            $stmt->bindParam(':ownership', $ownership, PDO::PARAM_STR);
            $stmt->bindParam(':units', $units, PDO::PARAM_INT);
            $stmt->bindParam(':building_id', $building_id, PDO::PARAM_INT);

            // Execute the query
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Property details updated successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update property details.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    }
}
?>
