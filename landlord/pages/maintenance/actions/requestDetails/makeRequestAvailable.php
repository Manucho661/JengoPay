<?php
header('Content-Type: application/json');

require_once '../../../db/connect.php'; // include your PDO connection

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // Check if the request method is POST (typically used for form submissions)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    }
    // Retrieve the data from the form
    $id = isset($_POST['id']) ? $_POST['id'] : null; // for updating a specific record
    $name = isset($_POST['budget']) ? $_POST['budget'] : '';
    $email = isset($_POST['duration']) ? $_POST['duration'] : '';

    if ($id && is_numeric($id)) {
        // Prepare an SQL query to update the record in the database
        $sql = "UPDATE maintenance_requets SET budget = :budget, duration = :duration WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        // Bind the values to the query parameters
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);

        // Execute the query
        if ($stmt->execute()) {
            // Respond with a success message
            echo json_encode(['status' => 'success', 'message' => 'Record updated successfully']);
        } else {
            // Respond with a failure message if the query execution failed
            echo json_encode(['status' => 'error', 'message' => 'Failed to update record']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
    }
} catch (Throwable $e) {
    // Handle exceptions and display an error message
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
