<?php
include '../../db/connect.php'; // This should create a $pdo variable (PDO connection)

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rent_deadline'])) {
    $deadline = $_POST['rent_deadline'];

    try {
        $sql = "UPDATE settings SET rent_deadline = :deadline WHERE id = 1"; // Adjust WHERE clause as needed
        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([':deadline' => $deadline]);

        echo json_encode(['success' => $success]);
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}
?>
