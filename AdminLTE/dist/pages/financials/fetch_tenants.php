<?php
// get_tenants.php
include '../db/connect.php'; // Adjust this path to where your connect.php is located
                                  // relative to this 'get_tenants.php' file.

header('Content-Type: application/json'); // Crucial for AJAX to correctly parse the response

if (isset($_GET['building_id']) && is_numeric($_GET['building_id'])) {
    $buildingId = $_GET['building_id'];

    try {
        // Use $pdo for your database connection, as defined in connect.php
        $stmt = $pdo->prepare("
            SELECT
                t.id AS tenant_id,
                u.first_name,
                u.middle_name
            FROM
                tenants t
            JOIN
                users u ON t.user_id = u.id
            WHERE
                t.building_id = :building_id
            ORDER BY
                u.first_name ASC, u.middle_name ASC
        ");
        $stmt->bindParam(':building_id', $buildingId, PDO::PARAM_INT);
        $stmt->execute();
        $tenants = $stmt->fetchAll(); // PDO::FETCH_ASSOC is default by connect.php

        echo json_encode($tenants); // Send the fetched tenants back as JSON

    } catch (PDOException $e) {
        // Log the error for internal debugging
        error_log("Database error fetching tenants for building ID " . $buildingId . ": " . $e->getMessage());
        // Send a generic error message to the client
        echo json_encode(["error" => "Failed to retrieve tenants. Please try again."]);
    }
} else {
    // If building_id is missing or invalid in the request
    echo json_encode(["error" => "Invalid or missing building ID parameter."]);
}
?>