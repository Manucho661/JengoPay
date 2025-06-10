<?php
// update_unit.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all necessary fields are set
    if (isset($_POST['unit_id'], $_POST['unit_number'], $_POST['rooms'], $_POST['unit_type'], $_POST['room_type'], $_POST['floor_number'])) {
        // Sanitize and validate input values
        $unit_id = intval($_POST['unit_id']);
        $unit_number = htmlspecialchars($_POST['unit_number']);
        $rooms = htmlspecialchars($_POST['rooms']);
        $unit_type = htmlspecialchars($_POST['unit_type']);
        $room_type = htmlspecialchars($_POST['room_type']);
        $floor_number = intval($_POST['floor_number']);

        // Include the database connection (PDO)
        include '../db/connect.php';  // Include your DB connection

        try {
            // Update query with PDO prepared statement
            $query = "UPDATE units SET unit_number = :unit_number, rooms = :rooms, unit_type = :unit_type, room_type = :room_type, floor_number = :floor_number WHERE unit_id = :unit_id";
            $stmt = $pdo->prepare($query);

            // Bind the parameters
            $stmt->bindParam(':unit_number', $unit_number, PDO::PARAM_STR);
            $stmt->bindParam(':rooms', $rooms, PDO::PARAM_STR);
            $stmt->bindParam(':unit_type', $unit_type, PDO::PARAM_STR);
            $stmt->bindParam(':room_type', $room_type, PDO::PARAM_STR);
            $stmt->bindParam(':floor_number', $floor_number, PDO::PARAM_INT);
            $stmt->bindParam(':unit_id', $unit_id, PDO::PARAM_INT);

            // Execute the query
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Unit details updated successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update unit details.']);
            }

        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }

        // Optionally close the PDO connection (it will be closed automatically at the end of the script)
        $pdo = null;

    } else {
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    }
}
?>
