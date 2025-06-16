<?php
include '../db/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $type = isset($_POST['type']) ? $_POST['type'] : null;

    if (!$id || !$type) {
        echo "Missing parameters.";
        exit;
    }

    try {
        switch ($type) {
            case 'users':
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
                break;

            case 'property':
                $stmt = $pdo->prepare("DELETE FROM properties WHERE property_id = :id");
                break;

            case 'maintenance':
                $stmt = $pdo->prepare("DELETE FROM maintenance_requests WHERE request_id = :id");
                break;



            case 'building':
                // First delete related units (and other child data, if any)
                $pdo->prepare("DELETE FROM units WHERE building_id = :id")->execute(['id' => $id]);
                // Then delete the building
                $stmt = $pdo->prepare("DELETE FROM buildings WHERE building_id = :id");
                break;

            case 'unit':
                  $stmt = $pdo->prepare("DELETE FROM units WHERE unit_id = :id");
                  break;


            case 'communication':
                // First delete related message_files and messages
                $pdo->prepare("DELETE FROM message_files WHERE thread_id = :id")->execute(['id' => $id]);
                $pdo->prepare("DELETE FROM messages WHERE thread_id = :id")->execute(['id' => $id]);
                // Then delete from communication
                $stmt = $pdo->prepare("DELETE FROM communication WHERE thread_id = :id");
                break;

            default:
                echo "Invalid type.";
                exit;
        }

        $stmt->execute(['id' => $id]);

        if ($stmt->rowCount() > 0) {
            echo "Record deleted successfully.";
        } else {
            echo "No record found or already deleted.";
        }

    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
?>
